<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: dashboard.php");
    exit();
}

require_once "../config/database.php";

// Pagination
$recipesPerPage = 2;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $recipesPerPage;

// Search, filters, and sorting
$query = isset($_GET['query']) ? trim($_GET['query']) : '';
$sortBy = isset($_GET['sort_by']) ? $_GET['sort_by'] : '';
$sortOrder = (isset($_GET['sort_order']) && strtolower($_GET['sort_order']) === 'asc') ? 'ASC' : 'DESC';

$vegFilters = ['is_veg', 'is_jain'];         // these must be true (1) if selected
$allergyFilters = ['contains_gluten', 'contains_eggs', 'contains_nuts', 'contains_dairy']; // these must be false (0) if selected
$filters = array_merge($vegFilters, $allergyFilters);
// Base query
$sql = "SELECT 
            recipes.id AS id, 
            recipes.title, 
            recipes.description, 
            recipes.prep_time, 
            recipes.cook_time, 
            recipes.servings, 
            recipes.published_date,
            flags.is_veg, 
            flags.is_jain, 
            flags.contains_dairy, 
            flags.contains_nuts, 
            flags.contains_eggs, 
            flags.contains_gluten
        FROM recipes
        JOIN flags ON recipes.id = flags.recipe_id
        WHERE 1=1";
$params = [];
$types = "";

// Search
if (!empty($query)) {
    $sql .= " AND (recipes.title LIKE ? OR recipes.description LIKE ?)";
    $searchTerm = '%' . $query . '%';
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $types .= "ss";
}

// Apply "must be true" filters
foreach ($vegFilters as $filter) {
    if (isset($_GET[$filter])) {
        $sql .= " AND flags.$filter = ?";
        $params[] = 1;
        $types .= "i";
    }
}

// Apply "must be false" filters (exclude allergens)
foreach ($allergyFilters as $filter) {
    if (isset($_GET[$filter])) {
        $sql .= " AND flags.$filter = ?";
        $params[] = 0;
        $types .= "i";
    }
}

// Sorting
$validSortFields = ['prep_time', 'cook_time', 'servings'];
if (in_array($sortBy, $validSortFields)) {
    $sql .= " ORDER BY recipes.$sortBy $sortOrder";
} else {
    $sql .= " ORDER BY recipes.published_date DESC";
}

// Total count for pagination
$countSql = "SELECT COUNT(*) AS total FROM ($sql) AS count_table";
$countStmt = $con->prepare($countSql);
if ($types) {
    $countStmt->bind_param($types, ...$params);
}
$countStmt->execute();
$countResult = $countStmt->get_result();
$totalRecipes = $countResult->fetch_assoc()['total'];
$totalPages = ceil($totalRecipes / $recipesPerPage);

// Apply LIMIT and OFFSET
$sql .= " LIMIT ? OFFSET ?";
$params[] = $recipesPerPage;
$params[] = $offset;
$types .= "ii";

// Final execution
$stmt = $con->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Recipes</title>
    <link rel="stylesheet" href="../assets/css/search_styles.css">
