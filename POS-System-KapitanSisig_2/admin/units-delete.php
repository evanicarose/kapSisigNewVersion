<?php
require '../config/function.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$paramResultId = checkParam('id');

if (is_numeric($paramResultId)) {
    $unitId = validate($paramResultId);
    $unit = getById('units', $unitId); 
    
    if ($unit['status'] == 200) {
        $unitDeleteRes = delete('units', $unitId);
        if ($unitDeleteRes) {
            $_SESSION['message'] = 'Unit deleted successfully!';
            header('Location: units.php');
            exit();
        } else {
            $_SESSION['message'] = 'Something went wrong.';
            header('Location: units.php');
            exit();
        }
    } else {
        $_SESSION['message'] = $unit['message'];
        header('Location: units.php');
        exit();
    }
} else {
    $_SESSION['message'] = 'Invalid ID.';
    header('Location: units.php');
    exit();
}
?>
