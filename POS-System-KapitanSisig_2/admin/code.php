<?php

include('../config/function.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


// Save Admin Functionality
if (isset($_POST['saveAdmin'])) {
    $firstname = validate($_POST['firstname']);
    $lastname = validate($_POST['lastname']);
    $username = validate($_POST['username']);
    $password = validate($_POST['password']);

    $position = isset($_POST['position']) && $_POST['position'] == 1 ? 1 : 0;

    if ($firstname != '' && $lastname != '' && $username != '' && $password != '') {
        $usernameCheck = mysqli_query($conn, "SELECT * FROM admins WHERE username='$username'");
        if ($usernameCheck && mysqli_num_rows($usernameCheck) > 0) {
            $_SESSION['message'] = 'Username already used by another user.';
            header('Location: admins-create.php');
            exit();
        }

        $bcrypt_password = password_hash($password, PASSWORD_BCRYPT);

        $data = [
            'firstname' => $firstname,
            'lastname' => $lastname,
            'username' => $username,
            'password' => $bcrypt_password,
            'position' => $position
        ];

        $result = insert('admins', $data);
        if ($result) {
            $_SESSION['message'] = 'Admin created.';
            header('Location: admins.php');
            exit();
        } else {
            $_SESSION['message'] = 'Something went wrong.';
            header('Location: admins-create.php');
            exit();
        }
    } else {
        $_SESSION['message'] = 'Please fill required fields.';
        header('Location: admins-create.php');
        exit();
    }
}


// Update Admin Functionality
if (isset($_POST['updateAdmin'])) {
    $adminId = validate($_POST['adminId']);

    $adminData = getByID('admins', $adminId);

    if ($adminData['status'] != 200) {
        $_SESSION['message'] = 'Admin not found.';
        header('Location: admins-edit.php?id=' . $adminId);
        exit();
    }

    $firstname = validate($_POST['firstname']);
    $lastname = validate($_POST['lastname']);
    $username = validate($_POST['username']);
    $password = validate($_POST['password']);

    $position = isset($_POST['position']) && $_POST['position'] == 1 ? 1 : 0;

    $hashedPassword = $password != '' ? password_hash($password, PASSWORD_BCRYPT) : $adminData['data']['password'];

    if ($firstname != '' && $lastname != '' && $username != '') {
        $data = [
            'firstname' => $firstname,
            'lastname' => $lastname,
            'username' => $username,
            'password' => $hashedPassword,
            'position' => $position
        ];

        $result = update('admins', $adminId, $data);
        if ($result) {
            $_SESSION['message'] = 'Admin updated.';
            header('Location: admins-edit.php?id=' . $adminId);
            exit();
        } else {
            $_SESSION['message'] = 'Something went wrong.';
            header('Location: admins-edit.php?id=' . $adminId);
            exit();
        }
    } else {
        $_SESSION['message'] = 'Please fill required fields.';
        header('Location: admins-edit.php?id=' . $adminId);
        exit();
    }
}


// Save Supplier
if (isset($_POST['saveSupplier'])) {
    $firstname = validate($_POST['firstname']);
    $lastname = validate($_POST['lastname']);
    $phonenumber = validate($_POST['phonenumber']);
    $address = validate($_POST['address']);

    if ($firstname != '' && $lastname != '' && $phonenumber != '' && $address != '') {
        $data = [
            'firstname' => $firstname,
            'lastname' => $lastname,
            'phonenumber' => $phonenumber,
            'address' => $address
        ];

        $result = insert('suppliers', $data);
        if ($result) {
            $_SESSION['message'] = 'Supplier added successfully.';
            header('Location: suppliers.php');
            exit();
        } else {
            $_SESSION['message'] = 'Something went wrong.';
            header('Location: suppliers-create.php');
            exit();
        }
    } else {
        $_SESSION['message'] = 'Please fill required fields.';
        header('Location: suppliers-create.php');
        exit();
    }
}

// Update Supplier
if (isset($_POST['updateSupplier'])) {
    $supplierId = validate($_POST['supplierId']);

    $supplierData = getById('suppliers', $supplierId);

    if ($supplierData['status'] != 200) {
        $_SESSION['message'] = 'Invalid Supplier ID.';
        header('Location: suppliers-edit.php?id=' . $supplierId);
        exit();
    }

    $firstname = validate($_POST['firstname']);
    $lastname = validate($_POST['lastname']);
    $phonenumber = validate($_POST['phonenumber']);
    $address = validate($_POST['address']);

    if ($firstname != '' && $lastname != '' && $phonenumber != '' && $address != '') {
        $data = [
            'firstname' => $firstname,
            'lastname' => $lastname,
            'phonenumber' => $phonenumber,
            'address' => $address
        ];

        $result = update('suppliers', $supplierId, $data);
        if ($result) {
            $_SESSION['message'] = 'Supplier updated successfully.';
            header('Location: suppliers-edit.php?id=' . $supplierId);
            exit();
        } else {
            $_SESSION['message'] = 'Something went wrong.';
            header('Location: suppliers-edit.php?id=' . $supplierId);
            exit();
        }
    } else {
        $_SESSION['message'] = 'Please fill required fields.';
        header('Location: suppliers-edit.php?id=' . $supplierId);
        exit();
    }
}


// Save Ingredient
if (isset($_POST['saveIngredient'])) {
    $name = $_POST['name'];
    $unit_id = $_POST['unit_id'];
    $category = $_POST['category'];
    $sub_category = $_POST['sub_category'];
    $price = $_POST['price'];

    $query = "INSERT INTO ingredients (name, unit_id, category, sub_category, price) 
              VALUES ('$name', '$unit_id', '$category', '$sub_category', '$price ')";
    
    if (mysqli_query($conn, $query)) {
        $_SESSION['message'] = "Ingredient added successfully";
        header('Location: ingredients-view.php');
        exit(0);
    } else {
        $_SESSION['message'] = "Failed to add ingredient";
        header('Location: ingredients-add.php');
        exit(0);
    }
}

// Update Ingredient
if (isset($_POST['updateIngredient'])) {
    $ingredientId = $_POST['ingredientId'];
    $name = $_POST['name'];
    
    $unit_id = $_POST['unit_id'];
    $category = $_POST['category'];
    $sub_category = $_POST['sub_category'];
    $price = $_POST['price'];


    $query = "UPDATE ingredients SET name='$name',  unit_id='$unit_id', 
              category='$category', sub_category='$sub_category', price='$price' WHERE id='$ingredientId'";
    
    if (mysqli_query($conn, $query)) {
        $_SESSION['message'] = "Ingredient updated successfully";
        header('Location: ingredients-view.php');
        exit(0);
    } else {
        $_SESSION['message'] = "Failed to update ingredient";
        header('Location: ingredients-edit.php?id='.$ingredientId);
        exit(0);
    }
}


// Save Category
if (isset($_POST['saveCategory'])) {
    $name = validate($_POST['name']);
    // $description = validate($_POST['description']);
    $status = isset($_POST['status']) ? 1 : 0;

    if ($name != '' ) {
        $data = [
            'name' => $name,
            // 'description' => $description,
            'status' => $status
        ];
        $result = insert('categories', $data);
        if ($result) {
            $_SESSION['message'] = 'Category created successfully!';
            header('Location: categories.php');
            exit();
        } else {
            $_SESSION['message'] = 'Something went wrong.';
            header('Location: categories-create.php');
            exit();
        }
    } else {
        $_SESSION['message'] = 'Please fill required fields.';
        header('Location: categories-create.php');
        exit();
    }
}


// Update Category
if (isset($_POST['updateCategory'])) {
    $categoryId = validate($_POST['categoryId']);

    $categoryData = getById('categories', $categoryId);

    if ($categoryData['status'] != 200) {
        $_SESSION['message'] = 'Category not found.';
        header('Location: categories-edit.php?id=' . $categoryId);
        exit();
    }

    $name = validate($_POST['name']);
    // $description = validate($_POST['description']);
    $status = isset($_POST['status']) ? 1 : 0;

    if ($name != '') {
        $data = [
            'name' => $name,
            // 'description' => $description,
            'status' => $status
        ];
        $result = update('categories', $categoryId, $data);
        if ($result) {
            $_SESSION['message'] = 'Category updated successfully!';
            header('Location: categories.php');
            exit();
        } else {
            $_SESSION['message'] = 'Something went wrong.';
            header('Location: categories.php');
            exit();
        }
    } else {
        $_SESSION['message'] = 'Please fill required fields.';
        header('Location: categories-edit.php');
        exit();
    }
}


// Save Product
if (isset($_POST['saveProduct'])) {
    $category_id = validate($_POST['category_id']);
    $productname = validate($_POST['productname']);
    $description = validate($_POST['description']);
    $quantity = validate($_POST['quantity']);  // Corrected quantity validation
    $price = validate($_POST['price']);

    if ($_FILES['image']['size'] > 0) {
        $path = "../pics/uploads/products";
        $image_ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);

        $filename = time() . '.' . $image_ext;

        move_uploaded_file($_FILES['image']['tmp_name'], $path . "/" . $filename);
        $finalImage = "pics/uploads/products/" . $filename;
    } else {
        $finalImage = '';
    }

    $data = [
        'category_id' => $category_id,
        'productname' => $productname,
        'description' => $description,
        'quantity' => $quantity,  // Added quantity to the data array
        'price' => $price,
        'image' => $finalImage
    ];

    $result = insert('products', $data);
    if ($result) {
        redirect('products.php', 'Menu product created successfully!');
    } else {
        redirect('products-create.php', 'Something went wrong.');
    }
}


