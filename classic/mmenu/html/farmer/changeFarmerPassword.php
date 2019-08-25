<?php  
require '../config.php';
if(!isset($_SESSION["userId"]))
  header("Location: login.php");
$error=0;
if(isset($_POST['submit']))
  {
    $curPass=$_POST['curPass'];
    $newPass=$_POST['newPass'];
    $confPass=$_POST['confPass'];
    if(strcmp($newPass,$confPass)!=0)
      $error=1;
    elseif((preg_match('/[^A-Za-z0-9]/', $newPass)) || strlen($newPass)<8 || strlen($newPass)>20)
        $error=2;
    else
    {
    $query="select password from farmer where fId=".$_SESSION["userId"];
    $result=$conn->query($query);
    if($row = $result->fetch_assoc())
    {
      if((strcmp($row["password"],$curPass)==0))
      {
        $query="update farmer set password='".$newPass."' where fId=".$_SESSION["userId"];
        if($conn->query($query))
          $error=3;
      }
      else
        $error=4;
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
    
    <title>Change Password</title>
    
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
  <body class="animsition site-navbar-small ">
    <!--[if lt IE 8]>
        <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->

    <nav class="site-navbar navbar navbar-default navbar-fixed-top navbar-mega" role="navigation">
    
      <div class="navbar-header">
        <button type="button" class="navbar-toggler hamburger hamburger-close navbar-toggler-left hided"
          data-toggle="menubar">
          <span class="sr-only">Toggle navigation</span>
          <span class="hamburger-bar"></span>
        </button>
        <button type="button" class="navbar-toggler collapsed" data-target="#site-navbar-collapse"
          data-toggle="collapse">
          <i class="icon wb-more-horizontal" aria-hidden="true"></i>
        </button>
        <div class="navbar-brand navbar-brand-center site-gridmenu-toggle" data-toggle="menubar">
          <img class="navbar-brand-logo" src="../../assets/images/farm.png" title="CF">
          <span class="navbar-brand-text hidden-xs-down">Contract Farming</span>
        </div>
      </div>
      <div class="navbar-container container-fluid">
        <!--- Navbar Collapse -->
        <div class="collapse navbar-collapse navbar-collapse-toolbar" id="site-navbar-collapse">
    
          <!-- Navbar Toolbar Right -->
          <ul class="nav navbar-toolbar navbar-right navbar-toolbar-right">
            <li class="nav-item dropdown">
              <a class="nav-link" data-toggle="dropdown" href="javascript:void(0)" title="Notifications"
                aria-expanded="false" data-animation="scale-up" role="button">
                <i class="icon wb-bell" aria-hidden="true"></i>
                <?php
                $query="select count(message) from notification where userId=".$_SESSION['userId']." AND status=0";
                $result=$conn->query($query);
                $row = $result->fetch_assoc();
                if($row['count(message)']!=0)
                {
                ?>
                <span class="badge badge-pill badge-danger up"><?php echo $row['count(message)']; ?></span>
                <?php
                }
                ?>
              </a>
              <div class="dropdown-menu dropdown-menu-right dropdown-menu-media" role="menu">
                <div class="dropdown-menu-header">
                  <h5>NOTIFICATIONS</h5>
                  <?php
                  if($row['count(message)']!=0)
                  {
                  ?>
                  <span class="badge badge-round badge-danger">New <?php echo $row['count(message)']; ?></span>
                  <?php
                }
                ?>
                </div>
    
                <div class="list-group">
                  <div data-role="container">
                    <div data-role="content">
                    <?php
                  $query="select * from notification where userId=".$_SESSION['userId']." order by notifId desc";
                  $result=$conn->query($query);
                  for($i=1;$i<=5;$i++)
                  {
                if($row = $result->fetch_assoc())
                {
                  ?>
                      <a class="list-group-item dropdown-item" href="javascript:void(0)" role="menuitem">
                        <div class="media">
                          <div class="media-body">
                            <?php
                            if($row['status']==0)
                            {
                            ?>
                            <h6 class="media-heading"><strong><?php echo $row['message']; ?></strong></h6>
                            <?php
                            }
                            else
                            {
                              ?>
                              <h6 class="media-heading" style="color: #757575; "><?php echo $row['message']; ?></h6>
                              <?php } ?>
                            <time class="media-meta" datetime="2018-06-12T20:50:48+08:00"><?php echo $row['time']; ?></time>
                          </div>
                        </div>
                      </a>
                    
                    <?php
                  }
                }
                    ?>

                    </div>
                  </div>
                </div>
                <div class="dropdown-menu-footer">
                  <a class="dropdown-item" href="farmerNotifications.php" role="menuitem">
                    All notifications
                  </a>
                </div>
              </div>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link navbar-avatar" data-toggle="dropdown" href="#" aria-expanded="false"
                data-animation="scale-up" role="button">
                <span class="avatar avatar-online">
                  <img src="<?php echo $_SESSION['profilePic']; ?>" alt="profile/user.jpg">
                  <i></i>
                </span>
              </a>
              <div class="dropdown-menu" role="menu">
                <a class="dropdown-item" href="viewFarmerProfile.php" role="menuitem"><i class="icon wb-user" aria-hidden="true"></i> View Profile</a>
                <a class="dropdown-item" href="editFarmerProfile.php" role="menuitem"><i class="icon wb-pencil" aria-hidden="true"></i> Edit Profile</a>
                <a class="dropdown-item active" href="javascript:void(0)" role="menuitem"><i class="icon wb-settings" aria-hidden="true"></i> Change Password</a>
                <div class="dropdown-divider" role="presentation"></div>
                <a class="dropdown-item" href="javascript:void(0)" data-target="#logoutModal" data-toggle="modal" role="menuitem"><i class="icon wb-power" aria-hidden="true"></i> Logout</a>
              </div>
            </li>
          </ul>
          <!-- End Navbar Toolbar Right -->
        </div>
        <!-- End Navbar Collapse -->
      </div>
    </nav>    
    <!--logout modal-->
    <div class="modal fade modal-fade-in-scale-up" id="logoutModal" aria-hidden="true" aria-labelledby="exampleModalTitle" role="dialog" tabindex="-1">
      <div class="modal-dialog modal-simple">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
            <h4 class="modal-title">Logout</h4>
          </div>
        <div class="modal-body">
        <p>Are you sure you want to logout?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <a type="button" class="btn btn-primary" href="../index.php">Logout</a>
      </div>
    </div>
    </div>
    </div>
    <!--end logout modal-->
    <div class="site-menubar">
      <ul class="site-menu">
        <li class="site-menu-item">
          <a href="dashboardFarmer.php">
            <i class="site-menu-icon wb-dashboard" aria-hidden="true"></i>
            <span class="site-menu-title">Dashboard</span>
          </a>
        </li>
        <li class="site-menu-item">
          <a href="viewCompletedFarmerContracts.php">
            <i class="site-menu-icon wb-book" aria-hidden="true"></i>
            <span class="site-menu-title">Completed Contracts</span>
          </a>
        </li>
      </div>
    </div>
  </div>

    <!-- Page -->
    <div class="page">
      <div class="page-header">
        <h1 class="page-title">Change Password</h1>
      </div>

      <div class="page-content  container-fluid">

        <!-- Panel Form Elements -->
        <div class="panel">
        <form method="post" action="changeFarmerPassword.php">
        <div class="panel-body">
          <?php
              if ($error==1) {
              ?>
              <div class="d-block small" style="color: red">New Password and Confirm Password should be same</div>
              <?php
              }
              elseif ($error==4)
              { ?>
              <div class="d-block small" style="color: red">Invalid Old Password</div>
              <?php
              }
              elseif ($error==2)
              { ?>
              <div class="d-block small" style="color: red">Password should contain only alphanumeric characters (at least 8, max 20)</div>
              <?php 
              }
              elseif ($error==3)
              { ?>
              <div class="d-block small" style="color: green">Password Changed</div>
              <?php } ?>
                  <div class="form-group form-material floating" data-plugin="formMaterial">
                    <input type="password" class="form-control" name="curPass" required/>
                    <label class="floating-label">Current Password</label>
                  </div>
                  <div class="form-group form-material floating" data-plugin="formMaterial">
                    <input type="password" class="form-control" name="newPass" required/>
                    <label class="floating-label">New Password</label>
                  </div>
                  <div class="form-group form-material floating" data-plugin="formMaterial">
                    <input type="password" class="form-control" name="confPass" required/>
                    <label class="floating-label">Confirm New Password</label>
                  </div>
                  <div class="row">
                  <div class="col-sm-12 col-md-4 col-xl-2">
                      <div class="example">
                        <ul class="list-unstyled">
                          <li class="mb-20">
                            <button type="reset" class="btn btn-block btn-primary">Reset</button>
                          </li>
                        </ul>
                      </div>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-2">
                      <div class="example">
                        <ul class="list-unstyled">
                          <li class="mb-20">
                            <button type="submit" class="btn btn-block btn-primary" name="submit" id="submit">Change Password</button>
                          </li>
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>
            </form>
        </div>
      </div>
    </div>
    <!-- End Page -->


    <!-- Footer -->
    <footer class="site-footer">
      <div class="site-footer-legal">© 2018</div>
      <div class="site-footer-right">
        Crafted with <i class="red-600 wb wb-heart"></i> by ISSC studets</a>
      </div>
    </footer>
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
        <script src="../../../global/vendor/ladda/spin.min.js"></script>
        <script src="../../../global/vendor/ladda/ladda.min.js"></script>
    
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
        <script src="../../../global/js/Plugin/input-group-file.js"></script>
        <script src="../../../global/js/Plugin/panel.js"></script>
        <script src="../../assets/examples/js/uikit/panel-actions.js"></script>
        <script src="../../../global/js/Plugin/material.js"></script>
        <script src="../../../global/js/Plugin/loading-button.js"></script>
        <script src="../../../global/js/Plugin/more-button.js"></script>
        <script src="../../../global/js/Plugin/ladda.js"></script>
    
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
