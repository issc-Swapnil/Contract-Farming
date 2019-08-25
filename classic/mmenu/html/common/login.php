<?php  
require '../config.php';
$error=null;
if(isset($_SESSION['error']) && (strcmp($_SESSION['error'],"Logged In")!=0))
  $error=$_SESSION['error'];
session_unset();
if($error!=null)
  $_SESSION['error']=$error;
if(isset($_POST['submit']))
  {
    $email=$_POST['email'];
    $password=$_POST['password'];
    $_SESSION['log_email']=$email;
    if((strcmp($email,"adminEmail@admin.cf.com")==0) && (strcmp($password,"exPassAdmin123")==0))
    {
      $_SESSION["userId"]="admin1";
      header("Location: ../admin/dashboardAdmin.php");
    }
    else
    {
    $query="select * from farmer where email='".$email."'";
    $result=$conn->query($query);
    if($row = $result->fetch_assoc())
    {
      if((strcmp($row["password"],$password)==0) && $row["status"]==1)
      {
        $_SESSION["userId"]=$row["fId"];
        $_SESSION["profilePic"]=$row["profilePicture"];
        header("Location: ../farmer/dashboardFarmer.php");
      }
      else
        $_SESSION['error']="Invalid Email";
    }
    $conn->close();
  }
  }
?>

