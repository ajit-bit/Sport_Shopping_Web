<?php
session_start();

// Database connection
$conn = new mysqli("localhost", "root", "", "sports_shop");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch products from the database
$sql = "SELECT * FROM products";
$result = $conn->query($sql);

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Check if the user has added an item to the cart
$addedMessage = isset($_GET['added']) ? 'Your product has been added to the cart successfully!' : '';

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Products</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        h1 {
            color: #333;
            text-align: center;
        }
        .product-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }
        .product-card {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 200px;
            text-align: center;
        }
        .product-card img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
        }
        .add-to-cart-button {
            margin-top: 10px;
            padding: 8px 12px;
            border: none;
            background-color: #28a745;
            color: white;
            cursor: pointer;
            border-radius: 5px;
        }
        .add-to-cart-button:hover {
            background-color: #218838;
        }
        /* Modal styles */
        .modal {
            display: none; 
            position: fixed; 
            z-index: 1; 
            left: 0;
            top: 0;
            width: 100%; 
            height: 100%; 
            overflow: auto; 
            background-color: rgb(0,0,0); 
            background-color: rgba(0,0,0,0.4); 
            padding-top: 60px;
        }
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto; 
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 600px;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
    <script>
        function showProductDetails(productId) {
            const xhr = new XMLHttpRequest();
            xhr.open("GET", "get_product_details.php?id=" + productId, true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    const product = JSON.parse(xhr.responseText);
                    document.getElementById("modal-title").innerText = product.name;
                    document.getElementById("modal-image").src = product.image;
                    document.getElementById("modal-details").innerText = product.details;
                    document.getElementById("modal-price").innerText = "Price: $" + product.price.toFixed(2);
                    document.getElementById("product-modal").style.display = "block";
                }
            };
            xhr.send();
        }

        function closeModal() {
            document.getElementById("product-modal").style.display = "none";
        }
    </script>
</head>
<body>
    <h1>Available Products</h1>
    
    <?php if ($addedMessage): ?>
        <p><?php echo $addedMessage; ?></p>
    <?php endif; ?>

    <div class="product-grid">
        <?php while ($product = $result->fetch_assoc()): ?>
            <div class="product-card">
                <img src="<?php echo $product['image']; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                <h2><?php echo htmlspecialchars($product['name']); ?></h2>
                <p><?php echo htmlspecialchars($product['details']); ?></p>
                <p>Price: $<?php echo number_format($product['price'], 2); ?></p>
                <form method="post" action="cart.php">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    <button type="submit" class="add-to-cart-button">Add to Cart</button>
                </form>
                <button onclick="showProductDetails(<?php echo $product['id']; ?>)">View Details</button>
            </div>
        <?php endwhile; ?>
    </div>

    <!-- Modal -->
    <div id="product-modal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2 id="modal-title"></h2>
            <img id="modal-image" src="" alt="" style="max-width: 100%; height: auto; border-radius: 5px;">
            <p id="modal-details"></p>
            <p id="modal-price"></p>
        </div>
    </div>
</body>
</html>
