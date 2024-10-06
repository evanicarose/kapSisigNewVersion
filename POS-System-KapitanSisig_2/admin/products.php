<?php include('includes/header.php'); ?>

<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Menu Products
                <a href="products-create.php" class="btn btn-outline-primary float-end">Add Menu Product</a> 
            </h4>
        </div>
        <div class="card-body">
            <?php alertMessage(); ?>
            <?php 
                $products = getAll('products'); 
                if (!$products) {
                    echo '<h4>Something went wrong.</h4>';
                } elseif (mysqli_num_rows($products) > 0) { 
            ?>
            <div class="table-responsive">
                <table class="table" style="width: 100%; margin-bottom: 1rem; color: #000; border: 1px solid #dee2e6;">
                    <thead>
                        <tr style="background-color: #f8f9fa; color: #000;">
                            <!-- <th>ID</th> -->
                            <th>Image</th>
                            <th>Menu Product Name</th>
                            <th>Category</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $i = 0;
                        while ($productItem = mysqli_fetch_assoc($products)) : 
                        $i++;
                        ?>
                        <tr style="background-color: <?= $i % 2 == 0 ? '#fff' : '#f9f9f9'; ?>; border: 1px solid #dee2e6;">
                            <!-- <td><?= $productItem['id'] ?></td> -->
                            <td><img src="../<?= htmlspecialchars($productItem['image']); ?>" style="width:70px;height:70px;" alt="product image" /></td>
                            <td><?= htmlspecialchars($productItem['productname']) ?></td>
                            <?php
                            // Get category name
                            $categoryId = $productItem['category_id'];
                            $categoryQuery = "SELECT name FROM categories WHERE id = '$categoryId'";
                            $categoryResult = mysqli_query($conn, $categoryQuery);

                            if ($categoryResult && mysqli_num_rows($categoryResult) > 0) {
                                $category = mysqli_fetch_assoc($categoryResult);
                                $categoryName = htmlspecialchars($category['name']);
                            } else {
                                $categoryName = "Unknown Category";
                            }
                            ?>
                            <td><?= $categoryName ?></td>
                            <td><?= htmlspecialchars($productItem['quantity']) ?></td>
                            <td><?= htmlspecialchars($productItem['price']) ?></td>
                            <td>
                                <a href="products-edit.php?id=<?= $productItem['id'];?>" class="btn btn-outline-success btn-sm" style="margin: 0; padding: 0.25rem 0.5rem;">Edit</a>
                                <a 
                                    href="products-delete.php?id=<?= $productItem['id'];?>" 
                                    class="btn btn-outline-danger btn-sm" 
                                    style="margin: 0; padding: 0.25rem 0.5rem;"
                                    onclick="return confirm('Are you sure you want to delete product?')">
                                    Delete
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            <?php 
                } else {
                    echo '<h4 class="mb-0">No record found.</h4>';
                } 
            ?>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
