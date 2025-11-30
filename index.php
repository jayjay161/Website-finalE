<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Zaheer Watch Repair</title>

  <!-- BOOTSTRAP -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  
  <!-- FONTS & ICONS -->
  <link href="https://fonts.cdnfonts.com/css/br-sonoma" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    /* BODY GRADIENT */
    body {
      margin: 0;
      padding: 0;
      background: linear-gradient(to bottom, #000000, #333333);
      font-family: Arial, sans-serif;
    }

    /* HERO SECTION */
    .hero {
      position: relative;
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: flex-start; /* text on left */
      padding: 0 50px;
      color: #fff;
      overflow: hidden;
      background-color: #000; /* fallback black */
    }

    .hero-bg {
      position: absolute;
      top: 0;
      left: 0;
      width: 120%; /* stretch horizontally */
      height: 100%;
      object-fit: cover;
      object-position: 75% 64%; /* move watch to right */
      z-index: 1;
      opacity: 0;
      transform: scale(1.1);
      transition: opacity 1.5s ease-out, transform 2s ease-out;
    }

    .hero-bg.loaded {
      opacity: 1;
      transform: scale(1);
    }

    .hero-content {
      z-index: 2; /* text on top */
      opacity: 0;
      transform: translateY(30px);
      transition: opacity 1s ease-out 0.5s, transform 1s ease-out 0.5s;
    }

    .hero-content.visible {
      opacity: 1;
      transform: translateY(0);
    }

    .hero .front {
      font-family: 'BR Sonoma', sans-serif;
      font-size: 3rem;
      font-weight: bold;
      margin-bottom: 20px;
      opacity: 0;
      transform: translateX(-50px);
      transition: opacity 0.8s ease-out 0.8s, transform 0.8s ease-out 0.8s;
    }

    .hero-content.visible .front {
      opacity: 1;
      transform: translateX(0);
    }

    .hero .lead {
      font-size: 1.2rem;
      margin-bottom: 10px;
      opacity: 0;
      transform: translateX(-30px);
      transition: opacity 0.8s ease-out 1s, transform 0.8s ease-out 1s;
    }

    .hero-content.visible .lead {
      opacity: 1;
      transform: translateX(0);
    }

    .sub-lead {
      font-size: 1rem;
      margin-bottom: 30px;
      color: #ddd;
      opacity: 0;
      transform: translateX(-30px);
      transition: opacity 0.8s ease-out 1.2s, transform 0.8s ease-out 1.2s;
    }

    .hero-content.visible .sub-lead {
      opacity: 1;
      transform: translateX(0);
    }

    .btn-grey {
      background-color: #888;
      color: #fff;
      border-radius: 50px;
      padding: 12px 30px;
      font-weight: bold;
      transition: 0.3s;
      opacity: 0;
      transform: translateY(20px);
      transition: opacity 0.8s ease-out 1.4s, transform 0.8s ease-out 1.4s, background-color 0.3s;
    }

    .hero-content.visible .btn-grey {
      opacity: 1;
      transform: translateY(0);
    }

    .btn-grey:hover {
      background-color: #666;
      color: #fff;
    }

    /* IMAGE CARDS */
    .services-wrapper {
      display: flex;
      justify-content: center;
      gap: 40px;
      margin-top: 50px; /* spacing below hero */
      padding: 0 20px;
      flex-wrap: nowrap;
      overflow-x: auto;
    }

    .service-card {
      flex: 0 0 350px;
      border: none;
      border-radius: 15px;
      overflow: hidden;
      transition: transform 0.5s ease-out, box-shadow 0.5s ease-out;
      cursor: pointer;
      background-color: #fff;
      opacity: 0;
      transform: translateY(50px);
    }

    .service-card.visible {
      opacity: 1;
      transform: translateY(0);
    }

    .service-card:hover {
      transform: translateY(-10px);
      box-shadow: 0 10px 20px rgba(0,0,0,0.3);
    }

    .service-card .card-img {
      height: 300px;
      background-size: cover;
      background-position: center;
      transition: transform 0.5s ease-out;
    }

    .service-card:hover .card-img {
      transform: scale(1.05);
    }

    .service-card .card-img {
      position: relative;
      height: 300px;
      background-size: cover;
      background-position: center;
      border-radius: 15px;
      overflow: hidden;
    }

    .card-caption {
      position: absolute;
      bottom: 0;
      width: 100%;
      padding: 10px 15px;
      background: rgba(0, 0, 0, 0.5); /* semi-transparent overlay */
      color: #fff;
      font-size: 0.95rem;
      text-align: center;
      transform: translateY(100%);
      transition: transform 0.4s ease-out;
    }

    .service-card:hover .card-caption {
      transform: translateY(0);
    }

    /* FOOTER */
    footer {
      text-align: center;
      padding: 30px 20px;
      background-color: #222;
      color: #fff;
      margin-top: 80px;
      opacity: 0;
      transform: translateY(30px);
      transition: opacity 1s ease-out, transform 1s ease-out;
    }

    footer.visible {
      opacity: 1;
      transform: translateY(0);
    }

    footer .social-links a {
      margin: 0 10px;
      color: #fff;
      font-size: 1.2rem;
      transition: 0.3s;
    }
    footer .social-links a:hover {
      color: #d4af37;
    }

    /* Scrollbar styling for cards */
    .services-wrapper::-webkit-scrollbar {
      height: 8px;
    }
    .services-wrapper::-webkit-scrollbar-thumb {
      background-color: rgba(0,0,0,0.2);
      border-radius: 4px;
    }
  </style>
</head>
<body>

  <!-- NAVBAR -->
  <nav class="navbar navbar-expand-lg navbar-dark fixed-top" style="background: rgba(0, 0, 0, 0.63); top: 0; transition: top 0.3s;">
    <a class="navbar-brand font-weight-bold" href="#">Zaheer Watch Repair</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item"><a class="nav-link text-center" href="#home">Home</a></li>
        <li class="nav-item"><a class="nav-link text-center" href="service.php">Services</a></li>
        <li class="nav-item"><a class="nav-link text-center" href="book.php">Book Repair</a></li>
        <li class="nav-item"><a class="nav-link text-center" href="track.php">Track Repair</a></li>
        <li class="nav-item"><a class="nav-link text-center" href="admin-login.php">Login</a></li>
      </ul>
    </div>
  </nav>

  <!-- HERO SECTION -->
  <section id="home" class="hero">
    <div class="hero-content">
      <h1 class="front">Time Restored</h1>
      <p class="lead">Expert watch repair for all brands and styles</p>
      <p class="sub-lead">We bring your timepieces back to life with precision and care.</p>
      <a href="book.php" class="btn btn-lg btn-grey align-items-center">BOOK A REPAIR</a>
    </div>
    <img src="backg.jpg" alt="Watch" class="hero-bg">
  </section>

  <!-- 3 IMAGE CARDS WITH TEXT OVER IMAGE -->
  <section id="services">
    <div class="container">
      <div class="services-wrapper">

        <div class="card service-card">
          <div class="card-img" style="background-image: url('pic0.jpg');">
            <div class="card-caption">Classic timepiece restoration</div>
          </div>
        </div>

        <div class="card service-card">
          <div class="card-img" style="background-image: url('pc9.jpg');">
            <div class="card-caption">Wristwatch on-hand precision repair</div>
          </div>
        </div>

        <div class="card service-card">
          <div class="card-img" style="background-image: url('pic3.jpg');">
            <div class="card-caption">Modern watches maintenance</div>
          </div>
        </div>

      </div>
    </div>
  </section>

  <!-- FOOTER -->
  <footer>
    <p>&copy; 2025 Zaheer. All rights reserved.</p>
    <div class="social-links">
      <a href="https://facebook.com" target="_blank"><i class="fab fa-facebook-f"></i></a>
      <a href="https://instagram.com" target="_blank"><i class="fab fa-instagram"></i></a>
    </div>
  </footer>

  <!-- JS -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>

  <!-- NAVBAR HIDE/SHOW ON SCROLL -->
  <script>
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
      // Hero background image transition
      const heroBg = document.querySelector('.hero-bg');
      heroBg.addEventListener('load', function() {
        this.classList.add('loaded');
      });
      
      // Force load if image is cached
      if (heroBg.complete) {
        heroBg.classList.add('loaded');
      }

      // Hero content transition
      const heroContent = document.querySelector('.hero-content');
      setTimeout(function() {
        heroContent.classList.add('visible');
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
              setTimeout(() => {
                entry.target.classList.add('visible');
              }, 200);
            } else if (entry.target.tagName === 'FOOTER') {
              entry.target.classList.add('visible');
            }
          }
        });
      }, observerOptions);

      // Observe service cards
      document.querySelectorAll('.service-card').forEach(card => {
        observer.observe(card);
      });

      // Observe footer
      const footer = document.querySelector('footer');
      observer.observe(footer);
    });
  </script>

</body>
</html>