<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main</title>
    <link rel="stylesheet" href="style1.css">
</head>
<body>

    <!-- Video Section -->
    <div class="video-container">
        <video controls autoplay muted loop width="100%">
            <source src="" type="video/mp4">
            Your browser does not support the video tag.
        </video>
    </div>

    <?php include 'nav.html'; ?>

    <section id="products" class="products-section">
        <div class="product-grid">
            <div class="product-card">
                <img src="images/product/pro1.webp" alt="Football Shoes">
                <h3>Muscleblaze protein</h3>
                <p>best seller in whey protein</p>
            </div>

            <div class="product-card">
                <img src="images/product/pro2.webp" alt="Running Shoes">
                <h3>Muscleblaze biozyme protein</h3>
                <p>Good for digestion</p>
            </div>

            <div class="product-card">
                <img src="images/product/pro3.jpg" alt="Yoga Mat">
                <h3>Fuelone Whey protein</h3>
                <p>best protein</p>
            </div>

            <div class="product-card">
                <img src="images/product/pro4.avif" alt="Basketball">
                <h3>ON protein</h3>
                <p>Good quality protein </p>
            </div>
            <!-- Additional product cards as needed -->
        </div>
    </section>

    <section class="about-section">
        <div class="about-container">
            <h2>About AP Sport</h2>
            <p>AP Sport is your one-stop shop for all your sports gear and apparel needs. We provide premium-quality products to ensure you are well-equipped for your next game or workout.</p>
            <p>Whether you're into football, basketball, running, or yoga, we have the right equipment for you.</p>
        </div>
    </section>

    <footer>
        <div class="footer-content">
            <div class="footer-about">
                <h3>AP Sport</h3>
                <p>Your go-to sports store for high quality.</p>
            </div>
        </div>
        <div class="footer-social">
            <h4>Follow Us</h4>
            <!-- Social media links can be added here -->
        </div>
    </footer>

</body>
</html>
