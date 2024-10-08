<?php include('includes/header.php'); ?>

<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
    <div class="card-header">
            <h4 class="mb-0">Add Ingredient
                <a href="ingredients-view.php" class = "btn btn-outline-danger float-end">Back</a> 
            </h4>
        </div>
        <div class="card-body">
            <?php alertMessage(); ?>
            <form action="code.php" method="POST">
                <!-- Ingredient Name -->
                <div class="mb-3">
                    <label for="name">Ingredient Name:</label>
                    <input type="text" id="name" name="name" class="form-control" required>
                </div>
                
                
                <!-- Unit Selection -->
                <div class="mb-3">
                    <label for="unit_id">Select Unit:</label>
                    <select id="unit_id" name="unit_id" class="form-select" required>
                        <option value="">Select Unit</option>
                        <?php
                        $unitResult = getAll('units'); // Fetch all units from the database
                        if ($unitResult && mysqli_num_rows($unitResult) > 0) {
                            while ($uItem = mysqli_fetch_assoc($unitResult)) {
                                echo '<option value="'.htmlspecialchars($uItem['id']).'">'.htmlspecialchars($uItem['name']).'</option>';
                            }
                        } else {
                            echo '<option value="">No units found!</option>';
                        }
                        ?>
                    </select>
                </div>

                <!-- Category Selection -->
                <div class="mb-3">
                    <label for="category">Category:</label>
                    <select id="category" name="category" class="form-select" required>
                        <option value="Main Ingredients">Main Ingredients</option>
                        <option value="Commissary">Commissary</option>
                    </select>
                </div>

                <!-- Sub-Category Selection -->
                <div class="mb-3">
                    <label for="sub_category">Sub Category:</label>
                    <select id="sub_category" name="sub_category" class="form-select" required>
                        <!-- Options will be populated by JavaScript -->
                    </select>
                </div>

                <div class="col-md-4 mb-3">
                        <label for="">Price *</label>
                        <input type="number" name="price" required class="form-control" />
                    </div>

                <!-- Submit Button -->
                <button type="submit" name="saveIngredient" class="btn btn-outline-primary">Save Ingredient</button>
            </form>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const categorySelect = document.getElementById('category');
    const subCategorySelect = document.getElementById('sub_category');

    const subCategoryOptions = {
        'Main Ingredients': ['Meat & Poultry', 'Vegetables', 'Others'],
        'Commissary': ['Condiments & Sauces', 'Spices & Herbs', 'Toppings', 'Cutlery', 'Others']
    };

    function updateSubCategoryOptions() {
        const selectedCategory = categorySelect.value;
        subCategorySelect.innerHTML = ''; // Clear existing options

        if (subCategoryOptions[selectedCategory]) {
            subCategoryOptions[selectedCategory].forEach(function(subCategory) {
                const option = document.createElement('option');
                option.value = subCategory;
                option.text = subCategory;
                subCategorySelect.appendChild(option);
            });
        }
    }

    categorySelect.addEventListener('change', updateSubCategoryOptions);

    // Initialize sub-category options on page load
    updateSubCategoryOptions();
});
</script>
