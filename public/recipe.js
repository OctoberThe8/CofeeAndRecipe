// recipe.js
document.addEventListener('DOMContentLoaded', function() {
    // Save recipe button functionality
    document.querySelectorAll('.btn-save-recipe').forEach(button => {
        // Add a click event listener to each "Save Recipe" button
        button.addEventListener('click', function () {
            // Get the recipe ID stored in the button's data attribute
            const recipeId = this.dataset.recipeId;
            // Send a POST request to the API to save or unsave the recipe
            fetch('/api/save-recipe.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ recipe_id: recipeId })
            })
            .then(response => response.json())
            .then(data => {
                // If the request was successful, update the button text
                if(data.success) {
                    this.textContent = data.saved ? 'Saved' : 'Save Recipe';
                }
            });
        });
    });
});