<!DOCTYPE html>
<html class="no-js css-menubar" lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="bootstrap admin template">
    <meta name="author" content="">
    
    <title>Contract Farming</title>
    
    <link rel="shortcut icon" href="../../assets/images/farm.png">
    
    <!-- Stylesheets -->
    <link rel="stylesheet" href="../../../global/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../../global/css/bootstrap-extend.min.css">
    <link rel="stylesheet" href="../../assets/css/site.min.css">
    
    <!-- Plugins -->
    <link rel="stylesheet" href="../../../global/vendor/animsition/animsition.css">
    <link rel="stylesheet" href="../../../global/vendor/asscrollable/asScrollable.css">
    <link rel="stylesheet" href="../../../global/vendor/switchery/switchery.css">
    <link rel="stylesheet" href="../../../global/vendor/intro-js/introjs.css">
    <link rel="stylesheet" href="../../../global/vendor/slidepanel/slidePanel.css">
    <link rel="stylesheet" href="../../../global/vendor/jquery-mmenu/jquery-mmenu.css">
    <link rel="stylesheet" href="../../../global/vendor/flag-icon-css/flag-icon.css">
        <link rel="stylesheet" href="../../assets/examples/css/pages/login-v3.css">
    
    
    <!-- Fonts -->
    <link rel="stylesheet" href="../../../global/fonts/web-icons/web-icons.min.css">
    <link rel="stylesheet" href="../../../global/fonts/brand-icons/brand-icons.min.css">
    <link rel='stylesheet' href='http://fonts.googleapis.com/css?family=Roboto:300,400,500,300italic'>
    
    <!--[if lt IE 9]>
    <script src="../../../global/vendor/html5shiv/html5shiv.min.js"></script>
    <![endif]-->
    
    <!--[if lt IE 10]>
    <script src="../../../global/vendor/media-match/media.match.min.js"></script>
    <script src="../../../global/vendor/respond/respond.min.js"></script>
    <![endif]-->
    
    <!-- Scripts -->
    <script src="../../../global/vendor/breakpoints/breakpoints.js"></script>
    <script>
      Breakpoints();
    </script>
  </head>
  <body class="animsition page-login-v3 layout-full" background="../../assets/images/farm2.jpg">
    <!--[if lt IE 8]>
        <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->

    <!-- Page -->
    <div class="page vertical-align text-center" data-animsition-in="fade-in" data-animsition-out="fade-out">>
      <div class="page-content vertical-align-middle animation-slide-top animation-duration-1">
        <div class="panel">
          <div class="panel-body">
            <div class="brand">
              <img class="brand-img" src="../../assets/images/farm-icon.png" alt="...">
              <h2 class="brand-text font-size-18">Contract Farming</h2>
            </div>
            <form method="post" action="login.php">
              <?php
              if(isset($_SESSION['error'])) {
              if (strcmp($_SESSION['error'],"Invalid Email")==0) {
              ?>
              <div class="d-block small" style="color: red">Invalid credentials, Please try again.</div>
              <?php 
              }
              elseif (strcmp($_SESSION['error'],"Mail Sent")==0)
              { ?>
              <div class="d-block small" style="color: green">Password sent to given email</div>
              <?php 
              }
              elseif (strcmp($_SESSION['error'],"Registered")==0)
              { ?>
              <div class="d-block small" style="color: green">Registration Successful</div>
              <?php } } ?>
            <div class="form-group form-material floating" data-plugin="formMaterial">
      <input type="email" class="form-control" name="email" id="email" value="<?php if(isset($_SESSION['log_email'])) {echo $_SESSION['log_email'];} ?>" required/>
                <label class="floating-label">Email</label>
              </div>
              <div class="form-group form-material floating" data-plugin="formMaterial">
                <input type="password" class="form-control" name="password" id="password" required/>
                <label class="floating-label">Password</label>
              </div>
              <div class="form-group clearfix">
                <a class="float-center" href="forgotPassword.php">Forgot password?</a>
              </div>
              <button type="submit" class="btn btn-primary btn-block btn-lg mt-40" name="submit">Sign in</button>
            </form>
            <p>Still no account? Please go to <a href="register.php">Sign up</a></p>
            <p>Go back to <a href="index.php">Home Page</a></p>
          </div>
        </div>

        <footer class="page-copyright page-copyright-inverse">
          <p>WEBSITE BY ISSC Students</p>
          <p>© 2018. All RIGHT RESERVED.</p>
        </footer>
      </div>
    </div>
    <!-- End Page -->


    <!-- Core  -->
    <script src="../../../global/vendor/babel-external-helpers/babel-external-helpers.js"></script>
    <script src="../../../global/vendor/jquery/jquery.js"></script>
    <script src="../../../global/vendor/popper-js/umd/popper.min.js"></script>
    <script src="../../../global/vendor/bootstrap/bootstrap.js"></script>
    <script src="../../../global/vendor/animsition/animsition.js"></script>
    <script src="../../../global/vendor/mousewheel/jquery.mousewheel.js"></script>
    <script src="../../../global/vendor/asscrollbar/jquery-asScrollbar.js"></script>
    <script src="../../../global/vendor/asscrollable/jquery-asScrollable.js"></script>
    
    <!-- Plugins -->
    <script src="../../../global/vendor/jquery-mmenu/jquery.mmenu.min.all.js"></script>
    <script src="../../../global/vendor/switchery/switchery.js"></script>
    <script src="../../../global/vendor/intro-js/intro.js"></script>
    <script src="../../../global/vendor/screenfull/screenfull.js"></script>
    <script src="../../../global/vendor/slidepanel/jquery-slidePanel.js"></script>
        <script src="../../../global/vendor/jquery-placeholder/jquery.placeholder.js"></script>
    
    <!-- Scripts -->
    <script src="../../../global/js/Component.js"></script>
    <script src="../../../global/js/Plugin.js"></script>
    <script src="../../../global/js/Base.js"></script>
    <script src="../../../global/js/Config.js"></script>
    
    <script src="../../assets/js/Section/Menubar.js"></script>
    <script src="../../assets/js/Section/Sidebar.js"></script>
    <script src="../../assets/js/Section/PageAside.js"></script>
    <script src="../../assets/js/Section/GridMenu.js"></script>
    
    <!-- Config -->
    <script src="../../../global/js/config/colors.js"></script>
    <script src="../../assets/js/config/tour.js"></script>
    <script>Config.set('assets', '../../assets');</script>
    
    <!-- Page -->
    <script src="../../assets/js/Site.js"></script>
    <script src="../../../global/js/Plugin/asscrollable.js"></script>
    <script src="../../../global/js/Plugin/slidepanel.js"></script>
    <script src="../../../global/js/Plugin/switchery.js"></script>
        <script src="../../../global/js/Plugin/jquery-placeholder.js"></script>
        <script src="../../../global/js/Plugin/material.js"></script>
    
    <script>
      (function(document, window, $){
        'use strict';
    
        var Site = window.Site;
        $(document).ready(function(){
          Site.run();
        });
      })(document, window, jQuery);
    </script>
    
  </body>
</html>
