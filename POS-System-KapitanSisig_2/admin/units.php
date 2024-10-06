<?php include('includes/header.php'); ?>

<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Units
                <a href="units-create.php" class="btn btn-outline-primary float-end">Add Units</a>
            </h4>
        </div>
        <div class="card-body">
            <?php alertMessage(); ?>
            <?php 
                $units = getAll('units'); 
                if (!$units) {
                    echo '<h4>Something went wrong.</h4>';
                    return false;
                }
                if (mysqli_num_rows($units) > 0) {
            ?>
            <div class="table-responsive">
                <table class="table" style="width: 100%; margin-bottom: 1rem; color: #000; border: 1px solid #dee2e6;">
                    <thead>
                        <tr style="background-color: #f8f9fa; color: #000;">
                            <!-- <th>ID</th> -->
                            <th>Name</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $i = 0;
                        foreach ($units as $item) : 
                            $i++;
                        ?>
                        <tr style="background-color: <?= $i % 2 == 0 ? '#fff' : '#f9f9f9'; ?>; border: 1px solid #dee2e6;">
                            <!-- <td><?= $item['id'] ?></td> -->
                            <td><?= htmlspecialchars($item['name']) ?></td>
                            <td>
                                <?php
                                    if ($item['status'] == 1) {
                                        echo '<span class="badge bg-danger">Hidden</span>';
                                    } else {
                                        echo '<span class="badge bg-primary">Visible</span>';
                                    }
                                ?>
                            </td>
                            <td>
                                <a href="units-edit.php?id=<?= $item['id']; ?>" class="btn btn-outline-success btn-sm" style="margin: 0; padding: 0.25rem 0.5rem;">Edit</a>
                                <a href="units-delete.php?id=<?= $item['id']; ?>" class="btn btn-outline-danger btn-sm" style="margin: 0; padding: 0.25rem 0.5rem;">Delete</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
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
