<?php include('includes/header.php'); ?>

<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Order View
                <a href="orders.php" class="btn-danger mx-2 btn-sm float-end" style="text-decoration: none;">Back</a>
                <a href="orders-view-print.php?track=<?= $_GET['track'] ?>" class="btn btn-info mx-2 btn-sm float-end">Print</a>
            </h4>
        </div>
        <div class="card-body">

        <?php alertMessage();?>

        <?php

        if(isset($_GET['track'])){

            if($_GET['track']== ''){
                ?>
                <div class="text-center py-5">
                    <h5>No tracking number found!</h5>
                    <div>
                    <a href="orders.php" class="btn btn-primary mt-4 w-25">Return to Orders View</a>
                    </div>
                    
                    </div>
                <?php

                return false;
            }
            $trackingNo = validate($_GET['track']);

            $query = "SELECT o.*, c.* FROM orders o, customers c WHERE 
                        c.id = o.customer_id AND tracking_no='$trackingNo' 
                        ORDER BY o.id DESC";

            $orders = mysqli_query($conn, $query);

            if($orders){

                if(mysqli_num_rows($orders) > 0){
                    $orderData = mysqli_fetch_assoc($orders);
                    $orderId = $orderData['id'];

                    ?>
                    <div class="card card-body shadow border-1 mb-4 ">
                        <div class="row">
                            <div class="col-md-6">
                                <h4>Order Details</h4>
                                <label class="mb-1">
                                    Tracking No: <span class="fw-bold"><?= htmlspecialchars($orderData['tracking_no'], ENT_QUOTES, 'UTF-8'); ?></span>
                                </label>
                                <br/>
                                <label class="mb-1">
                                    Order Date: <span class="fw-bold"><?= htmlspecialchars($orderData['order_date'], ENT_QUOTES, 'UTF-8'); ?></span>
                                </label>
                                <br/>
                                <label class="mb-1">
                                    Order Status: <span class="fw-bold"><?= htmlspecialchars($orderData['order_status'], ENT_QUOTES, 'UTF-8'); ?></span>
                                </label>
                                <br/>
                                <label class="mb-1">
                                    Payment Method: <span class="fw-bold"><?= htmlspecialchars($orderData['payment_mode'], ENT_QUOTES, 'UTF-8'); ?></span>
                                </label>
                                <br/>
                            </div>

                            <div class="col-md-6">
                                <h4>Customer Details</h4>

                                <label class="mb-1">
                                    Name: <span class="fw-bold"><?= htmlspecialchars($orderData['name'], ENT_QUOTES, 'UTF-8'); ?></span>
                                </label>
                                <br/>
                                <br/>
                            </div>
                        </div>
                    </div>


                    <?php
                        $orderItemQuery = "SELECT oi.quantity as orderItemQuantity, oi.price as orderItemPrice, o.*, oi.*, p.* 
                                FROM orders as o, order_items as oi, products as p 
                                WHERE  oi.order_id = o.id AND p.id = oi.product_id AND o.tracking_no='$trackingNo' ";

                        $orderItemsRes = mysqli_query($conn, $orderItemQuery);

                        if($orderItemsRes){
                            if(mysqli_num_rows($orderItemsRes) > 0){
                                $totalAmount = 0;
                                ?>
                                    <h4 class="my-3">Order Items Details</h4>
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th>Price</th>
                                                <th>Quantity</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while($orderItemRow = mysqli_fetch_assoc($orderItemsRes)): ?>
                                                <tr>
                                                    <td>
                                                    <img src="<?= $orderItemRow['image'] != '' ? '../'.$orderItemRow['image'] : '../pics/uploads/no-img.jpg'; ?>"

                                                        style="width:50px;height:50px;"
                                                        alt="Img"/>

                                                        <?= $orderItemRow['productname'];?>
                                                    </td>
                                                    
                                                    <td width="15%" class="fw-bold text-center">
                                                        Php <?= number_format($orderItemRow['orderItemPrice'], 2); ?>
                                                    </td>
                                                    <td width="15%" class="fw-bold text-center">
                                                        <?= $orderItemRow['orderItemQuantity']; ?>
                                                    </td>
                                                    <td width="15%" class="fw-bold text-center">
                                                        Php <?= number_format($orderItemRow['orderItemPrice'] * $orderItemRow['orderItemQuantity'], 2); ?>
                                                    </td> 
                                                </tr>
                                                <?php $totalAmount += $orderItemRow['orderItemPrice'] * $orderItemRow['orderItemQuantity']; ?>
                                            <?php endwhile; ?>

                                            <tr>
                                                <td class="text-end fw-bold">Total Price: </td>
                                                <td colspan="3" class="text-end fw-bold">Php <?= number_format($totalAmount, 2); ?></td>
                                            </tr>
                                        </tbody>

                                    </table>
                                <?php
                            }else{
                                echo ' <h5>No record Found!</h5>';
                                return false;
                            }
                        }else{
                            echo ' <h5>No record Found!</h5>';
                            return false;
                        }
                    ?>
                    <?php
                }else{
                    echo ' <h5>No record Found!</h5>';
                    return false;
                }

            }else{
                echo ' <h5>Something Went Wrong!</h5>';
            }
        }else{
            ?>
                <div class="text-center py-5">
                    <h5>No tracking number found!</h5>
                    <div>
                    <a href="orders.php" class="btn btn-primary mt-4 w-25">Return to Orders View</a>
                    </div>
                    
                </div>
            <?php
        }
        ?>

       

        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>