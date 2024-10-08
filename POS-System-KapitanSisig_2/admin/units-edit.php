<?php include('includes/header.php'); ?>

<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Edit Unit
                <a href="units.php" class="btn btn-outline-danger float-end">Back</a> 
            </h4>
        </div>
        <div class="card-body">
            <?php alertMessage(); ?>
            <form action="code.php" method="POST">

            <?php
            $paramValue = checkParam('id');
            if (!is_numeric($paramValue)) {
                echo '<h5>' . htmlspecialchars($paramValue) . '</h5>';
                return false;
            }
            $unit = getById('units', $paramValue);
            if ($unit['status'] == 200) {
            ?>
            <input type="hidden" name="unitId" value="<?= htmlspecialchars($unit['data']['id']); ?>">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="">Name *</label>
                        <input type="text" name="name" value="<?= htmlspecialchars($unit['data']['name']); ?>" required class="form-control" />
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="">Status (Unchecked=Visible, Checked=Hidden)</label>
                        <br />
                        <input type="checkbox" style="width:30px;height:30px;" name="status" <?= $unit['data']['status'] ? 'checked' : ''; ?>>
                    </div>
                    <div class="col-md-6 mb-3 text-end">
                        <br />
                        <button type="submit" name="updateUnit" class="btn btn-outline-primary">Update</button>
                    </div>
                </div>
                <?php
                } else {
                    echo '<h5>' . htmlspecialchars($unit['message']) . '</h5>';
                }
                ?>
            </form>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
