<?php

?>
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Social Code</title>

  <!-- Bootstrap core CSS -->
  <link href="assets/css/boot.css" rel="stylesheet">

  <!-- Custom fonts -->
  <link href="assets/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/simple-line-icons.css">
  <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Catamaran:100,200,300,400,500,600,700,800,900" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Muli" rel="stylesheet">
  <link rel="stylesheet" href="assets/device-mockups/device-mockups.css">
  <link href="assets/css/new-age.min.css" rel="stylesheet">
  <style type="text/css">
    .fixed-top{
        border-color: rgba(34,34,34,.1)!important;
        background-color: #05cd51!important;
        color: #212529!important;
    }
    header.masthead{
      background: url(assets/img/bg-pattern.png),linear-gradient(to left,#13bd59,#05cd51)!important;
    }
    .btn-outline.active, .btn-outline:active, .btn-outline:focus, .btn-outline:hover {
        color: #000;
        border-color: #05cd51;
        background-color: #fff;
    }
    .lcd{
      max-width: 100%!important;
    }
    .section-heading {
        margin-bottom: 1px!important;
    }
    section.cta .cta-content h2 {
      max-width: 100%!important;
    }
    section {
      padding: 70px 0!important;
    }
    .demo{
      height: 100%!important;
    }
    section.features .feature-item i {
     /* background: linear-gradient(to left,#7b43976b,#05cd51)!important;*/
    }
  </style>
</head>

<body id="page-top">

  <!-- Navigation -->
  <nav class="navbar navbar-expand-lg navbar-light fixed-top" id="mainNav" style="padding: 0px; background-color: #05cd51;">
    <div class="container">
      <a class="navbar-brand js-scroll-trigger" href="#page-top"><img src="assets/logo/logo.svg" style="width:4em;"></a>
    </div>
  </nav>

  <header class="masthead">
    <div class="container h-100">
      <div class="row h-100">
        <div class="col-lg-7 my-auto">
          <div class="header-content mx-auto">
            <h1 class="mb-5">Social Code is a platform where you can connect with your friends.</h1>
            <a href="" id="introtwo" class="btn btn-outline btn-xl js-scroll-trigger">Start Now!</a>
          </div>
        </div>
        <div class="col-lg-5 my-auto">
          <div class="device-container">
            <div class="device-mockup iphone6_plus portrait white">
              <div class="device">
                <div class="screen">
                  <!-- Demo image for screen mockup, you can put an image here, some HTML, an animation, video, or anything else! -->
                  <img src="assets/img/demo-screen-1.jpg" class="img-fluid" alt="">
                </div>
                <div class="button">
                  <!-- You can hook the "home button" to some JavaScript events or just remove it -->
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>


  <section class="features" id="features">
    <div class="container">
      <div class="section-heading text-center">
        <h2>Unlimited Features, Unlimited Fun</h2>
        <p class="text-muted">Check out what you can do with this app!</p>
        <hr>
      </div>
      <div class="row">
        <div class="col-lg-6 my-auto">
          <div class="device-container lcd">
            <div class="device-mockup samsung_tv portrait white">
              <div class="device">
                <div class="screen">
                  <!-- Demo image for screen mockup, you can put an image here, some HTML, an animation, video, or anything else! -->
                  <img src="assets/images/web-demo.png" class="img-fluid demo" alt="">
                </div>
                <div class="button">
                  <!-- You can hook the "home button" to some JavaScript events or just remove it -->
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-6 my-auto">
          <div class="container-fluid">
            <div class="row">
              <div class="col-lg-6">
                <div class="feature-item">
                  <i class="icon-screen-smartphone text-primary"></i>
                  <h3>Cross Platform Availability</h3>
                  <p class="text-muted">Use on any platform. Android, IOS and Desktop</p>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="feature-item">
                  <i class="icon-camera text-primary"></i>
                  <h3>Multimedia</h3>
                  <p class="text-muted">Share videos, pictures and text with your friends</p>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-6">
                <div class="feature-item">
                  <i class="icon-present text-primary"></i>
                  <h3>Free to Use</h3>
                  <p class="text-muted">It is free and always will be</p>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="feature-item">
                  <i class="icon-lock text-primary"></i>
                  <h3>Secure and Safe</h3>
                  <p class="text-muted">We respect your privacy and your data is not accessible by anyone</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="cta">
    <div class="cta-content">
      <div class="container">
        <h2>Stop waiting.<br>Start Socializing Now!</h2>
        <a href="" id="introne" class="btn btn-outline btn-xl js-scroll-trigger">Let's Get Started!</a>
      </div>
    </div>
    <div class="overlay"></div>
  </section>

  <section class="contact bg-primary" id="contact">
    <div class="container">
      <h2>Made by K2 With
        <i class="fas fa-heart"></i>
      </h2>
    </div>
  </section>

  <script type="text/javascript">
    var host="http://"+location.hostname+"/fyp";
    document.getElementById("introne").setAttribute("href", host+"/?intro=false");
    document.getElementById("introtwo").setAttribute("href", host+"/?intro=false");
  </script>

  <!-- Bootstrap core JavaScript -->
  <script type="text/javascript" src="assets/js/jquery.min.js"></script>
  <script type="text/javascript" src="assets/js/boot.js"></script>
  <script src="assets/js/jquery.easing.min.js"></script>
  <script src="assets/js/new-age.min.js"></script>

</body>

</html>
<?php

?>