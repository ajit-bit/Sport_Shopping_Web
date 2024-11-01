<?php
session_start();

// Database connection
$conn = new mysqli("localhost", "root", "", "sports_shop");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle add to cart action
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['product_id'])) {
        $product_id = (int)$_POST['product_id'];

        // Initialize cart if it doesn't exist
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        if (!isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id] = 1;
        } else {
            $_SESSION['cart'][$product_id]++;
        }

        header("Location: product.php?added=true");
        exit();
    }

    // Handle remove from cart action
    if (isset($_POST['remove_product_id'])) {
        $remove_product_id = (int)$_POST['remove_product_id'];
        
        if (isset($_SESSION['cart'][$remove_product_id])) {
            unset($_SESSION['cart'][$remove_product_id]);
        }
    }
}

// Initialize cart
$cart = $_SESSION['cart'] ?? [];

// Retrieve product details for cart items
$totalPrice = 0;
$productDetails = [];
foreach ($cart as $productId => $quantity) {
    $result = $conn->query("SELECT * FROM products WHERE id = $productId");

    if ($result && $result->num_rows > 0) {
        $product = $result->fetch_assoc();
        $productDetails[$productId] = $product;
        $totalPrice += $product['price'] * $quantity;
    } else {
        // Remove the product from the cart if not found
        unset($cart[$productId]);
    }
}

// Update the session cart
$_SESSION['cart'] = $cart;

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
            text-align: center;
        }
        h1 {
            color: #333;
        }
        .cart-grid {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
        }
        .cart-item {
            background-color: #fff;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 300px;
        }
        .cart-item img {
            width: 50px;
            height: 50px;
            border-radius: 5px;
        }
        .checkout-button {
            background-color: #28a745;
            border: none;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
        }
        .checkout-button:hover {
            background-color: #218838;
        }
        .remove-button {
            background-color: #dc3545;
            border: none;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }
        .remove-button:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <h1>Your Shopping Cart</h1>

    <div class="cart-grid">
        <?php if ($productDetails): ?>
            <?php foreach ($productDetails as $id => $product): ?>
                <div class="cart-item">
                    <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                    <div>
                        <h2><?php echo htmlspecialchars($product['name']); ?></h2>
                        <p>Quantity: <?php echo $cart[$id]; ?></p>
                        <p>Price: $<?php echo number_format($product['price'], 2); ?></p>
                        <p>Total: $<?php echo number_format($product['price'] * $cart[$id], 2); ?></p>
                    </div>
                    <form method="post" action="">
                        <input type="hidden" name="remove_product_id" value="<?php echo $id; ?>">
                        <button type="submit" class="remove-button">Remove</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Your cart is empty.</p>
        <?php endif; ?>
    </div>

    <?php if ($totalPrice > 0): ?>
        <p>Total Price: $<?php echo number_format($totalPrice, 2); ?></p>
        <button class="checkout-button">Checkout</button>
    <?php endif; ?>
</body>
</html>
