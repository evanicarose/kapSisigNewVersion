<?php

include('../config/function.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
date_default_timezone_set('Asia/Manila');



if(!isset($_SESSION['ingredientItems'])) {
    $_SESSION['ingredientItems'] = [];
}

if(!isset($_SESSION['ingredientItemIds'])) {
    $_SESSION['ingredientItemIds'] = [];
}

if(isset($_POST['addIngredient'])){
    $ingredientId = validate($_POST['ingredient_id']);
    $quantity = validate($_POST['quantity']);

    // Updated query to join the units table
    $checkIngredient = mysqli_query($conn, "
        SELECT i.*, u.name AS unit_name 
        FROM ingredients i
        LEFT JOIN units u ON i.unit_id = u.id 
        WHERE i.id='$ingredientId' LIMIT 1
    ");

    if($checkIngredient){
        if(mysqli_num_rows($checkIngredient) > 0){
            $row = mysqli_fetch_assoc($checkIngredient); // Fetch the ingredient details

            $ingredientData = [
                'ingredient_id' => $row['id'],
                'name' => $row['name'],
                'unit_id' => $row['unit_id'],
                'unit_name' => $row['unit_name'], // Added UoM name
                'category' => $row['category'],
                'sub_category' => $row['sub_category'],
                'price' => $row['price'],
                'quantity' => $quantity,
            ];

            if(!in_array($row['id'], $_SESSION['ingredientItemIds'])){
                array_push($_SESSION['ingredientItemIds'], $row['id']);
                array_push($_SESSION['ingredientItems'], $ingredientData);
            } else {
                foreach($_SESSION['ingredientItems'] as $key => $ingSessionItem) {
                    if($ingSessionItem['ingredient_id'] == $row['id']){
                        $newQuantity = $ingSessionItem['quantity'] + $quantity;

                        $ingredientData = [
                            'ingredient_id' => $row['id'],
                            'name' => $row['name'],
                            'unit_id' => $row['unit_id'], // Store UoM ID
                            'unit_name' => $row['unit_name'], // Store UoM name
                            'category' => $row['category'],
                            'sub_category' => $row['sub_category'],
                            'price' => $row['price'],
                            'quantity' => $newQuantity,
                        ];

                        $_SESSION['ingredientItems'][$key] = $ingredientData;
                    }
                }
            }
            redirect('purchase-order-create.php', 'Ingredient added: ' .$quantity. ' ' .$row['name']);
        } else {
            redirect('purchase-order-create.php', 'No such ingredient found!');
        }
    } else {
        redirect('purchase-order-create.php', 'Something went wrong!');
    }
}

if (isset($_POST['ingredientIncDec'])) {
    $ingredientId = validate($_POST['ingredient_id']);
    $quantity = validate($_POST['quantity']);

    // Fetch product details from the database
    $checkIngredient = mysqli_query($conn, "SELECT * FROM ingredients WHERE id='$ingredientId' LIMIT 1");

    // Initialize a flag
    $flag = false;

    // Check if product exists
    if (mysqli_num_rows($checkIngredient) > 0) {
        $row = mysqli_fetch_assoc($checkIngredient);

        // Loop through session items and update the quantity
        foreach ($_SESSION['ingredientItems'] as $key => $item) {
            if ($item['ingredient_id'] == $ingredientId) {
                // Update the quantity
                $_SESSION['ingredientItems'][$key]['quantity'] = $quantity;
                $flag = true; // Quantity changed
                break; // Exit the loop once the item is found and updated
            }
        }
    }

    // Prepare JSON response based on the flag
    if ($flag) {
        jsonResponse(200, 'success', "Quantity changed.");
    } else {
        jsonResponse(500, 'error', "Something went wrong!");
    }
}



if (isset($_POST['proceedToPlaceIng'])) {
    $adminName = validate($_POST['adminName']);
    $ingPayment_mode = validate($_POST['ingPayment_mode']);
    $supplierName = validate($_POST['supplierName']);

    // Ensure correct usage of variable names
    $checkAdmin = mysqli_query($conn, "SELECT * FROM admins WHERE firstname='$adminName' LIMIT 1");

    if ($checkAdmin) {
        if (mysqli_num_rows($checkAdmin) > 0) {
            $_SESSION['invoice_no'] = "INV-" . rand(111111, 999999);
            $_SESSION['adminName'] = $adminName;
            $_SESSION['ingPayment_mode'] = $ingPayment_mode;
            $_SESSION['supplierName'] = $supplierName;

            jsonResponse(200, 'success', 'Admin found');
        } else {
            $_SESSION['adminName'] = $adminName;
            jsonResponse(404, 'warning', 'Admin not found');
        }
    } else {
        jsonResponse(500, 'error', 'Something Went Wrong');
    }
}

if (isset($_POST['savePurchaseOrder'])) {
    $adminName = validate($_SESSION['adminName']);
    $invoice_no = validate($_SESSION['invoice_no']);
    $ingPayment_mode = validate($_SESSION['ingPayment_mode']);
    $supplierName = validate($_SESSION['supplierName']);
    $order_placed_by_id = "Admin";

    // Check if customer exists
    $checkAdmin = mysqli_query($conn, "SELECT * FROM admins WHERE firstname='$adminName' LIMIT 1");
    $checkSupplier = mysqli_query($conn, "SELECT * FROM suppliers WHERE id = '$supplierName' LIMIT 1");

    if (!$checkAdmin) {
        jsonResponse(500, 'error', 'Something Went Wrong');
    }

    if (!$checkSupplier) {
        jsonResponse(500, 'error', 'Something Went Wrong');
    }

    if (mysqli_num_rows($checkAdmin) > 0) {
        if(mysqli_num_rows($checkSupplier) > 0){
            $adminData = mysqli_fetch_assoc($checkAdmin);
            $supplierData = mysqli_fetch_assoc($checkSupplier);

            
            if (!isset($_SESSION['ingredientItems'])) {
                jsonResponse(404, 'warning', 'No items to place order');
                exit;
            }

            $totalAmount = 0;
            $sessionIngredients = $_SESSION['ingredientItems'];  // Make sure this exists
            foreach ($sessionIngredients as $ingredientItems) {
                $totalAmount += $ingredientItems['price'] * $ingredientItems['quantity'];  // Fix typo
            }

            $data = [
                'customer_id' => $adminData['id'],
                'tracking_no' => rand(111111, 999999),
                'invoice_no' => $invoice_no,
                'total_amount' => $totalAmount,
                'order_date' => date('Y-m-d H:i:s'),
                'order_status' => 'Booked',
                'ingPayment_mode' => $ingPayment_mode,
                'order_placed_by_id' => $order_placed_by_id,
                'supplierName' => $supplierName
            ];

            $result = insert('purchaseOrders', $data);
            $lastOrderId = mysqli_insert_id($conn);

            foreach ($sessionIngredients as $ingItem) {
                $ingredientId = $ingItem['ingredient_id'];
                $price = $ingItem['price'];
                $quantity = $ingItem['quantity'];

                $dataIngredientItem = [
                    'order_id' => $lastOrderId,
                    'ingredient_id' => $ingredientId,
                    'price' => $price,
                    'quantity' => $quantity,
                ];

                $orderItemQuery = insert('ingredients_items', $dataIngredientItem);

                // Update product quantities
                $checkIngredientQuantityQuery = mysqli_query($conn, "SELECT * FROM ingredients WHERE id='$ingredientId'");
                $ingredientQtyData = mysqli_fetch_assoc($checkIngredientQuantityQuery);
                $totalIngredientQuantity = $ingredientQtyData['quantity'] + $quantity;

                $dataUpdate = [
                    'quantity' => $totalIngredientQuantity
                ];

                $updateIngredientQty = update('ingredients', $ingredientId, $dataUpdate);
            }

            unset($_SESSION['ingredientItemIds'], $_SESSION['ingredientItems'], $_SESSION['ingPayment_mode'], $_SESSION['invoice_no'], $_SESSION['supplierName']);
            jsonResponse(200, 'success', 'Order placed successfully!');
        }
    } else {
        jsonResponse(404, 'warning', 'No Admin found');
    }
}



?>
