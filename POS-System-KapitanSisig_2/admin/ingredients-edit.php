<?php include('includes/header.php'); ?>

<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Edit Ingredient
                <a href="ingredients-view.php" class="btn btn-outline-danger float-end">Back</a>
            </h4>
        </div>
        <div class="card-body">
            <?php
            if (isset($_GET['id'])) {
                $ingredientData = getById('ingredients', $_GET['id']);
                if ($ingredientData['status'] == 200) {
                    $ingredient = $ingredientData['data'];
            ?>
            <form action="code.php" method="POST">
                <input type="hidden" name="ingredientId" value="<?= htmlspecialchars($ingredient['id']) ?>">

                <div class="mb-3">
                    <label for="name">Ingredient Name</label>
                    <input type="text" id="name" name="name" value="<?= htmlspecialchars($ingredient['name']) ?>" class="form-control" required>
                </div>

                

                <div class="mb-3">
                    <label for="unit_id">Select Unit *</label>
                    <select id="unit_id" name="unit_id" class="form-select" required>
                        <option value="">Select Unit</option>
                        <?php
                        $unitResult = getAll('units');
                        if ($unitResult && mysqli_num_rows($unitResult) > 0) {
                            while ($uItem = mysqli_fetch_assoc($unitResult)) {
                                $selected = ($uItem['id'] == $ingredient['unit_id']) ? 'selected' : '';
                                echo '<option value="' . htmlspecialchars($uItem['id']) . '" ' . $selected . '>' . htmlspecialchars($uItem['name']) . '</option>';
                            }
                        } else {
                            echo '<option value="">No units found!</option>';
                        }
                        ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="category">Category:</label>
                    <select id="category" name="category" class="form-control" required>
                        <option value="Main Ingredients" <?= $ingredient['category'] == 'Main Ingredients' ? 'selected' : '' ?>>Main Ingredients</option>
                        <option value="Commissary" <?= $ingredient['category'] == 'Commissary' ? 'selected' : '' ?>>Commissary</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="sub_category">Sub Category:</label>
                    <select id="sub_category" name="sub_category" class="form-control" required>
                        <option value="" disabled>Select Sub Category</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                                <label for="">Price *</label>
                                <input type="number" name="price" value="<?= $productData['data']['price']; ?>" class="form-control" />
                            </div>                

                <button type="submit" name="updateIngredient" class="btn btn-outline-primary">Update Ingredient</button>
            </form>
            <?php
                } else {
                    echo '<h4>No Ingredient Found.</h4>';
                }
            } else {
                echo '<h4>Invalid Request.</h4>';
            }
            ?>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var category = document.getElementById('category').value;
    var subCategorySelect = document.getElementById('sub_category');
    
    var subCategories = [];
    if (category === 'Main Ingredients') {
        subCategories = ['Meat & Poultry', 'Vegetables', 'Others'];
    } else if (category === 'Commissary') {
        subCategories = ['Condiments & Sauces', 'Spices & Herbs', 'Toppings', 'Cutlery', 'Others'];
    }

    subCategories.forEach(function(subCategory) {
        var option = document.createElement('option');
        option.value = subCategory;
        option.text = subCategory;
        if (subCategory === '<?= htmlspecialchars($ingredient['sub_category']) ?>') {
            option.selected = true;
        }
        subCategorySelect.appendChild(option);
    });
});
</script>
