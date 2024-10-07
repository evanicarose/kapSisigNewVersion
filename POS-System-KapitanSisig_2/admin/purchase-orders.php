<?php include('includes/header.php'); ?>

<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Purchase Orders</h4>
        </div>
        <div class="card-body">

        <?php
            $query = "SELECT po.*, a.* FROM purchaseOrders po, admins a WHERE a.id = po.customer_id ORDER BY po.id DESC";
            $purchaseOrders = mysqli_query($conn, $query);

            if($purchaseOrders){
                if(mysqli_num_rows($purchaseOrders) > 0){
                    ?>
                    <table class="table table-striped table-bordered align-items-center justify-content-center">
                        <thead>
                            <tr>
                                <th>Tracking No.</th>
                                <th>Name</th>
                                <th>Order Date</th>
                                <th>Order Status</th>
                                <th>Payment Method</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($purchaseOrders as $ingredientItem): ?>
                                <tr>
                                    <td class="fw-bold"><?= $ingredientItem['tracking_no']; ?></td>
                                    <td><?= $ingredientItem['firstname']; ?></td>
                                    <td><?= $ingredientItem['order_date']; ?></td>
                                    <td><?= $ingredientItem['order_status']; ?></td>
                                    <td><?= $ingredientItem['ingPayment_mode']; ?></td>
                                    <td>
                                        <a href="purchase-orders-view.php?track=<?= $ingredientItem['tracking_no']; ?>" class="btn btn-info mb-0 px-2 btn-sm">View</a>
                                        <a href="purchase-orders-view-print.php?track=<?= $ingredientItem['tracking_no']; ?>" class="btn btn-primary mb-0 px-2 btn-sm">Print</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php
                } else {
                    echo "<h5>No Record Available</h5>";
                }
            } else {
                echo "<h5>Something went wrong</h5>";
            }
        ?>

        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>