</head>
<body>
<?php include '../components/navbar.php'; ?>

    <main>
        <section class="search-bar-container">
            <form method="GET" action="search.php" class="search-form">
                <input type="text" name="query" class="search-bar" placeholder="Search for recipes..." value="<?php echo htmlspecialchars($query); ?>">

                <!-- Preserve filters -->
                <?php foreach ($filters as $filter): ?>
                    <?php if (isset($_GET[$filter])): ?>
                        <input type="hidden" name="<?php echo $filter; ?>" value="<?php echo $_GET[$filter]; ?>">
                    <?php endif; ?>
                <?php endforeach; ?>

                <!-- Preserve sort -->
                <?php if ($sortBy): ?>
                    <input type="hidden" name="sort_by" value="<?php echo $sortBy; ?>">
                <?php endif; ?>
                <?php if (isset($_GET['sort_order'])): ?>
                    <input type="hidden" name="sort_order" value="<?php echo $_GET['sort_order']; ?>">
                <?php endif; ?>

                <button type="submit" class="search-btn">Search</button>
            </form>
        </section>

        <aside>
            <h3>Filter & Sort</h3>
            <form method="GET" action="search.php" class="filter-form">
                <input type="hidden" name="query" value="<?php echo htmlspecialchars($query); ?>">
                <input type="hidden" name="page" value="1">

                <!-- Filters -->
                <div class="filters">
                    <?php foreach ($filters as $filter): ?>
                        <label>
                            <input type="checkbox" class="styled-checkbox" name="<?php echo $filter; ?>" value="<?php echo ($filter === 'is_veg' || $filter === 'is_jain') ? '1' : '0'; ?>"
                                <?php if (isset($_GET[$filter])) echo 'checked'; ?>>
                            <?php echo ucwords(str_replace("_", " ", $filter)); ?>
                        </label><br>
                    <?php endforeach; ?>
                </div>

                <!-- Sort -->
                <div class="sort">
                    <div class="sort-group">
                        <label for="sort_by">Sort By:</label>
                        <select name="sort_by" id="sort_by">
                            <option value="">Default</option>
                            <option value="prep_time" <?php if ($sortBy === 'prep_time') echo 'selected'; ?>>Prep Time</option>
                            <option value="cook_time" <?php if ($sortBy === 'cook_time') echo 'selected'; ?>>Cook Time</option>
                            <option value="servings" <?php if ($sortBy === 'servings') echo 'selected'; ?>>Servings</option>
                        </select>
                    </div>

                    <div class="sort-group">
                        <label for="sort_order">Order:</label>
                        <select name="sort_order" id="sort_order">
                            <option value="asc" <?php if ($sortOrder === 'ASC') echo 'selected'; ?>>Ascending</option>
                            <option value="desc" <?php if ($sortOrder === 'DESC') echo 'selected'; ?>>Descending</option>
                        </select>
                    </div>
                </div>


                <button type="submit" class="filter-btn">Apply Filters & Sort</button>
            </form>
        </aside>

        <section class="recipes-container">
            <h2 class="recipe-list-header"><?php echo empty($query) ? "Latest Recipes" : "Search Results"; ?></h2>

            <hr>

            <?php if ($result->num_rows > 0): ?>
                <ul class=recipe-list>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <li class="recipe-item">
                            <div class="recipe-info">
                                <div class="text-info">
                                    <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                                    <p><?php echo htmlspecialchars($row['description']); ?></p>
                                </div>
                                <div class="info-tabs">
                                    <div class="info-tab">
                                        <p class="info-tab-head">Prep Time</p>
                                        <p class="info-tab-content"><?php echo htmlspecialchars($row['prep_time']); ?></p>
                                    </div>
                                    <div class="info-tab">
                                        <p class="info-tab-head">Cook Time</p>
                                        <p class="info-tab-content"><?php echo htmlspecialchars($row['cook_time']); ?></p>
                                    </div>
                                    <div class="info-tab">
                                        <p class="info-tab-head">Serves</p>
                                        <p class="info-tab-content"><?php echo htmlspecialchars($row['servings']); ?></p>
                                    </div>
                                </div>
                                
                            </div>
                            <div class="recipes-action">
                                <div class="flags">
                                    <?php if ($row['is_veg']) : ?>
                                            <span class="pill green-pill">Veg</span>
                                    <?php else: ?>
                                        <span class="pill red-pill">Non-Veg</span>
                                    <?php endif;?>
                                    <?php if ($row['is_jain']) : ?>
                                        <span class="pill green-pill">Jain</span>
                                    <?php endif;?>
                                    <?php if ($row['contains_dairy']) : ?>
                                        <span class="pill red-pill">Contains Dairy</span>
                                    <?php endif;?>
                                    <?php if ($row['contains_nuts']) : ?>
                                        <span class="pill red-pill">Contains Nuts</span>
                                    <?php endif;?>
                                    <?php if ($row['contains_eggs']) : ?>
                                        <span class="pill red-pill">Contains Eggs</span>
                                    <?php endif;?>
                                    <?php if ($row['contains_gluten']) : ?>
                                        <span class="pill red-pill">Contains Gluten</span>
                                    <?php endif;?>
                                </div>
                                <div class="action-container">
                                    <a class="recipe-list-button" href="./recipe_detail.php?id=<?php echo $row['id']; ?>">View Recipe</a>
                                </div>
                            </div>    
                        </li>
                    <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <p>No recipes found.</p>
            <?php endif; ?>

            <!-- Pagination -->
            <?php
            $getParams = $_GET;
            unset($getParams['page']);
            $baseQueryString = http_build_query($getParams);
            ?>
            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="?<?php echo $baseQueryString . '&page=' . ($page - 1); ?>" class="page-link">&laquo; Prev</a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="?<?php echo $baseQueryString . '&page=' . $i; ?>" 
                    class="page-link <?php if ($i === $page) echo 'active'; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>

                <?php if ($page < $totalPages): ?>
                    <a href="?<?php echo $baseQueryString . '&page=' . ($page + 1); ?>" class="page-link">Next &raquo;</a>
                <?php endif; ?>
            </div>

        </section>
    </main>
    <?php include '../components/footer.php'; ?>

</body>
</html>
