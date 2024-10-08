<?php 
require '../config/function.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ensure a valid 'id' parameter is present
$paramResultId = checkParam('id');
if (is_numeric($paramResultId)) {
    $ingredientId = validate($paramResultId);

    // Get the ingredient details
    $ingredient = getById('ingredients', $ingredientId);

    if ($ingredient['status'] == 200) {
        // Correct table name 'ingredients'
        $ingredientDeleteRes = delete('ingredients', $ingredientId);

        if ($ingredientDeleteRes) {
            $_SESSION['message'] = 'Ingredient deleted successfully!';
            header('Location: ingredients-view.php');
            exit();
        } else {
            $_SESSION['message'] = 'Something went wrong.';
            header('Location: ingredients-view.php');
            exit();
        }
    } else {
        $_SESSION['message'] = $ingredient['message'];
        header('Location: ingredients-view.php');
        exit();
    }
} else {
    $_SESSION['message'] = 'Invalid ID provided.';
    header('Location: ingredients-view.php');
    exit();
}
?>