// Update Product
if (isset($_POST['updateProduct'])) {
    $product_id = validate($_POST['product_id']);

    $productData = getById('products', $product_id);
    if ($productData['status'] != 200) {
        $_SESSION['message'] = 'Product not found.';
        header('Location: products-edit.php?id=' . $product_id);
        exit();
    }

    $category_id = validate($_POST['category_id']);
    $productname = validate($_POST['productname']);
    $description = validate($_POST['description']);
    $quantity = validate($_POST['quantity']);  // Corrected quantity validation for update
    $price = validate($_POST['price']);

    if ($_FILES['image']['size'] > 0) {
        $path = "../pics/uploads/products";
        $image_ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array($image_ext, $allowed_ext)) {
            $_SESSION['message'] = 'Invalid image type.';
            header('Location: products-edit.php?id=' . $product_id);
            exit();
        }

        $filename = time() . '.' . $image_ext;
        $destination = $path . "/" . $filename;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
            $finalImage = "pics/uploads/products/" . $filename;

            $deleteImage = "../" . $productData['data']['image'];
            if (file_exists($deleteImage)) {
                unlink($deleteImage);
            }
        } else {
            $finalImage = $productData['data']['image'];
            $_SESSION['message'] = 'Failed to move the uploaded file.';
            header('Location: products-edit.php?id=' . $product_id);
            exit();
        }
    } else {
        $finalImage = $productData['data']['image'];
    }

    $data = [
        'category_id' => $category_id,
        'productname' => $productname,
        'description' => $description,
        'quantity' => $quantity,  // Added quantity to the update array
        'price' => $price,
        'image' => $finalImage
    ];

    $result = update('products', $product_id, $data);

    if ($result) {
        $_SESSION['message'] = 'Menu product updated successfully!';
        header('Location: products.php');
        exit();
    } else {
        $_SESSION['message'] = 'Something went wrong.';
        header('Location: products-edit.php?id=' . $product_id);
        exit();
    }
}



