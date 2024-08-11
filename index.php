<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <title>Code Forum</title>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css"
    integrity="sha384-dpuaG1suU0eT09tx5plTaGMLBsfDLzUCCUXOY2j/LSvXYuG6Bqs43ALlhIqAJVRb" crossorigin="anonymous">

  <style>
    .navbar {
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
      /* Black box shadow */
    }

    a {
      text-decoration: none;
      color: grey;
    }

    #spinner {
      position: fixed;
      top: 0;
      left: 0;
      height: 100vh;
      width: 100vw;
      display: flex;
      justify-content: center;
      align-items: center;
      background-color: #ffffff;
      z-index: 1050;
    }
  </style>

  <!-- Custom styles for this template -->
  <link href="CSS/carousel.css" rel="stylesheet">
  <link rel="stylesheet" href="CSS/header.css">
  <link rel="stylesheet" href="CSS/footer.css">
</head>

<body>

  <header>
    <?php
    include_once 'Partials/header.php';
    ?>
  </header>
 


    <main id="main-content">
      <div id="myCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
          <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="0" class="active" aria-current="true"
            aria-label="Slide 1"></button>
          <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
          <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner">
          <div class="carousel-item active">
            <img src="Images/prog2.png" width="100%" height="100%">
            <div class="container">
              <div class="carousel-caption">
                <h1>Welcome to the Code Forum! </h1>
                <p> Dive into the world of programming and tech discussions.</p>
                <p><a class="btn btn-lg btn-primary" href="SignUp1.php">Learn more</a></p>
              </div>
            </div>
          </div>
          <div class="carousel-item">
            <img src="Images/prog.png" width="100%" height="100%">
            <div class="container">
              <div class="carousel-caption text-start">
                <h1>Example headline.</h1>
                <p>Some representative placeholder content for the first slide of the carousel.</p>
                <p><a class="btn btn-lg btn-primary" href="SignUp1.php">Sign up today</a></p>
              </div>
            </div>
          </div>
          <div class="carousel-item">
            <img src="Images/prog3.png" width="100%" height="100%">
            <div class="container">
              <div class="carousel-caption text-end">
                <h1>One more for good measure.</h1>
                <p>Some representative placeholder content for the third slide of this carousel.</p>
                <p><a class="btn btn-lg btn-primary" href="login1.php">Browse Forums</a></p>
              </div>
            </div>
          </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#myCarousel" data-bs-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#myCarousel" data-bs-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Next</span>
        </button>
      </div>

      <div class="container marketing">
        <h1 class="fs-bold text-secondary d-flex justify-content-center mt-5" id="forums"><a href="">Our Forums</a></h1>
        <div class="row">
          <div class="col-lg-4">
            <img src="Images/java1.png" height="50%" width="50%">
            <h2>Java</h2>
            <p>Write once, run anywhere. Java’s portability across different platforms makes it an ideal choice for
              developing cross-platform applications.</p>
            <p><a class="btn btn-secondary" href="login1.php">View details &raquo;</a></p>
          </div>
          <div class="col-lg-4">
            <img src="Images/c.png" height="50%" width="50%">
            <h2>C</h2>
            <p>Harness the power of C to write highly efficient and fast code. Perfect for performance-critical
              applications where speed and resource management are crucial.</p>
            <p><a class="btn btn-secondary" href="login1.php">View details &raquo;</a></p>
          </div>
          <div class="col-lg-4">
            <img src="Images/python1.webp" height="50%" width="50%">
            <h2>Python</h2>
            <p> Python's readability and straightforward syntax make it an excellent choice for beginners and
              professionals alike, fostering clear and maintainable code.</p>
            <p><a class="btn btn-secondary" href="login1.php">View details &raquo;</a></p>
          </div>
        </div><!-- /.row -->


        <hr class="featurette-divider">
        <h1 class="fs-bold text-secondary d-flex justify-content-center mt-5" id="aboutus"><a href="">About Us</a></h1>

        <div class="row featurette">
          <div class="col-md-7">
            <h2 class="featurette-heading">"Connecting Minds, Sharing Knowledge"</h2>
            <p class="lead">We bring together individuals from diverse backgrounds to share insights, discuss ideas, and
              solve problems collaboratively.</p>
          </div>
          <div class="col-md-5">
            <img src="Images/about1.avif" width="100%" height="100%">
          </div>
        </div>

        <hr class="featurette-divider">
        <div class="row featurette-2">
          <div class="col-md-7 order-md-2">
            <h2 class="featurette-heading">Why We Exist?</h2>
            <p class="lead">To provide a dynamic platform where technology enthusiasts and professionals can discuss
              ideas, solve problems, and advance their skills.</p>
          </div>
          <div class="col-md-5 order-md-1">
            <img src="Images/globe1.jpg" width="100%" height="100%">
          </div>
        </div>

        <hr class="featurette-divider">
        <div class="row featurette-3">
          <div class="col-md-7">
            <h2 class="featurette-heading">"Elevate Your Expertise"</h2>
            <p class="lead">Elevate your expertise and stay ahead with cutting-edge discussions, tutorials, and industry
              insights.</p>
          </div>
          <div class="col-md-5">
            <img src="Images/about2.jpg" width="100%" height="100%">
          </div>
        </div>

        <hr class="featurette-divider">
      </div>
      <?php
      include_once 'Partials/Footer.php';
      ?>
    </main>
    <div class="container">
    <div class="spinner-container" id="spinner">
      <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
      crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.1/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.1/ScrollTrigger.min.js"></script> 
    <script src="JS/Animation.js"></script>
    <script src="JS/jquery-3.7.1.min.js"></script>
    <script>
      $(document).ready(function(){
        $("#spinner").fadeOut();  
      })
    </script>
</body>

</html>