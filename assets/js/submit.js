function addIngredient() {
    const container = document.getElementById("ingredients-container");
    const group = document.createElement("div");
    group.className = "ingredient-group";
    group.innerHTML = `
        <input type="text" name="ingredient_name[]" placeholder="Ingredient Name" required>
        <input type="text" name="ingredient_qty[]" placeholder="Quantity" required>
        <button type="button" onclick="removeElement(this)">−</button>
    `;
    container.appendChild(group);
}

function addStep() {
    const container = document.getElementById("steps-container");
    const group = document.createElement("div");
    group.className = "step-group";
    group.innerHTML = `
        <textarea name="steps[]" placeholder="Describe this step" required></textarea>
        <button type="button" onclick="removeElement(this)">−</button>
    `;
    container.appendChild(group);
}

function removeElement(btn) {
    btn.parentElement.remove();
}