// Save Unit
if (isset($_POST['saveUnit'])) {
    $name = validate($_POST['name']);
    $status = isset($_POST['status']) ? 1 : 0;

    $data = [
        'name' => $name,
        'status' => $status
    ];

    $result = insert('units', $data);

    if ($result) {
        $_SESSION['message'] = 'Unit created successfully!';
        header('Location: units.php');
        exit();
    } else {
        $_SESSION['message'] = 'Something went wrong.';
        header('Location: units-create.php');
        exit();
    }
}


// Update Unit
if (isset($_POST['updateUnit'])) {
    $unitId = validate($_POST['unitId']);
    
    if (empty($unitId)) {
        $_SESSION['message'] = 'No ID provided.';
        header('Location: units-edit.php?id=' . $unitId);
        exit();
    }

    $unitData = getById('units', $unitId);

    if ($unitData['status'] != 200) {
        $_SESSION['message'] = 'Unit not found.';
        header('Location: units-edit.php?id=' . $unitId);
        exit();
    }

    $name = validate($_POST['name']);
    $status = isset($_POST['status']) ? 1 : 0;

    if (!empty($name)) {
        $data = [
            'name' => $name,
            'status' => $status
        ];
        $result = update('units', $unitId, $data);

        if ($result) {
            $_SESSION['message'] = 'Unit updated successfully!';
            header('Location: units.php');
            exit();
        } else {
            $_SESSION['message'] = 'Something went wrong.';
            header('Location: units.php');
            exit();
        }
    } else {
        $_SESSION['message'] = 'Please fill required fields.';
        header('Location: units-edit.php?id=' . $unitId);
        exit();
    }
}

// Save Customer
if (isset($_POST['saveCustomer'])) {
    $name = validate($_POST['name']);
    

    if ($name != '') {
        $data = [
            'name' => $name,
            
        ];
        $result = insert('customers', $data);
        if ($result) {
            $_SESSION['message'] = 'Customer created successfully!';
            header('Location: customers.php');
            exit();
        } else {
            $_SESSION['message'] = 'Something went wrong.';
            header('Location: customer-create.php');
            exit();
        }
    } else {
        $_SESSION['message'] = 'Please fill required fields.';
        header('Location: customer-create.php');
        exit();
    }
}


// Update Customer
if (isset($_POST['updateCustomer'])) {
    $customerId = validate($_POST['customerId']);

    $customerData = getById('customers', $customerId);

    if ($customerData['status'] != 200) {
        $_SESSION['message'] = 'Customer not found.';
        header('Location: customer-edit.php?id=' . $customerId);
        exit();
    }

    $name = validate($_POST['name']);

    if ($name != '') {
        $data = [
            'name' => $name
            
        ];
        $result = update('customers', $customerId, $data);
        if ($result) {
            $_SESSION['message'] = 'Customer updated successfully!';
            header('Location: customers.php');
            exit();
        } else {
            $_SESSION['message'] = 'Something went wrong.';
            header('Location: customers.php');
            exit();
        }
    } else {
        $_SESSION['message'] = 'Please fill required fields.';
        header('Location: customer-edit.php');
        exit();
    }
}


?>