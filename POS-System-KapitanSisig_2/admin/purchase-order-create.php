<?php

include('includes/header.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);

?>

<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Create Purchase Order
                <a href="#" class="btn btn-outline-danger float-end">Back</a> 
            </h4>
        </div>
        <div class="card-body">
        <?php alertMessage(); ?>
            <form action="purchase-orders-code.php" method="POST">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="">Select Ingredients</label>
                        <select name="ingredient_id" class="form-select mySelect2">
                            <option value="">-- Select Ingredient --</option>
                            <?php
                                $ingredients = getAll('ingredients');
                                if($ingredients) {
                                    if(mysqli_num_rows($ingredients) > 0) {
                                        foreach($ingredients as $ingItem) {
                                            ?>
                                                <option value="<?= $ingItem['id']; ?>"><?= $ingItem['name']; ?></option>
                                            <?php
                                        }
                                    } else {
                                        echo '<option value="">No Ingredients found!</option>';
                                    }
                                } else {
                                    echo '<option value="">Something went wrong!</option>';
                                }
                            ?>
                        </select>
                    </div>
                    
                    <div class="col-md-2 mb-3">
                        <label for="">Quantity</label>
                        <input type="number" name="quantity" value="1" min="1" class="form-control" />
                    </div>
                    <div class="col-md-3 mb-3">
                        <br/>
                        <button type="submit" name="addIngredient" class="btn btn-outline-primary">Add Ingredient</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="card mt-3 mb-4">
        <div class="card-header">
             <h4 class="mb-0">Ingredients</h4>
        </div>
        <div class="card-body" id="ingredientArea">
             <?php
              if(isset($_SESSION['ingredientItems']))
              {
                $sessionIngredients = $_SESSION['ingredientItems'];
                if(empty($sessionIngredients)){
                    unset($_SESSION['ingredientItems']);
                    unset($_SESSION['ingredientItemIds']);
                }
                  ?>
                <div class="mb-3" id="ingredientContent">
                    <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Product Name</th>
                            <th>UoM</th> <!-- Update header for UoM -->
                            <th>Category</th>
                            <th>Sub Category</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total Price</th> 
                            <th>Remove</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $i = 1;
                            foreach($sessionIngredients as $key => $item) : 
                        ?>
                            <tr>
                                <td><?= $i++; ?></td>
                                <td><?= $item['name']; ?></td>
                                <td><?= $item['unit_name']; ?></td> <!-- Updated to display UoM name -->
                                <td><?= $item['category']; ?></td>
                                <td><?= $item['sub_category']; ?></td>
                                <td>Php <?= $item['price']; ?></td>
                                <td>
                                <div class="input-group qtyBox">
                                    <input type="hidden" value="<?= $item['ingredient_id'];?>" class="ingId">
                                    <button class="input-group-text ing-decrement">-</button>
                                    <input type="text" value="<?= $item['quantity']; ?>" class="qty quantityInput" />
                                    <button class="input-group-text ing-increment">+</button>
                                </div>

                                </td>
                                <td>Php <?= number_format($item['price'] * $item['quantity'], 2); ?></td>
                                <td>
                                    <a href="purchase-order-item-delete.php?index=<?= $key; ?>" class="btn btn-danger">Remove</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>


                    </table>

                    <div class="mt-2">
                    <hr>
                    <div class="row">
                        <div class="col-md-4">
                            <label>Select Payment Method</label>
                            <select id="ingPayment_mode" class="form-select">
                                <option value="">-- Select Payment --</option>
                                <option value="Cash Payment">Cash Payment</option>
                                <option value="Online Payment">Online Payment</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label>Enter Admin Name</label>
                            <input type="text" id="adminName" class="form-control" value="" />
                        </div>
                        <div class="col-md-3 mb-3">
                        <label for="">Select Supplier</label>
                        <select name="supplier_id" id="supplierName" class="form-select mySelect2 ">
                            <option value="">-- Select Supplier --</option>
                            <?php
                                $suppliers = getAll('suppliers');
                                if($suppliers) {
                                    if(mysqli_num_rows($suppliers) > 0) {
                                        foreach($suppliers as $supplierItems) {
                                            ?>
                                                <option value="<?= $supplierItems['id']; ?>"><?= $supplierItems['firstname']; ?></option>
                                            <?php
                                        }
                                    } else {
                                        echo '<option value="">No Suppliers found!</option>';
                                    }
                                } else {
                                    echo '<option value="">Something went wrong!</option>';
                                }
                            ?>
                        </select>
                    </div>
                        <div class="col-md-4">
                            <br/>
                            <button type="button" class="btn btn-warning w-100 proceedToPlaceIng">Proceed to place order</button>
                        </div>
                    </div>
                </div>


                
                </div>
                <?php
              }
              else
              {
                 echo '<h5>No items added</h5>';
              }
             ?>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
