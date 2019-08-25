<?php
require '../config.php';
if(!isset($_SESSION["userId"]))
  header("Location: login.php");
$error=0;
if(isset($_POST['add']))
{
$name = $_REQUEST['name'];
$cost = $_REQUEST['cost'];
$category = $_REQUEST['category'];
$type = $_REQUEST['type'];
$area=$_REQUEST['area'];
$sql="INSERT INTO crop(cropName,costPerTon,cropCategory,cropType,areaPerTon)VALUES('$name','$cost','$category','$type','$area')";
if(mysqli_query($conn,$sql))
{
    $id = mysqli_insert_id($conn);
    $i=1;
    foreach($_POST['instr'] as $instruction)
    {
      if($instruction != null)
      {
        $sql="INSERT INTO schedule VALUES('$id','$i','$instruction')";
        mysqli_query($conn,$sql);
      }
      $i++;
    }
    $error=1;
}
else
  $error=2;
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
    
    <title>Crop Master</title>
    
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
        <li class="site-menu-item">
          <a href="viewFarmerRegistrations.php">
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
        <li class="site-menu-item has-sub active open">
          <a href="javascript:void(0)">
            <i class="site-menu-icon wb-add-file" aria-hidden="true"></i>
            <span class="site-menu-title">Manage Crop &amp; Supply</span>
            <span class="site-menu-arrow"></span>
          </a>
          <ul class="site-menu-sub">
            <li class="site-menu-item active">
              <a href="javascript:void(0)">
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
        <h1 class="page-title">Crop Master</h1>
      </div>

      <div class="page-content  container-fluid">
      <form method="post" action="cropMaster.php" id="myForm">
        <!-- Panel Form Elements -->
        
          <?php
            if(isset($_POST['submit']))
            {
              $i=2;
              ?>
                  <input type="hidden" name="name" value="<?php echo $_POST['name']; ?>">
                  <input type="hidden" name="category" value="<?php echo $_POST['category']; ?>">
                  <input type="hidden" name="type" value="<?php echo $_POST['type']; ?>">
                  <input type="hidden" name="cost" value="<?php echo $_POST['cost']; ?>">
                  <input type="hidden" name="area" value="<?php echo $_POST['area']; ?>">
                  <div class="panel">
        
                    <div class="panel-body">
                  <table cellpadding="5" width="100%">
                    <thead style="color: #455A64; ">
                    <tr>
                      <th>Day</th>
                      <th>Instruction</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                      <td>1</td>
                      <td><textarea class="form-control" name="instr[]" form="myForm" rows="1" required>Plow Ground</textarea></td>
                    </tr>
                <?php
                while($i<$_POST['days'])
                {
                  ?>
                    <tr>
                      <td><?php echo $i; ?></td>
                      <td><textarea class="form-control" name="instr[]" form="myForm" rows="1"></textarea></td>
                    </tr>
                  <?php
                  $i++;
                }
                ?>

                    <tr>
                      <td><?php echo $i; ?></td>
                      <td><textarea class="form-control" name="instr[]" form="myForm" rows="1" required>Harvest & Deliver</textarea></td>
                    </tr>
                    </tbody>
                  </table>
                  <div class="row">
                    <div class="col-sm-12 col-md-4 col-xl-2">
                      <div class="example">
                        <ul class="list-unstyled">
                          <li class="mb-20">
                            <input type="submit" class="btn btn-block btn-primary" name="add" value="Add" />
                          </li>
                        </ul>
                      </div>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-2">
                      <div class="example">
                        <ul class="list-unstyled">
                          <li class="mb-20">
                            <input type="reset" class="btn btn-block btn-primary" value="Reset" />
                          </li>
                        </ul>
                      </div>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-2">
                      <div class="example">
                        <ul class="list-unstyled">
                          <li class="mb-20">
                            <input type="submit" class="btn btn-block btn-primary" name="back" value="Go Back" />
                          </li>
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
              <?php
            }
            else
            {
              ?>
              <div class="col-12">
            <!-- Example Card Columns -->
            <div class="example-wrap">
              <div class="card-columns">
              <div class="card card-block">
          <h4 class="card-title">Add New Crop</h4>
          <?php
          if ($error==1)
              {
              ?>
              <div class="d-block small" style="color: green">Crop Added</div>
              <?php
              }
              elseif ($error==2)
              {
              ?>
              <div class="d-block small" style="color: red">Error while adding crop</div>
              <?php
              }
            ?>
              <div class="form-group form-material floating" data-plugin="formMaterial">
                    <input type="text" class="form-control" name="name" required/>
                    <label class="floating-label">Crop Name</label>
                  </div>
              <div class="form-group form-material floating" data-plugin="formMaterial">
                    <input type="text" class="form-control" name="type" required/>
                    <label class="floating-label">Crop Type</label>
                  </div>    
              <div class="form-group form-material floating" data-plugin="formMaterial">
                    <select class="form-control" name="category" onchange="if(this.options[this.selectedIndex].value=='customOption'){
              toggleField(this,this.nextSibling);
              this.selectedIndex='0';
          }" required>
                      <option></option>
                      <option value="Cereal">Cereal</option>
                      <option value="Fruit">Fruit</option>
                      <option value="Vegetable">Vegetable</option>
                      <option value="customOption">Other</option>
                    </select><input name="category" class="form-control" style="display:none;" disabled="disabled" onblur="if(this.value==''){toggleField(this,this.previousSibling);}"/ required>
                    <label class="floating-label">Crop Category</label>
                  </div>
              <div class="form-group form-material floating" data-plugin="formMaterial">
                    <input type="number" class="form-control" name="cost" min="1" value="10000" required/>
                    <label class="floating-label">Cost per Ton(Rs.)</label>
                  </div>
              <div class="form-group form-material floating" data-plugin="formMaterial">
                    <input type="number" class="form-control" name="area" min="1" value="10" required/>
                    <label class="floating-label">Area per Ton(Acre)</label>
                  </div>
              <div class="form-group form-material floating" data-plugin="formMaterial">
                    <input type="number" class="form-control" name="days" min="15" value="15" required/>
                    <label class="floating-label">Number of days for Harvest</label>
                  </div>
                  <center>
                    <div class="col-md-6 col-lg-6">
                      <div class="example">
                        <ul class="list-unstyled">
                          <li class="mb-20">
                            <input type="submit" class="btn btn-block btn-primary" name="submit" value="Add Schedule" />
                          </li>
                        </ul>
                      </div>
                    </div>
                  </center>
            </div>
      <?php
      $query="select * from crop order by cropName";
      $result=$conn->query($query);
      while($row = $result->fetch_assoc())
      {
      ?>
        <div class="card card-block">
          <h4 class="card-title"><?php echo $row['cropName']; ?></h4>
              <table class="tablesaw" cellpadding="5">
              <tbody valign="top">
                <tr>
                  <td><strong>Crop Type</strong></td>
                  <td><?php echo $row['cropType']; ?></td>
                </tr>
                <tr>
                  <td><strong>Crop Category</strong></td>
                  <td><?php echo $row['cropCategory']; ?></td>
                </tr>
                <tr>
                  <td><strong>Cost Per Ton</strong></td>
                  <td>Rs. <?php echo $row['costPerTon']; ?></td>
                </tr>
                <tr>
                  <td><strong>Land Area Per Ton</strong></td>
                  <td><?php echo $row['areaPerTon']; ?> Acres</td>
                </tr>
              </tbody>
              </table>
              <?php
              $query2="select * from schedule where cropId=".$row['cropId'];
              $result2=$conn->query($query2);
              if($row2 = $result2->fetch_assoc())
              {
              ?>
              <table class="tablesaw" cellpadding="5">
              <tbody valign="top">
                <tr>
                  <td colspan="2"><strong>Crop Schedule:</strong></td>
                </tr>
                <tr>
                  <td style="color: #455A64; "><u>Day</u></td>
                  <td style="color: #455A64; "><u>Instruction</u></td>
                </tr>
              <?php
              do
              {
              ?>
                <tr>
                  <td><?php echo $row2['day']; ?></td>
                  <td><?php echo $row2['instruction']; ?></td>
                </tr>
              <?php
              }while($row2 = $result2->fetch_assoc());
              ?>
              </tbody>
              </table>
              <?php
              }
              $query2="select * from crop_supply where cropId=".$row['cropId'];
              $result2=$conn->query($query2);
              if($row2 = $result2->fetch_assoc())
              {
              ?>
              <table class="tablesaw" cellpadding="5">
              <tbody valign="top">
                <tr>
                  <td colspan="2"><strong>Recommended Supply:</strong></td>
                </tr>
                <tr>
                  <td style="color: #455A64; "><u>Name</u></td>
                  <td style="color: #455A64; "><u>Units/Ton</u></td>
                </tr>
              <?php
              do
              {
                $query3="select supplyName from supply where supplyId=".$row2['supplyId'];
              $result3=$conn->query($query3);
              $row3 = $result3->fetch_assoc();
              ?>
                <tr>
                  <td><?php echo $row3['supplyName']; ?></td>
                  <td><?php echo $row2['supplyQuantity']; ?>/<?php echo $row2['cropQuantity']; ?></td>
                </tr>
              <?php
              }while($row2 = $result2->fetch_assoc());
              }
              ?>
              </tbody>
              </table>
            </div>
        <?php } ?>
        <!-- End Panel Form Elements -->
</div>
            </div>
            <!-- End Example Card Columns -->
          </div>
        <?php } ?>
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
    
    <script >
function toggleField(hideObj,showObj){
  hideObj.disabled=true;        
  hideObj.style.display='none';
  showObj.disabled=false;   
  showObj.style.display='inline';
  showObj.focus();
}
</script>

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
