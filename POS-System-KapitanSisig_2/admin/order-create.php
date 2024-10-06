<?php

include('includes/header.php'); ?>

<div class="modal fade" id="addCustomerModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Add Customer</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
            <label>Enter Name</label>
            <input type="text" class="form-control" id="c_name"/>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary saveCustomer">Save</button>
      </div>
    </div>
  </div>
</div>

<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Create Order
                <a href="#" class = "btn btn-outline-danger float-end">Back</a> 
            </h4>
        </div>
        <div class="card-body">
        <?php alertMessage(); ?>
            <form action="orders-code.php" method="POST">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="">Select Products</label>
                        <select name="product_id" class="form-select mySelect2 ">
                            <option value="">-- Select Product --</option>
                            <?php
                                $products = getAll('products');
                                if($products) {
                                    if(mysqli_num_rows($products) > 0) {
                                        foreach($products as $prodItem) {
                                            ?>
                                                <option value="<?= $prodItem['id']; ?>"><?= $prodItem['productname']; ?></option>
                                            <?php
                                        }
                                    } else {
                                        echo '<option value="">No product found!</option>';
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
                    <div class="col-md-3 mb-3 text-end">
                        <br/>
                        <button type="submit" name="addItem" class="btn btn-outline-primary">Add Item</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="card mt-3">
        <div class="card-header">
             <h4 class="mb-0">Products</h4>
        </div>
        <div class="card-body" id= "productArea">
             <?php
              if(isset($_SESSION['productItems']))
              {
                $sessionProducts = $_SESSION['productItems'];
                if(empty($sessionProducts)){
                    unset($_SESSION['productItems']);
                    unset($_SESSION['productItemIds']);
                }
                  ?>
                <div class="mb-3" id= "productContent">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Product Name</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total Price</th> 
                                <th>Remove</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $i=1;
                                foreach($sessionProducts as $key => $item ) : 
                            ?>
                                <tr>
                                <td><?= $i++; ?></td>
                                <td><?= $item['name']; ?></td>
                                <td>Php <?= $item['price']; ?></td>
                                <td>
                                     <div class="input-group qtyBox">
                                        <input type="hidden" value="<?= $item['product_id'];?>" class = "prodId" >
                                        <button class="input-group-text decrement">-</button>
                                        <input type="text" value="<?= $item['quantity']; ?>" class="qty quantityInput" />
                                        <button class="input-group-text increment">+</button>
                                     </div>
                                </td>
                                <td>Php <?= number_format($item['price'] * $item['quantity'], 2); ?></td>
                                <td>
                                <a href="order-item-delete.php?index=<?= $key; ?>" class="btn btn-danger">Remove</a>
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
                            <select id="payment_mode" class="form-select">
                                <option value="">-- Select Payment --</option>
                                <option value="Cash Payment">Cash Payment</option>
                                <option value="Online Payment">Online Payment</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label>Enter Customer Name</label>
                            <input type="text" id="cname" class="form-control" value="" />
                        </div>
                        <div class="col-md-4">
                            <br/>
                            <button type="button" class="btn btn-warning w-100 proceedToPlace">Proceed to place order</button>
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