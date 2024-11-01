<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>GET SHAPE</title>
  <link rel="stylesheet" href="styles.css">
  <script defer src="script.js"></script>
  
</head>
<body>
<?php include 'nav.html'; ?>
  <div class="slideshow-container">

    <div class="mySlides fade">
        <img src="images\slide1.png" alt="Slide 1">
    </div>

    <div class="mySlides fade">
        <img src="images\slide2.webp" alt="Slide 2">
    </div>

    <div class="mySlides fade">
        <img src="images\sldie3.png" alt="Slide 3">
    </div>
  </div>
  <div class="container">
    <div class="left-column">
        <img src=".jpg" alt="" style="width:px; height:px;">
    </div>
    <div class="right-column">
      <h1>Welcome</h1>
      <h3>(Yea buddy light weights)</h3>
      <p>Our mission is to provide you with the tools and resources to build your body for whatever health and wellness goal you set your sight on. From programs and articles to supplements and gear, every part of your routine starts here.</p>
    </div>
    </div>
    <div class="Feature">Feature content</div>
    <section id="products" class="products-section">
      <div class="product-grid">
          <div class="product-card">
              <img src="https://via.placeholder.com/300x300" alt="Football Shoes">
              <button class="btn">Strength & Muscle</button>
              </div>
      <div class="product-card">
          <img src="https://via.placeholder.com/300x300" alt="Running Shoes">
          <button class="btn">Sport performance</button>
        
      </div>
       <div class="product-card">
          <img src="https://via.placeholder.com/300x300" alt="Yoga Mat">
          <button class="btn">Weight Loss</button>          
      </div>
      <div class="product-card">
          <img src="https://via.placeholder.com/300x300" alt="Basketball">
          <button class="btn">Recovery</button> 
  </section>
     </div>
     <script>
      let slideIndex = 0;
showSlides();

function showSlides() {
    let i;
    let slides = document.getElementsByClassName("mySlides");

    for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";  
    }

    slideIndex++;

    if (slideIndex > slides.length) {slideIndex = 1}    

    slides[slideIndex - 1].style.display = "block";  

    setTimeout(showSlides, 3000);
}
     </script>
<footer>
  <div class="footer-content">
    <p>&copy; All rights reserved.</p>
  </div>
</footer>

</body>
</html>
