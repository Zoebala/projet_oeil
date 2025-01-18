
  @include("layouts.template.portions.t_header")

  <!-- ======= Header ======= -->
  <header id="header" class="d-flex align-items-center">
    <div class="container d-flex align-items-center justify-content-between">

      <h1 class="logo fst-italic"><a href="#"><img src="{{ './images/isp.png' }}" class="img-fluid" alt="logo" width="50px" height="60px"> ISP Mb-Ngungu</a></h1>
      <!-- Uncomment below if you prefer to use an image logo -->
      <!-- <a href="index.html" class="logo"><img src="template/assets/img/logo.png" alt=""></a>-->

      @include("layouts.template.portions.t_nav")
      <!-- .navbar -->

    </div>
  </header><!-- End Header -->

  <!-- ======= Hero Section ======= -->
  @include("layouts.template.portions.t_home")
  <!-- End Hero -->

  <main id="main">


      @include("layouts.template.portions.t_actualite")
    @include("layouts.template.portions.t_apropos")
    @include("layouts.template.portions.t_service")
    <!-- End Services Section -->

    <!-- ======= Testimonials Section ======= -->

    <!-- ======= Contact Section ======= -->
    @include("layouts.template.portions.t_contact")
    <!-- End Contact Section -->

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
    @include("layouts.template.portions.t_footer")
  <!-- End Footer -->

  <div id="preloader"></div>
  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="template/assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="template/assets/vendor/aos/aos.js"></script>
  <script src="template/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="template/assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="template/assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="template/assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="template/assets/vendor/waypoints/noframework.waypoints.js"></script>
  <script src="template/assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="template/assets/js/main.js"></script>
  <script src="js/typed.js"></script>
  <script>
           var typed = new Typed('.typed-words', {
           strings: ["L'Institut Supérieur Pédagogique","de Mbanza-Ngungu","vous souhaite","la bienvenue","dans sa plateforme en ligne"],
           typeSpeed: 80,
           backSpeed: 80,
           backDelay: 4000,
           startDelay: 1000,
           loop: true,
           showCursor: true
           });
   </script>

</body>

</html>
