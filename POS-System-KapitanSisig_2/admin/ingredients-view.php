<?php include('includes/header.php'); ?>

<?php
// Updated SQL query to join the ingredients and units tables
$query = "SELECT ingredients.*, units.name AS unit_name FROM ingredients 
          LEFT JOIN units ON ingredients.unit_id = units.id";
$result = mysqli_query($conn, $query);
?>

<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Ingredients
                <a href="ingredients-add.php" class="btn btn-outline-primary float-end">Add Ingredient</a> 
            </h4>
        </div>
        <div class="card-body">
        <?php alertMessage(); ?>
            <table class="table" style="width: 100%; margin-bottom: 1rem; color: #000; border: 1px solid #dee2e6;">
                <thead>
                    <tr style="background-color: #f8f9fa; color: #000;">
                        <!-- <th>ID</th> -->
                        <th>Name</th>
                      
                        <th>Unit</th>
                        <th>Category</th>
                        <th>Sub Category</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $i = 0;
                    while ($row = mysqli_fetch_assoc($result)) : 
                    $i++;
                    ?>
                        <tr style="background-color: <?= $i % 2 == 0 ? '#fff' : '#f9f9f9'; ?>; border: 1px solid #dee2e6;">
                            <!-- <td><?php echo htmlspecialchars($row['id']); ?></td> -->
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                         
                            <td><?php echo htmlspecialchars($row['unit_name']); ?></td> <!-- Display unit name -->
                            <td><?php echo htmlspecialchars($row['category']); ?></td>
                            <td><?php echo htmlspecialchars($row['sub_category']); ?></td>
                            <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                            <td><?php echo htmlspecialchars($row['price']); ?></td>
                            <td>
                                <a href="ingredients-edit.php?id=<?php echo $row['id']; ?>" class="btn btn-outline-success btn-sm" style="margin: 0; padding: 0.25rem 0.5rem;">Edit</a>
                                <a href="ingredients-delete.php?id=<?php echo $row['id']; ?>" class="btn btn-outline-danger btn-sm" style="margin: 0; padding: 0.25rem 0.5rem;">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
