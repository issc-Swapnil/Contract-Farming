<?php
require '../config.php';
if(!isset($_SESSION["userId"]))
  header("Location: ../common/login.php");
if(isset($_POST['reject']))
{
  $id=$_POST['ids'];
  for($i=0;$i<sizeof($id);$i++)
  {
  $query="update farmer set status=2 where fId=".$id[$i];
  $conn->query($query);
  }
}
elseif(isset($_POST['accept']))
{
  $id=$_POST['ids'];
  for($i=0;$i<sizeof($id);$i++)
  {
  $query="update farmer set status=1 where fId=".$id[$i];
  $conn->query($query);
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
    
    <title>Farmer Registrations</title>
    
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

    <link rel="stylesheet" href="../../../global/vendor/datatables.net-bs4/dataTables.bootstrap4.css">
          <link rel="stylesheet" href="../../../global/vendor/datatables.net-fixedheader-bs4/dataTables.fixedheader.bootstrap4.css">
          <link rel="stylesheet" href="../../../global/vendor/datatables.net-fixedcolumns-bs4/dataTables.fixedcolumns.bootstrap4.css">
          <link rel="stylesheet" href="../../../global/vendor/datatables.net-rowgroup-bs4/dataTables.rowgroup.bootstrap4.css">
          <link rel="stylesheet" href="../../../global/vendor/datatables.net-scroller-bs4/dataTables.scroller.bootstrap4.css">
          <link rel="stylesheet" href="../../../global/vendor/datatables.net-select-bs4/dataTables.select.bootstrap4.css">
          <link rel="stylesheet" href="../../../global/vendor/datatables.net-responsive-bs4/dataTables.responsive.bootstrap4.css">
          <link rel="stylesheet" href="../../../global/vendor/datatables.net-buttons-bs4/dataTables.buttons.bootstrap4.css">
          <link rel="stylesheet" href="../../assets/examples/css/tables/datatable.css">
    
    
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
                $query="select count(message) from notification where userId is null AND status=0";
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
                  $query="select * from notification where userId is null order by notifId desc";
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
                  <a class="dropdown-item" href="adminNotifications.php" role="menuitem">
                    All notifications
                  </a>
                </div>
              </div>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link navbar-avatar" data-toggle="dropdown" href="#" aria-expanded="false"
                data-animation="scale-up" role="button">
                <span class="avatar avatar-online">
                  <img src="../farmer/profile/user.png" alt="...">
                  <i></i>
                </span>
              </a>
              <div class="dropdown-menu" role="menu">
                <a class="dropdown-item" href="viewAdminProfile.php" role="menuitem"><i class="icon wb-user" aria-hidden="true"></i> View Profile</a>
                <a class="dropdown-item" href="editAdminProfile.php" role="menuitem"><i class="icon wb-pencil" aria-hidden="true"></i> Edit Profile</a>
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
          <a href="dashboardAdmin.php">
            <i class="site-menu-icon wb-dashboard" aria-hidden="true"></i>
            <span class="site-menu-title">Dashboard</span>
          </a>
        </li>
        <li class="site-menu-item active">
          <a href="javascript:void(0)">
            <i class="site-menu-icon wb-user-add" aria-hidden="true"></i>
            <span class="site-menu-title">Farmer Registrations</span>
          </a>
        </li>
        <li class="site-menu-item">
          <a href="newRequirement.php">
            <i class="site-menu-icon wb-clipboard" aria-hidden="true"></i>
            <span class="site-menu-title">New Requirement</span>
          </a>
        </li>
        <li class="site-menu-item">
          <a href="viewFarmerRequests.php">
            <i class="site-menu-icon wb-inbox" aria-hidden="true"></i>
            <span class="site-menu-title">Contract Requests</span>
          </a>
        </li>
        <li class="site-menu-item">
          <a href="viewFarmerProfiles.php">
            <i class="site-menu-icon wb-users" aria-hidden="true"></i>
            <span class="site-menu-title">Farmer Profiles</span>
          </a>
        </li>
        <li class="site-menu-item">
          <a href="viewCurrentRequirements.php">
            <i class="site-menu-icon wb-bookmark" aria-hidden="true"></i>
            <span class="site-menu-title">Pending Requirements</span>
          </a>
        </li>
        <li class="site-menu-item">
          <a href="viewFinishedRequirements.php">
            <i class="site-menu-icon wb-book" aria-hidden="true"></i>
            <span class="site-menu-title">Finished Requirements</span>
          </a>
        </li>
        <li class="site-menu-item">
          <a href="viewOfferedContracts.php">
            <i class="site-menu-icon wb-order" aria-hidden="true"></i>
            <span class="site-menu-title">Offered Contracts</span>
          </a>
        </li>
        <li class="site-menu-item">
          <a href="viewActiveContracts.php">
            <i class="site-menu-icon wb-library" aria-hidden="true"></i>
            <span class="site-menu-title">Active Contracts</span>
          </a>
        </li>
        <li class="site-menu-item">
          <a href="viewCompletedContracts.php">
            <i class="site-menu-icon wb-check" aria-hidden="true"></i>
            <span class="site-menu-title">Completed Contracts</span>
          </a>
        </li>
        <li class="site-menu-item has-sub">
          <a href="javascript:void(0)">
            <i class="site-menu-icon wb-add-file" aria-hidden="true"></i>
            <span class="site-menu-title">Manage Crop &amp; Supply</span>
            <span class="site-menu-arrow"></span>
          </a>
          <ul class="site-menu-sub">
            <li class="site-menu-item">
              <a href="cropMaster.php">
                <span class="site-menu-title">Crop Master</span>
              </a>
            </li>
            <li class="site-menu-item">
              <a href="supplyMaster.php">
                <span class="site-menu-title">Supply Master</span>
              </a>
            </li>
            <li class="site-menu-item">
              <a href="addCropSupply.php">
                <span class="site-menu-title">Recommended Supply/Crop</span>
              </a>
            </li>
          </ul>
        </li>
      </div>
    </div>
  </div>

    <!-- Page -->
       <div class="page">
       <div class="page-header">
        <h1 class="page-title">Fulfilled Requirements</h1>
        </div>
      <div class="page-content container-fluid">
      <form method="post" action="viewFarmerRegistrations.php">
      <div class="panel">
          <div class="panel-body">
          <?php
          $query="select * from farmer where status=0";
          $result=$conn->query($query);
          if($row = $result->fetch_assoc())
          {
            ?>
              <table class="table table-hover dataTable table-striped w-full" data-plugin="dataTable">
              <thead>
                <tr>
                  <th>Select</th>
                  <th>Name</th>
                  <th>Village</th>
                  <th>Region</th>
                  <th>State</th>
                  <th>Land Area</th>
                  <th>Phone No</th>
                  <th>Email</th>
                </tr>
              </thead>
              <tbody>
              <?php
              do
            {
              ?>
            <tr>
              <td><input type="checkbox" name="ids[]" value="<?php echo $row['fId'];?>" /></td>
              <td><?php echo $row['fName'];?></td>
              <td><?php echo $row['village'];?></td>
              <td><?php echo $row['region'];?></td>
              <td><?php echo $row['state'];?></td>
              <td><?php echo $row['landArea'];?></td>
              <td><?php echo $row['phone'];?></td>
              <td><?php echo $row['email'];?></td>
            </tr>
                <?php
                }while($row = $result->fetch_assoc());
                ?>
                </tbody>
                </table>
                <div class="row">
              <div class="col-sm-12 col-md-4 col-xl-4">
              </div>
                    <div class="col-sm-12 col-md-4 col-xl-2">
                      <div class="example">
                        <ul class="list-unstyled">
                          <li class="mb-20">
                            <input type="submit" class="btn btn-block btn-primary" name="accept" value="Accept" />
                          </li>
                        </ul>
                      </div>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-2">
                      <div class="example">
                        <ul class="list-unstyled">
                          <li class="mb-20">
                            <input type="submit" class="btn btn-block btn-primary" name="reject" value="Reject" />
                          </li>
                        </ul>
                      </div>
                    </div>
                    </div>
                  </div>
          <?php
          }
          else
          {
          ?>
            <div >
                  No new registrations!
            </div>
          <?php
          }
          ?>
          </div>
          </div>
        </form>
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

           <script src="../../../global/vendor/datatables.net/jquery.dataTables.js"></script>
        <script src="../../../global/vendor/datatables.net-bs4/dataTables.bootstrap4.js"></script>
        <script src="../../../global/vendor/datatables.net-fixedheader/dataTables.fixedHeader.js"></script>
        <script src="../../../global/vendor/datatables.net-fixedcolumns/dataTables.fixedColumns.js"></script>
        <script src="../../../global/vendor/datatables.net-rowgroup/dataTables.rowGroup.js"></script>
        <script src="../../../global/vendor/datatables.net-scroller/dataTables.scroller.js"></script>
        <script src="../../../global/vendor/datatables.net-responsive/dataTables.responsive.js"></script>
        <script src="../../../global/vendor/datatables.net-responsive-bs4/responsive.bootstrap4.js"></script>
        <script src="../../../global/vendor/datatables.net-buttons/dataTables.buttons.js"></script>
        <script src="../../../global/vendor/datatables.net-buttons/buttons.html5.js"></script>
        <script src="../../../global/vendor/datatables.net-buttons/buttons.flash.js"></script>
        <script src="../../../global/vendor/datatables.net-buttons/buttons.print.js"></script>
        <script src="../../../global/vendor/datatables.net-buttons/buttons.colVis.js"></script>
        <script src="../../../global/vendor/datatables.net-buttons-bs4/buttons.bootstrap4.js"></script>
    
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

            <script src="../../../global/js/Plugin/datatables.js"></script>
        <script src="../../assets/examples/js/tables/datatable.js"></script>
    
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
