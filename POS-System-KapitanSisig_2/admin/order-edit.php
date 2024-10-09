<?php
include('includes/header.php'); 
?>

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

<div class="container-fluid px-4 pb-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Edit Order
                <a href="orders.php" class="btn btn-outline-danger float-end">Back</a> 
            </h4>
        </div>
        <div class="card-body">
            <?php alertMessage(); ?>
                <form action="orders-code.php" method="POST" enctype="multipart/form-data">
            
                <?php 
                    $trackingNo = validate($_GET['track']);

                    $query = "SELECT o.*, c.* FROM orders o, customers c WHERE 
                                c.id = o.customer_id AND tracking_no='$trackingNo' 
                                ORDER BY o.id DESC";
        
                    $orders = mysqli_query($conn, $query);

                    $orderItemQuery = "SELECT oi.quantity as orderItemQuantity, oi.price as orderItemPrice, o.*, oi.*, p.* 
                    FROM orders as o, order_items as oi, products as p 
                    WHERE  oi.order_id = o.id AND p.id = oi.product_id AND o.tracking_no='$trackingNo' ";

                    $orderItemsRes = mysqli_query($conn, $orderItemQuery);
                    $orderData = mysqli_fetch_assoc($orders);  
                    $orderId = $orderData['id'];
                        ?>
                            <input type="hidden" name="order_id" value="<?= $orderData['data']['id']; ?>" />
                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <label for="">Select Products</label>
                                        <select name="product_id" class="form-select mySelect2">
                                            <option value="">-- Select Product --</option>
                                            <?php
                                            $products = getAll('products');
                                            if ($products) {
                                                if (mysqli_num_rows($products) > 0) {
                                                    foreach ($products as $prodItem) {
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
                                    <div class="col-md-3 mb-3">
                                        <br/>
                                        <button type="submit" name="addItemForUpdateOrder" class="btn btn-outline-primary">Add Item</button>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label>Select Order Status</label>
                                        <select name="order_status" class="form-select">
                                            <option value="Placed" <?= ($orderData['order_status'] == 'Placed') ? 'selected' : ''; ?>>Placed</option>
                                            <option value="Preparing" <?= ($orderData['order_status'] == 'Preparing') ? 'selected' : ''; ?>>Preparing</option>
                                            <option value="Completed" <?= ($orderData['order_status'] == 'Completed') ? 'selected' : ''; ?>>Completed</option>
                                            <option value="Cancelled" <?= ($orderData['order_status'] == 'Cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                                        </select>
                                    </div>
                </div>
            </form>

            <!-- Display existing order items -->
            <?php if($orderItemsRes): ?>
                <div class="card mt-3">
                    <div class="card-header">
                        <h4 class="mb-0">Products</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Product Name</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total Price</th>
                                    <th>Remove</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orderItemsRes as $item): ?>
                                    <tr>
                                        <td><?= $item['productname']; ?></td>
                                        <td>Php <?= number_format($item['price'], 2); ?></td>
                                        <td>
                                            <div class="input-group qtyBox">
                                                <input type="hidden" value="<?= $item['product_id'];?>" class = "prodId" >
                                                <button class="input-group-text prod-decrement">-</button>
                                                <input type="text" value="<?= $item['orderItemQuantity'];?>" class="qty quantityInput" min="1" />
                                                <button class="input-group-text prod-increment">+</button>
                                            </div>
                                        </td>
                                        <td>Php <?= number_format($item['price'] * $item['orderItemQuantity'], 2); ?></td>
                                        <td>
                                            <a href="order-item-delete.php?item_id=<?= $item['id']; ?>" class="btn btn-danger">Remove</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <?php 
                            $customer_id = $item['customer_id'];
                            $checkCustomer = mysqli_query($conn, "SELECT * FROM customers WHERE id='$customer_id' LIMIT 1");
                            $customerData = mysqli_fetch_assoc($checkCustomer);
                            
                        ?>
                        <div class="mt-2">
                            <hr>
                            <div class="row">
                                <div class="col-md-4">
                                    <label>Select Payment Method</label>
                                    <select name="payment_mode" class="form-select">
                                        <option value="">-- Select Payment --</option>
                                        <option value="Cash Payment" <?= ($item['payment_mode'] == 'Cash Payment') ? 'selected' : ''; ?>>Cash Payment</option>
                                        <option value="Online Payment" <?= ($item['payment_mode'] == 'Online Payment') ? 'selected' : ''; ?>>Online Payment</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label>Enter Customer Name</label>
                                    <input type="text" name="customer_name" class="form-control" value="<?= $customerData['name'];?>" />
                                </div>
                                <div class="col-md-4">
                                    <br/>
                                    <button type="button" class="btn btn-warning w-100 proceedToPlace">Proceed to Update Order</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <h5>No items added to the order yet.</h5>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>