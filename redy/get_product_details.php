<?php
session_start();

// Database connection
$conn = new mysqli("localhost", "root", "", "sports_shop");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if ID is set
if (isset($_GET['id'])) {
    $productId = intval($_GET['id']);
    $sql = "SELECT name, details, price FROM products WHERE id = $productId";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Fetch the product details
        $product = $result->fetch_assoc();
        echo json_encode($product);
    } else {
        echo json_encode(['error' => 'Product not found']);
    }
} else {
    echo json_encode(['error' => 'No product ID provided']);
}

$conn->close();
?>
