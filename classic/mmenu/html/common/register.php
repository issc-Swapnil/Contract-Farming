<?php  
require '../config.php';
$_SESSION['error']=null;
if(isset($_POST['submit']))
  {
  	$name=$_POST['name'];
    $_SESSION['name']=$name;
    $email=$_POST['email'];
    $password=$_POST['password'];
    $password2=$_POST['PasswordCheck'];
    $phone=$_POST['phone'];
    $_SESSION['phone']=$phone;
    $region=$_POST['region'];
    $_SESSION['region']=$region;
    $village=$_POST['village'];
    $_SESSION['village']=$village;
    $area=$_POST['area'];
    $_SESSION['area']=$area;
    if(strcmp($password,$password2)!=0)
      $_SESSION['error']="Password Mismatch";
    else
    {
      if(preg_match('/[^A-Za-z0-9]/', $password))
      {
        $_SESSION['error']="Password Invalid";
      }
      else
      {
    $query="select fId from farmer where email='".$email."'";
    $result=$conn->query($query);
    if($row = $result->fetch_assoc())
    	$_SESSION['error']="Invalid Email";
    else
    {
    $_SESSION['name']=$name;
    $query="select stateName from state where stateId=".$_POST['state'];
    $result=$conn->query($query);
    if(!($row = $result->fetch_assoc()))
    	$_SESSION['error']="Database Error";
    else
    {
    	$state=$row['stateName'];
    $query="insert into farmer(fName,email,password,phone,state,region,village,landArea,signUpDate,profilePicture) values ('".$name."','".$email."','".$password."','".$phone."','".$state."','".$region."','".$village."','".$area."','".date('Y-m-d')."','profile/user.png')";
    if(!$conn->query($query))
    	$_SESSION['error']="Database Error";
    else
    {
      $query="select fId from farmer where email='".$email."'";
      $result=$conn->query($query);
      if($row = $result->fetch_assoc())
      {
        $fid=$row['fId'];
        $crop=$_POST['crop'];
        for($i=0;$i<count($crop);$i++)
        {
        $query="insert into farmer_crop values(".$fid.",".$crop[$i].")";
        $conn->query($query);
        }
    	 $_SESSION['error']="Registered";
      header("Location: login.php");
    }
    else
    {
      $_SESSION['error']="Database Error";
    }
    }
    }
  }
	}
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
        <link rel="stylesheet" href="../../assets/examples/css/pages/register-v3.css">
    
    
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
  <body class="animsition page-register-v3 layout-full" background="../../assets/images/farm2.jpg">
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
            <form method="post" action="register.php">
            <?php
              if(isset($_SESSION['error'])) {
              if (strcmp($_SESSION['error'],"Invalid Email")==0) {
              ?>
              <div class="d-block small" style="color: red">This email cannot be used, please use a different one</div>
              <?php 
              }
              elseif (strcmp($_SESSION['error'],"Password Mismatch")==0)
              { ?>
              <div class="d-block small" style="color: red">Please confirm correct Password</div>
              <?php 
              }
              elseif (strcmp($_SESSION['error'],"Password Invalid")==0)
              { ?>
              <div class="d-block small" style="color: red">Your password can only contain letters or numbers</div>
              <?php 
              }
              elseif (strcmp($_SESSION['error'],"Database Error")==0)
              { ?>
              <div class="d-block small" style="color: red">There was an error while uploading data</div>
              <?php } } ?>
              <div class="row">
              <div class="col-md-6 col-lg-6">
                <div class="form-group form-material floating" data-plugin="formMaterial">
                  <input type="text" class="form-control" name="name" id="name" value="<?php if(isset($_SESSION['name'])) {echo $_SESSION['name'];} ?>" required/>
                  <label class="floating-label">Full Name</label>
                </div>
                </div>
                <div class="col-md-6 col-lg-6">
                <div class="form-group form-material floating" data-plugin="formMaterial">
                  <input type="email" class="form-control" name="email" id="email" value="<?php if(isset($_SESSION['email'])) {echo $_SESSION['email'];} ?>" required/>
                  <label class="floating-label">Email</label>
                </div>
                </div>
              </div>
              <div class="row">
              <div class="col-md-6 col-lg-6">
              <div class="form-group form-material floating" data-plugin="formMaterial">
                <input type="password" class="form-control" name="password" id="password" required/>
                <label class="floating-label">Password</label>
              </div>
              </div>
              <div class="col-md-6 col-lg-6">
              <div class="form-group form-material floating" data-plugin="formMaterial">
                <input type="password" class="form-control" name="PasswordCheck" id="PasswordCheck" required/>
                <label class="floating-label">Re-enter Password</label>
              </div>
              </div>
              </div>
              <div class="row">
              <div class="col-md-6 col-lg-6">
              <div class="form-group form-material floating" data-plugin="formMaterial">
                <input type="tel" class="form-control" name="phone" id="phone" value="<?php if(isset($_SESSION['phone'])) {echo $_SESSION['phone'];} ?>" required/>
                <label class="floating-label">Phone Number</label>
              </div>
              </div>
              <div class="col-md-6 col-lg-6">
              <div class="form-group form-material floating" data-plugin="formMaterial">
                <input type="number" class="form-control" name="area" id="area" min="0" value="<?php if(isset($_SESSION['area'])) {echo $_SESSION['area'];} ?>" required/>
                <label class="floating-label">Land Area(In Acres)</label>
              </div>
              </div>
              </div>
              <div class="form-group form-material floating" data-plugin="formMaterial">
                    <select class="form-control" name="state" required>
                      <?php
                      $query="select * from state";
                      $result=$conn->query($query);
                      while($row = $result->fetch_assoc())
                      {
                      ?>
                      <option value="<?php echo $row['stateId']; ?>"><?php echo $row['stateName']; ?></option>
                      <?php
                      }
                      ?>
                    </select>
                    <label class="floating-label">State</label>
                  </div>
              <div class="row">
              <div class="col-md-6 col-lg-6">
              <div class="form-group form-material floating" data-plugin="formMaterial">
                <input type="text" class="form-control" name="region" id="region" value="<?php if(isset($_SESSION['region'])) {echo $_SESSION['region'];} ?>" required/>
                <label class="floating-label">Region</label>
              </div>
              </div>
              <div class="col-md-6 col-lg-6">
              <div class="form-group form-material floating" data-plugin="formMaterial">
                <input type="text" class="form-control" name="village" id="village" value="<?php if(isset($_SESSION['village'])) {echo $_SESSION['village'];} ?>" required/>
                <label class="floating-label">Village</label>
              </div>
              </div>
              </div>
              <div class="form-group form-material floating" data-plugin="formMaterial">
                    <select class="form-control" name="crop[]" multiple="" required>
                      <?php
                      $query="select cropId,cropName from crop";
                      $result=$conn->query($query);
                      while($row = $result->fetch_assoc())
                      {
                      ?>
                      <option value="<?php echo $row['cropId']; ?>"><?php echo $row['cropName']; ?></option>
                      <?php
                      }
                      ?>
                    </select>
                    <label class="floating-label">Crop</label>
                  </div>


              <button type="submit" class="btn btn-primary btn-block btn-lg mt-40" name="submit">Sign up</button>
            </form>
            <p>Have an account already? Please go to <a href="login.php">Sign In</a></p>
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
