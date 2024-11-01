<?php
session_start();

// Database connection
$conn = new mysqli("localhost", "root", "", "sports_shop");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle adding product
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $name = $_POST['name'];
    $details = $_POST['details'];
    $price = $_POST['price'];

    // Check if the image is uploaded
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] === UPLOAD_ERR_OK) {
        // Handle file upload
        $targetDir = "uploads/"; // Directory where the image will be uploaded
        if (!is_dir($targetDir)) {
            // Create the directory if it doesn't exist
            mkdir($targetDir, 0755, true);
        }

        $image = $targetDir . basename($_FILES["image"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($image, PATHINFO_EXTENSION));

        // Check if image file is an actual image
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check === false) {
            echo "File is not an image.";
            $uploadOk = 0;
        }

        // Check file size (optional, e.g., limit to 5MB)
        if ($_FILES["image"]["size"] > 5000000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if (!in_array($imageFileType, ["jpg", "png", "jpeg", "gif"])) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk === 0) {
            echo "Sorry, your file was not uploaded.";
        } else {
            // If everything is ok, try to upload file
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $image)) {
                $sql = "INSERT INTO products (name, details, price, image) VALUES ('$name', '$details', $price, '$image')";
                if ($conn->query($sql) === TRUE) {
                    echo "Product added successfully!";
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    } else {
        echo "No file was uploaded or there was an upload error.";
    }
}

// Handle removing product
if (isset($_POST['action']) && $_POST['action'] === 'remove') {
    $productId = $_POST['product_id'];

    // Delete the product from the database
    $sql = "DELETE FROM products WHERE id = $productId";
    if ($conn->query($sql) === TRUE) {
        echo "Product removed successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
}

// Handle modifying product
if (isset($_POST['action']) && $_POST['action'] === 'modify') {
    $productId = $_POST['product_id'];
    $name = $_POST['name'];
    $details = $_POST['details'];
    $price = $_POST['price'];

    // Prepare the SQL statement
    $sql = "UPDATE products SET name='$name', details='$details', price=$price";

    // Check if the image is uploaded
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] === UPLOAD_ERR_OK) {
        // Handle file upload
        $targetDir = "uploads/"; // Directory where the image will be uploaded
        if (!is_dir($targetDir)) {
            // Create the directory if it doesn't exist
            mkdir($targetDir, 0755, true);
        }

        $image = $targetDir . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $image);
        $sql .= ", image='$image'"; // Append the image update to the SQL statement
    }

    $sql .= " WHERE id = $productId"; // Complete the SQL statement
    if ($conn->query($sql) === TRUE) {
        echo "Product modified successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
}

// Fetch products for modification and deletion
$products = $conn->query("SELECT * FROM products");

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #343a40; /* Dark background */
            color: #f8f9fa; /* Light text */
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #f8f9fa; /* Light text */
        }
        form {
            max-width: 400px;
            margin: 0 auto;
            background-color: #495057; /* Darker form background */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }
        input[type="text"], input[type="number"], input[type="file"], select {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ced4da; /* Lighter border */
            border-radius: 4px;
            background-color: #212529; /* Darker input background */
            color: #f8f9fa; /* Light text */
        }
        button {
            padding: 10px 15px;
            background-color: #007bff; /* Blue button */
            border: none;
            color: white;
            cursor: pointer;
            border-radius: 5px;
            width: 100%;
        }
        button:hover {
            background-color: #0056b3; /* Darker blue on hover */
        }
        .product-list {
            margin: 20px auto;
            max-width: 400px;
            background-color: #495057; /* Darker form background */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }
    </style>
</head>
<body>
    <h1>Admin Panel</h1>

    <h2>Add Product</h2>
    <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="action" value="add">
        <label for="name">Product Name:</label>
        <input type="text" id="name" name="name" required>

        <label for="details">Product Details:</label>
        <input type="text" id="details" name="details" required>

        <label for="price">Price:</label>
        <input type="number" id="price" name="price" step="0.01" required>

        <label for="image">Choose Image:</label>
        <input type="file" id="image" name="image" accept="image/*" required>

        <button type="submit">Add Product</button>
    </form>

    <h2>Remove Product</h2>
    <form method="post">
        <input type="hidden" name="action" value="remove">
        <label for="product_id_remove">Select Product to Remove:</label>
        <select name="product_id" id="product_id_remove" required>
            <option value="">Select a product</option>
            <?php if ($products): while ($row = $products->fetch_assoc()): ?>
                <option value="<?= $row['id']; ?>"><?= htmlspecialchars($row['name']); ?></option>
            <?php endwhile; endif; ?>
        </select>
        <button type="submit">Remove Product</button>
    </form>

    <h2>Modify Product</h2>
    <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="action" value="modify">
        <label for="product_id_modify">Select Product to Modify:</label>
        <select name="product_id" id="product_id_modify" required onchange="loadProductDetails(this.value)">
            <option value="">Select a product</option>
            <?php 
            // Reset the pointer to fetch products again for the modify dropdown
            $products->data_seek(0);
            if ($products): while ($row = $products->fetch_assoc()): ?>
                <option value="<?= $row['id']; ?>"><?= htmlspecialchars($row['name']); ?></option>
            <?php endwhile; endif; ?>
        </select>

        <label for="name_modify">Product Name:</label>
        <input type="text" id="name_modify" name="name" required>

        <label for="details_modify">Product Details:</label>
        <input type="text" id="details_modify" name="details" required>

        <label for="price_modify">Price:</label>
        <input type="number" id="price_modify" name="price" step="0.01" required>

        <label for="image_modify">Choose New Image (optional):</label>
        <input type="file" id="image_modify" name="image" accept="image/*">

        <button type="submit">Modify Product</button>
    </form>

    <div class="product-list">
        <h2>Current Products</h2>
        <ul>
            <?php 
            // Reset the pointer to fetch products again for the display
            $products->data_seek(0);
            if ($products): while ($row = $products->fetch_assoc()): ?>
                <li><?= htmlspecialchars($row['name']) . " - " . htmlspecialchars($row['price']); ?> 
                <img src="<?= htmlspecialchars($row['image']); ?>" alt="<?= htmlspecialchars($row['name']); ?>" style="width:50px; height:50px;"></li>
            <?php endwhile; endif; ?>
        </ul>
    </div>

    <script>
        function loadProductDetails(productId) {
            // Use AJAX to fetch product details
            if (productId) {
                fetch(`get_product_details.php?id=${productId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) {
                            console.error(data.error);
                            alert(data.error);
                        } else {
                            document.getElementById('name_modify').value = data.name;
                            document.getElementById('details_modify').value = data.details;
                            document.getElementById('price_modify').value = data.price;
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching product details:', error);
                        alert('Error fetching product details.');
                    });
            } else {
                document.getElementById('name_modify').value = '';
                document.getElementById('details_modify').value = '';
                document.getElementById('price_modify').value = '';
            }
        }
    </script>
</body>
</html>
