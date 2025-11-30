<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Services - Zaheer Watch Repair</title>

  <!-- BOOTSTRAP -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

  <!-- FONT -->
  <link href="https://fonts.cdnfonts.com/css/br-sonoma" rel="stylesheet">

  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f0f0f0; 
      margin: 0;
      padding: 0;
      color: #333;
    }

    /* CARD WRAPPER */
    .services-wrapper {
      display: flex;
      justify-content: center;
      gap: 40px; /* slightly bigger gap like homepage */
      flex-wrap: wrap;
    }

    /* CARD SIZE */
    .service-card {
      width: 300px; /* match homepage cards */
      border-radius: 10px;
      overflow: hidden;
      background-color: #fff;
      box-shadow: 0 6px 15px rgba(0,0,0,0.1);
      transition: transform 0.5s ease-out, box-shadow 0.5s ease-out;
      cursor: pointer;
      margin-bottom: 20px;
      opacity: 0;
      transform: translateY(50px);
    }

    .service-card.visible {
      opacity: 1;
      transform: translateY(0);
    }

    .service-card:hover {
      transform: translateY(-10px);
      box-shadow: 0 12px 25px rgba(0,0,0,0.15);
    }

    .service-card .card-img {
      height: 300px; /* match homepage cards */
      background-size: cover;
      background-position: center;
      transition: transform 0.5s ease-out;
    }

    .service-card:hover .card-img {
      transform: scale(1.05);
    }

    .service-card .card-body {
      padding: 15px;
      text-align: center;
      opacity: 0;
      transform: translateY(20px);
      transition: opacity 0.5s ease-out 0.2s, transform 0.5s ease-out 0.2s;
    }

    .service-card.visible .card-body {
      opacity: 1;
      transform: translateY(0);
    }

    /* FONTS MATCHING MAIN.PHP */
    .service-card .card-title {
      font-family: 'BR Sonoma', sans-serif;
      font-size: 1.1rem; /* same as homepage lead */
      font-weight: bold;
      margin-bottom: 8px;
      color: #333;
    }

    .service-card .card-text {
      font-family: 'BR Sonoma', sans-serif;
      font-size: 0.95rem; /* same as homepage sub-lead */
      color: #666;
    }

    h2 {
      font-family: 'BR Sonoma', sans-serif;
      font-size: 2.5rem; /* match homepage hero heading */
      font-weight: bold;
      margin-bottom: 40px;
      color: #222;
      text-align: center;
      opacity: 0;
      transform: translateY(30px);
      transition: opacity 0.8s ease-out, transform 0.8s ease-out;
    }

    h2.visible {
      opacity: 1;
      transform: translateY(0);
    }

    /* SECTION ANIMATION */
    #services {
      opacity: 0;
      transform: translateY(30px);
      transition: opacity 1s ease-out, transform 1s ease-out;
    }

    #services.visible {
      opacity: 1;
      transform: translateY(0);
    }

    /* FOOTER WITH TRANSITIONS */
    footer {
      background-color: #111;
      color: #fff;
      text-align: center;
      padding: 25px 0;
      margin-top: 50px;
      opacity: 0;
      transform: translateY(30px);
      transition: opacity 1s ease-out, transform 1s ease-out;
    }

    footer.visible {
      opacity: 1;
      transform: translateY(0);
    }

    /* NAVBAR TRANSITION */
    .navbar {
      transition: top 0.3s, background-color 0.3s ease-out;
    }
  </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top" style="background: rgba(0,0,0,0.63);">
  <a class="navbar-brand font-weight-bold" href="index.html">Zaheer Watch Repair</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav ml-auto">
      <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
      <li class="nav-item active"><a class="nav-link" href="service.php">Services</a></li>
      <li class="nav-item"><a class="nav-link text-center" href="book.php">Book Repair</a></li>
      <li class="nav-item"><a class="nav-link text-center" href="track.php">Track Repair</a></li>
      <li class="nav-item"><a class="nav-link text-center" href="admin-login.php">Login</a></li>
    </ul>
  </div>
</nav>

<!-- SERVICES SECTION -->
<section id="services" class="py-5" style="margin-top: 80px;">
  <div class="container">
    <h2>Our Services</h2>

    <div class="services-wrapper">
      <div class="card service-card">
        <div class="card-img" style="background-image: url('pngwing.com (6).png');"></div>
        <div class="card-body">
          <h5 class="card-title">Battery Replacement</h5>
          <p class="card-text">Quick and precise battery replacement for all types of quartz watches.</p>
        </div>
      </div>

      <div class="card service-card">
        <div class="card-img" style="background-image: url('pngwing.com (5).png');"></div>
        <div class="card-body">
          <h5 class="card-title">Movement Repair</h5>
          <p class="card-text">Overhaul and repair of mechanical watch movements to restore perfect timing.</p>
        </div>
      </div>

      <div class="card service-card">
        <div class="card-img" style="background-image: url('pngwing.com (4).png');"></div>
        <div class="card-body">
          <h5 class="card-title">Strap & Bracelet Service</h5>
          <p class="card-text">Replacement, adjustment, and polishing for leather, metal, or rubber straps.</p>
        </div>
      </div>
    </div>

  </div>
</section>

<!-- FOOTER -->
<footer>
  <p>&copy; 2025 Zaheer. All rights reserved.</p>
</footer>

<!-- JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>

<script>
  // NAVBAR HIDE/SHOW ON SCROLL - Same as homepage
  let lastScrollTop = 0;
  const navbar = document.querySelector('.navbar');

  window.addEventListener('scroll', function() {
    let st = window.pageYOffset || document.documentElement.scrollTop;
    if (st > lastScrollTop) {
      navbar.style.top = "-80px";
    } else {
      navbar.style.top = "0";
    }
    lastScrollTop = st <= 0 ? 0 : st;
  });

  // TRANSITION ANIMATIONS ON LOAD AND SCROLL
  document.addEventListener('DOMContentLoaded', function() {
    // Initial page load animations
    setTimeout(function() {
      const servicesSection = document.getElementById('services');
      const heading = document.querySelector('h2');
      
      servicesSection.classList.add('visible');
      heading.classList.add('visible');
    }, 300);

    // Scroll animations for cards and footer
    const observerOptions = {
      threshold: 0.1,
      rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          if (entry.target.classList.contains('service-card')) {
            // Staggered animation for cards
            setTimeout(() => {
              entry.target.classList.add('visible');
            }, entry.target.dataset.delay || 0);
          } else if (entry.target.tagName === 'FOOTER') {
            // Footer animation
            setTimeout(() => {
              entry.target.classList.add('visible');
            }, 300);
          }
        }
      });
    }, observerOptions);

    // Observe service cards with staggered delays
    document.querySelectorAll('.service-card').forEach((card, index) => {
      card.dataset.delay = index * 200; // 200ms delay between each card
      observer.observe(card);
    });

    // Observe footer
    const footer = document.querySelector('footer');
    observer.observe(footer);
  });

  // Add smooth loading for images
  window.addEventListener('load', function() {
    document.querySelectorAll('.card-img').forEach(img => {
      img.style.opacity = '1';
    });
  });
</script>

</body>
</html>