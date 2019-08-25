<?php
require '../config.php';
if(!isset($_SESSION["userId"]))
  header("Location: ../common/login.php");
$_SESSION['error']="Logged In";
if(isset($_POST['accept']))
{
  $tom=date("Y-m-d", strtotime("+ 1 day"));
  $query="update contract set status=4,startDate='".$tom."' where contractId=".$_POST['radioInput'];
  $conn->query($query);
  $query="update contract set status=2 where fId=".$_SESSION['userId']." AND status=1";
  $conn->query($query);
  $query="select reqId,quantity from contract where contractId=".$_POST['radioInput'];
  $result=$conn->query($query);
  $row = $result->fetch_assoc();
  $query1="update requirement set remainingQuantity=remainingQuantity-".$row['quantity']." where reqId=".$row['reqId'];
  $conn->query($query1);
  $query2="select remainingQuantity,cropId from requirement where reqId=".$row['reqId'];
  $result2=$conn->query($query2);
  $row2 = $result2->fetch_assoc();
  if($row2['remainingQuantity']==0)
    {
      $query1="update requirement set status=1 where reqId=".$row['reqId'];
      $conn->query($query1);
      $query1="update contract set status=3 where status=0 AND reqId=".$row['reqId'];
      $conn->query($query1);
      $query1="update contract set status=3 where status=1 AND reqId=".$row['reqId'];
      $conn->query($query1);
    }
  $query3="select max(day) from schedule where cropId=".$row2['cropId'];
  $result3=$conn->query($query3);
  $row3 = $result3->fetch_assoc();
  $end=date("Y-m-d", strtotime("+ ".($row3['max(day)']+1)." day"));
  $query1="update contract set endDate='".$end."' where contractId=".$_POST['radioInput'];
  $conn->query($query1);
}
elseif(isset($_POST['reject']))
{
  $query="update contract set status=2 where contractId=".$_POST['radioInput'];
  $conn->query($query);
}
elseif(isset($_POST['cancel']))
{
  $query="update contract set status=2 where contractId=".$_POST['id'];
  $conn->query($query);
}
elseif(isset($_POST['request']))
{
  $query="select remainingQuantity,cropId from requirement where reqId=".$_POST['radioInput'];
  $result=$conn->query($query);
  $row = $result->fetch_assoc();
  $query1="select areaPerTon from crop where cropId=".$row['cropId'];
  $result1=$conn->query($query1);
  $row1 = $result1->fetch_assoc();
  $reqArea=$row['remainingQuantity']*$row1['areaPerTon'];
  $query2="select landArea from farmer where fId=".$_SESSION['userId'];
  $result2=$conn->query($query2);
  $row2 = $result2->fetch_assoc();
  if($row2['landArea']<$reqArea)
    $area=$row2['landArea'];
  else
    $area=$reqArea;
  $quantity=ceil($area/$row1['areaPerTon']);
  $query3="insert into contract(reqId,fId,landArea,quantity,creationDate,status) values (".$_POST['radioInput'].",".$_SESSION['userId'].",".$area.",".$quantity.",'".date('Y-m-d')."',0)";
  if(!($conn->query($query3)))
    echo "<script type='text/javascript'>window.alert('Sorry, cannot make this request at this time. Please try again later')</script>";
  else
  {
  $id = mysqli_insert_id($conn);
  $query4="select * from crop_supply where cropId=".$row['cropId'];
  $result4=$conn->query($query4);
  while($row4 = $result4->fetch_assoc())
  {
  $supQuantity=ceil(($row4['supplyQuantity']*$quantity)/$row4['cropQuantity']);
  $query5="insert into contract_supply values (".$id.",".$row4['supplyId'].",".$supQuantity.")";
  $conn->query($query5);
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
    
    <title>Farmer Dashboard</title>
    
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
        <link rel="stylesheet" href="../../assets/examples/css/tables/basic.css">
        <link rel="stylesheet" href="../../../global/vendor/aspieprogress/asPieProgress.css">
        <link rel="stylesheet" href="../../assets/examples/css/charts/pie-progress.css">
        <link rel="stylesheet" href="../../assets/examples/css/layouts/headers.css">
        <link rel="stylesheet" href="../../../global/vendor/tablesaw/tablesaw.css">
    
    
    <!-- Fonts -->
        <link rel="stylesheet" href="../../../global/fonts/font-awesome/font-awesome.css">
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
                <a class="dropdown-item" href="changeFarmerPassword.php" role="menuitem"><i class="icon wb-settings" aria-hidden="true"></i> Change Password</a>
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
        <li class="site-menu-item active">
          <a href="javascript:void(0)">
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
    <form method="post" action="dashboardFarmer.php">
      <?php
      $query="select * from contract where fId=".$_SESSION['userId']." AND status=4";
      $result=$conn->query($query);
      if($row = $result->fetch_assoc())
      {
        $query1="select * from requirement where reqId=".$row['reqId'];
        $result1=$conn->query($query1);
        $row1 = $result1->fetch_assoc();
        $query2="select costPerTon,cropName from crop where cropId=".$row1['cropId'];
        $result2=$conn->query($query2);
        $row2 = $result2->fetch_assoc();
                  $query3="select max(day) from schedule where cropId=".$row1['cropId'];
                  $result3=$conn->query($query3);
                  $row3 = $result3->fetch_assoc();
                  $date1 = new DateTime($row['startDate']);
                  $date2 = new DateTime(date('Y-m-d'));
                  $diff = $date2->diff($date1)->format("%a");
                  $remain=$row3['max(day)']-$diff;
                  $per=ceil(($diff*100)/$row3['max(day)']);
      ?>
      <div class="page-header page-header-bordered">
        <h1 class="page-title">Active Contract</h1>
        <div class="page-header-actions">
                    <div class="counter inline-block text-right mr-20 hidden-sm-down">
                      <div class="counter-label">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
                    </div>
                    
                    
                    <div class="pie-progress pie-progress-sm" data-plugin="pieProgress" data-barcolor="#28d17c"
                      data-size="100" data-barsize="4" data-goal="<?php echo $per; ?>" aria-valuenow="<?php echo $per; ?>"
                      role="progressbar">
                      <div class="pie-progress-content">
                        <div class="pie-progress-number"><?php echo $per; ?>%</div>
                      </div>
                    </div>

                    <div class="counter inline-block text-right mr-20 hidden-sm-down">
                      <div class="counter-label">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
                    </div>

                    </div>
           
      </div>
        <div class="page-content  container-fluid">

        <div class="panel">
          <div class="panel-body">
              <?php
              $query3="select * from schedule where cropId=".$row1['cropId'];
              $result3=$conn->query($query3);
              if($row3 = $result3->fetch_assoc())
              {
              ?>
                <div class="example-wrap">
                 <h4 class="example-title">Crop Schedule</h4>
                 <table class="table table-hover">
                 <tbody>
              <?php
              do
              {
              ?>
              <tr>
                  <td width="75">Day <?php echo $row3['day']; ?> : </td>
                  <td><?php echo $row3['instruction']; ?></td>
              </tr>
              <?php
              }while($row3 = $result3->fetch_assoc());
              ?>
              </tbody>
              </table>
              </div>
              <?php
              }
              ?>
          </div>
          </div>

        <div class="panel">
          <div class="panel-heading">
            <h3 class="panel-title">Information</h3>
            <div class="panel-actions panel-actions-keep">
              <a class="panel-action icon wb-minus" data-toggle="panel-collapse" aria-hidden="true"></a>
            </div>
          </div>
          <div class="panel-body">
            <div class="row">
              <div class="col-md-6 col-lg-2">
              <div class="example-wrap">
                  <h4 class="example-title">Start Date</h4>
                  <p class="form-control-plaintext"><?php echo $row['startDate']; ?></p>
                  </div>
              </div>
              <div class="col-md-6 col-lg-2">
              <div class="example-wrap">
                  <h4 class="example-title">Crop</h4>
                  <p class="form-control-plaintext"><?php echo $row2['cropName']; ?></p>
                  </div>
              </div>
              <div class="col-md-6 col-lg-2">
              <div class="example-wrap">
                  <h4 class="example-title">Quantity to Produce</h4>
                  <p class="form-control-plaintext"><?php echo $row['quantity']; ?> Ton</p>
                  </div>
              </div>
              <div class="col-md-6 col-lg-2">
              <div class="example-wrap">
                  <h4 class="example-title">Land Area Used</h4>
                  <p class="form-control-plaintext"><?php echo $row['landArea']; ?> Acres</p>
                  </div>
              </div>
              <div class="col-md-6 col-lg-2">
              <div class="example-wrap">
                  <h4 class="example-title">Current Rating</h4>
                  <p class="form-control-plaintext"><?php echo $row['rating']; ?></p>
                  </div>
              </div>
              <div class="col-md-6 col-lg-2">
              <div class="example-wrap">
                  <h4 class="example-title">End Date</h4>
                  <p class="form-control-plaintext"><?php echo $row['endDate']; ?></p>
                  </div>
              </div>
              <?php
              $query3="select * from contract_supply where contractId=".$row['contractId'];
              $result3=$conn->query($query3);
              if($row3 = $result3->fetch_assoc())
              {
                $query4="select supplyName from supply where supplyId=".$row3['supplyId'];
              $result4=$conn->query($query4);
              $row4 = $result4->fetch_assoc();

              ?>
          <div class="col-md-6 col-lg-12">
                <div class="example-wrap">
                 <h4 class="example-title">Supply Provided</h4>
                 <p class="form-control-plaintext">
              <?php
              echo $row3['supplyQuantity']." units of ".$row4['supplyName'];
              while($row3 = $result3->fetch_assoc())
              {
              $query4="select supplyName from supply where supplyId=".$row3['supplyId'];
              $result4=$conn->query($query4);
              $row4 = $result4->fetch_assoc();
              echo ", ".$row3['supplyQuantity']." units of ".$row4['supplyName'];
              }
              ?>
              </p>
          </div>
          
          </div>
          <?php } ?>
              </div>
              </div>
            </div>
          

          <div class="panel">
          <h3 class="panel-title">Costing</h3>
          <div class="panel-body">
              <table class="tablesaw">
              <thead style="color: #455A64; ">
                <tr>
                  <th>Amenities</th>
                  <th>Amount (Rs.)</th>
                  <th>Quantity</th>
                  <th>Total (Rs.)</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>Basic Cost</td>
                  <td><?php echo $row1['basicCost']; ?></td>
                  <td>-</td>
                  <td><?php echo $row1['basicCost']; ?></td>
                </tr>
              </tbody>
              <tbody>
                <tr>
                  <td>Land Cost</td>
                  <td><?php echo $row1['landCost']; ?> /Acre</td>
                  <td><?php echo $row['landArea']; ?> Acre</td>
                  <td><?php echo $row1['landCost']*$row['landArea']; ?></td>
                </tr>
              </tbody>
              <tbody>
                <tr>
                  <td>Crop Cost</td>
                  <td><?php echo $row2['costPerTon']; ?> /Ton</td>
                  <td><?php echo $row['quantity']; ?> Ton</td>
                  <td><?php echo $row2['costPerTon']*$row['quantity']; ?></td>
                </tr>
              </tbody>
              <tbody>
                <tr>
                  <td colspan="4"></td>
                </tr>
                </tbody>
                <tbody bgcolor="#f5f5f5">
                <tr>
                  <td>Total</td>
                  <td colspan="2"></td>
                  <td>
                  <?php
                  $total1=$row1['basicCost']+($row1['landCost']*$row['landArea'])+($row2['costPerTon']*$row['quantity']);
                  echo $total1;
                  ?>
                  </td>
                </tr>
              </tbody>
              <?php
              $query3="select * from contract_supply where contractId=".$row['contractId'];
              $result3=$conn->query($query3);
              $total2=0;
              if($row3 = $result3->fetch_assoc())
              {
              ?>
              <tbody>
                <tr>
                  <td colspan="4"></td>
                </tr>
                </tbody>
              <thead style="color: #455A64; ">
                <tr>
                  <th>Supplies</th>
                  <th>Amount (Rs.)</th>
                  <th>Quantity</th>
                  <th>Total (Rs.)</th>
                </tr>
              </thead>
              <?php
              do
              {
              $query4="select supplyName,costPerUnit from supply where supplyId=".$row3['supplyId'];
              $result4=$conn->query($query4);
              $row4 = $result4->fetch_assoc();
              $total2 = $total2 + ($row4['costPerUnit']*$row3['supplyQuantity']);
              ?>
                <tbody>
                <tr>
                  <td><?php echo $row4['supplyName']; ?></td>
                  <td><?php echo $row4['costPerUnit']; ?></td>
                  <td><?php echo $row3['supplyQuantity']; ?></td>
                  <td><?php echo $row4['costPerUnit']*$row3['supplyQuantity']; ?></td>
                </tr>
                </tbody>
                <?php }while($row3 = $result3->fetch_assoc()); ?>

                <tbody>
                <tr>
                  <td colspan="4"></td>
                </tr>
                </tbody>
                <tbody bgcolor="#f5f5f5">
                <tr>
                  <td>Total</td>
                  <td colspan="2"></td>
                  <td><?php echo $total2; ?></td>
                </tr>
                </tbody>
              <tbody>
                <tr>
                  <td colspan="4"></td>
                </tr>
                </tbody>
                <thead style="color: #455A64; ">
                <tr>
                  <th>Total</th>
                  <th colspan="2"></th>
                  <th>Total (Rs.)</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>Amenities</td>
                  <td></td>
                  <td><strong>+</strong></td>
                  <td><?php echo $total1; ?></td>
                </tr>
                </tbody>
              <tbody>
                <tr>
                  <td>Supplies</td>
                  <td></td>
                  <td><strong>-</strong></td>
                  <td><?php echo $total2; ?></td>
                </tr>
                </tbody>
                <tbody>
                <tr>
                  <td colspan="4"></td>
                </tr>
                </tbody>
              <tbody bgcolor="#e0e0e0">
                <tr>
                  <td>Payable Amount</td>
                  <td colspan="2"></td>
                  <td><?php echo $total1-$total2; ?></td>
                </tr>
              </tbody>?>
                
              <?php } ?>
              </table>
              </div>
          </div>
          </div>
        <!-- End Panel Form Elements -->
      </div>
      <?php
      }
      else
      {
        $query="select * from contract where fId=".$_SESSION['userId']." AND status=0";
        $result=$conn->query($query);
        if($row = $result->fetch_assoc())
        {
          ?>
          <div class="page-header">
          <h1 class="page-title">There is a pending request from you. Please wait for the admin to respond.</h1>
          </div>

          <div class="page-content  container-fluid">
          <div class="panel">
          <div class="panel-heading">
            <h3 class="panel-title">Contract Request Details</h3>
          </div>
          <div class="panel-body">
          <?php
          $query1="select * from requirement where reqId=".$row['reqId'];
          $result1=$conn->query($query1);
          $row1 = $result1->fetch_assoc();
          $query2="select cropName from crop where cropId=".$row1['cropId'];
          $result2=$conn->query($query2);
          $row2 = $result2->fetch_assoc();
          ?>
            <div class="row">
              <div class="col-md-6 col-lg-2">
                <div class="example-wrap">
                  <h4 class="example-title">Crop</h4>
                  <p class="form-control-plaintext"><?php echo $row2['cropName']; ?></p>
                </div>
              </div>
              <div class="col-md-6 col-lg-2">
              <div class="example-wrap">
                  <h4 class="example-title">Land Area</h4>
                  <p class="form-control-plaintext"><?php echo $row['landArea']; ?> Acres</p>
                  </div>
              </div>
              <div class="col-md-6 col-lg-2">
              <div class="example-wrap">
                  <h4 class="example-title">Expected Production</h4>
                  <p class="form-control-plaintext"><?php echo $row['quantity']; ?> Ton</p>
                  </div>
              </div>
              <div class="col-md-6 col-lg-2">
              <div class="example-wrap">
                  <h4 class="example-title">Basic Cost</h4>
                  <p class="form-control-plaintext">Rs. <?php echo $row1['basicCost']; ?></p>
                  </div>
              </div>
              <div class="col-md-6 col-lg-4">
              <div class="example-wrap">
                  <h4 class="example-title">Land Cost</h4>
                  <p class="form-control-plaintext">Rs. <?php echo $row1['landCost']; ?> Per Acre</p>
                  </div>
              </div>
              <?php
              $query3="select * from contract_supply where contractId=".$row['contractId'];
              $result3=$conn->query($query3);
              if($row3 = $result3->fetch_assoc())
              {
              ?>
                <div class="col-md-6 col-lg-6">
                <div class="example-wrap">
                 <h4 class="example-title">Initial Supply</h4>
              <?php
              do
              {
                $query4="select supplyName from supply where supplyId=".$row3['supplyId'];
                $result4=$conn->query($query4);
                $row4 = $result4->fetch_assoc();
              ?>
                <p class="form-control-plaintext"><?php echo $row3['supplyQuantity']; ?> units of <?php echo $row4['supplyName']; ?></p>
              <?php
              }while($row3 = $result3->fetch_assoc());
              ?>
              </div>
              </div>
              <?php
              }
              ?>
            </div>
            <input type="hidden" name="id" value="<?php echo $row['contractId']?>">
            <div class="row">
            <div class="col-sm-12 col-md-4 col-xl-2">
                      <div class="example">
                        <ul class="list-unstyled">
                          <li class="mb-20">
                            <input type="submit" class="btn btn-block btn-primary" name="cancel" value="Cancel Request" />
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
          <div class="page-header">
            <h1 class="page-title">Form a contract, TODAY!</h1>
          </div>
          <div class="page-content container-fluid">
          
              <div class="panel nav-tabs-horizontal nav-tabs-inverse" data-plugin="tabs">
                <div class="panel-heading panel-heading-tab">
                  <ul class="nav nav-tabs nav-tabs-solid" role="tablist">
                    <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#panelTab1"
                      aria-controls="panelTab1" role="tab" aria-expanded="true">New Requirements</a></li>
                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#panelTab2" aria-controls="panelTab2"
                      role="tab">Requests from Admin</a></li>
                  </ul>
                </div>
                <div class="panel-body pt-20">
                  <div class="tab-content">
          <?php
          $query="select * from requirement where cropId in(select cropId from farmer_crop where fId=".$_SESSION['userId'].") AND status=0";
          $result=$conn->query($query);
          if($row = $result->fetch_assoc())
          {
            ?>
            <div class="tab-pane active" id="panelTab1" role="tabpanel">
              <table class="table table-hover dataTable table-striped w-full" data-plugin="dataTable">
              <thead>
                <tr>
                  <th>Select</th>
                  <th>Crop</th>
                  <th>Required Quantity(Ton)</th>
                  <th>Required Area(Acre)</th>
                  <th>Basic Cost(Rs.)</th>
                  <th>Land Cost (Rs./Acre)</th>
                  <th>Cost for Crop (Rs./Ton)</th>
                  <th>Supplies</th>
                </tr>
              </thead>
              <tbody>
              <?php
              do
              {
              $query1="select cropName,areaPerTon,costPerTon from crop where cropId=".$row['cropId'];
              $result1=$conn->query($query1);
              $row1 = $result1->fetch_assoc();
              ?>
                <tr>
                  <td><input type="radio" name="radioInput" value="<?php echo $row['reqId']; ?>" required></td>
                  <td><?php echo $row1['cropName']; ?></td>
                  <td><?php echo $row['remainingQuantity'] ?></td>
                  <td><?php echo $row['remainingQuantity']*$row1['areaPerTon']; ?></td>
                  <td><?php echo $row['basicCost']; ?></td>
                  <td><?php echo $row['landCost']; ?></td>
                  <td><?php echo $row1['costPerTon']; ?></td>
                  <td>
                  <?php
                  $query2="select supplyId from crop_supply where cropId=".$row['cropId'];
                  $result2=$conn->query($query2);
                  while($row2 = $result2->fetch_assoc())
                  {
                    $query3="select supplyName from supply where supplyId=".$row2['supplyId'];
                    $result3=$conn->query($query3);
                    $row3 = $result3->fetch_assoc();
                    echo $row3['supplyName'];
                    echo "<br>";
                  }
                  ?>
                  </td>
                </tr>
                <?php
                }while($row = $result->fetch_assoc());
                ?>
                </tbody>
                </table>
            <center>
                    <div class="col-sm-12 col-md-4 col-xl-2">
                      <div class="example">
                        <ul class="list-unstyled">
                          <li class="mb-20">
                            <input type="submit" class="btn btn-block btn-primary" name="request" value="Request Contract" />
                          </li>
                        </ul>
                      </div>
                    </div>
                  </center>
                  </div>
          <?php
          }
          else
          {
          $query="select * from requirement where  status=0";
          $result=$conn->query($query);
          if($row = $result->fetch_assoc())
          {
            ?>
            <div class="tab-pane active" id="panelTab1" role="tabpanel">
              <p>There are no requirements for your particular crop at this time, but these are requirements for some other crops:</p>
              <table class="table table-hover dataTable table-striped w-full" data-plugin="dataTable">
              <thead>
                <tr>
                  <th>Select</th>
                  <th>Crop</th>
                  <th>Required Quantity(Ton)</th>
                  <th>Required Area(Acre)</th>
                  <th>Basic Cost(Rs.)</th>
                  <th>Land Cost (Rs./Acre)</th>
                  <th>Cost for Crop (Rs./Ton)</th>
                  <th>Supplies</th>
                </tr>
              </thead>
              <tbody>
              <?php
              do
              {
              $query1="select cropName,areaPerTon,costPerTon from crop where cropId=".$row['cropId'];
              $result1=$conn->query($query1);
              $row1 = $result1->fetch_assoc();
              ?>
                <tr>
                  <td><input type="radio" name="radioInput" value="<?php echo $row['reqId']; ?>" required></td>
                  <td><?php echo $row1['cropName']; ?></td>
                  <td><?php echo $row['remainingQuantity'] ?></td>
                  <td><?php echo $row['remainingQuantity']*$row1['areaPerTon']; ?></td>
                  <td><?php echo $row['basicCost']; ?></td>
                  <td><?php echo $row['landCost']; ?></td>
                  <td><?php echo $row1['costPerTon']; ?></td>
                  <td>
                  <?php
                  $query2="select supplyId from crop_supply where cropId=".$row['cropId'];
                  $result2=$conn->query($query2);
                  while($row2 = $result2->fetch_assoc())
                  {
                    $query3="select supplyName from supply where supplyId=".$row2['supplyId'];
                    $result3=$conn->query($query3);
                    $row3 = $result3->fetch_assoc();
                    echo $row3['supplyName'];
                    echo "<br>";
                  }
                  ?>
                  </td>
                </tr>
                <?php
                }while($row = $result->fetch_assoc());
                ?>
                </tbody>
                </table>
            <center>
                    <div class="col-sm-12 col-md-4 col-xl-2">
                      <div class="example">
                        <ul class="list-unstyled">
                          <li class="mb-20">
                            <input type="submit" class="btn btn-block btn-primary" name="request" value="Request Contract" />
                          </li>
                        </ul>
                      </div>
                    </div>
                  </center>
                  </div>
          <?php
          }
          else
          {
            ?>
            <div class="tab-pane active" id="panelTab1" role="tabpanel">
                Sorry! No new requirements uploaded. Please try again later.
            </div>
          <?php
          }
        }
          $query="select * from contract where fId=".$_SESSION['userId']." AND status=1";
          $result=$conn->query($query);
          if($row = $result->fetch_assoc())
          {
            ?>
            <div class="tab-pane" id="panelTab2" role="tabpanel">
              <table class="table table-hover dataTable table-striped w-full" data-plugin="dataTable">
              <thead>
                <tr>
                  <th>Select</th>
                  <th>Crop</th>
                  <th>Requested Area(Acre)</th>
                  <th>Basic Cost(Rs.)</th>
                  <th>Land Cost(Rs./Acre)</th>
                </tr>
              </thead>
              <tbody>
              <?php
              do
            {
              $query1="select * from requirement where reqId=".$row['reqId'];
              $result1=$conn->query($query1);
              $row1 = $result1->fetch_assoc();
              $query2="select cropName from crop where cropId=".$row1['cropId'];
              $result2=$conn->query($query2);
              $row2 = $result2->fetch_assoc();
              ?>
                <tr>
                  <td><input type="radio" name="radioInput" value="<?php echo $row['contractId']; ?>" required></td>
                  <td><?php echo $row2['cropName']; ?></td>
                  <td><?php echo $row['landArea']; ?></td>
                  <td><?php echo $row1['basicCost']; ?></td>
                  <td><?php echo $row1['landCost']; ?></td>
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
            <div class="tab-pane" id="panelTab2" role="tabpanel">
                  Sorry! No requests from Admin yet. Please look at new requirements and make a request.
            </div>
          <?php
          }
          ?>
          </div>
              </div>
            </div>
            
          </div>
          <?php
        }
      }
      ?>
      </form>
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
        <script src="../../../global/vendor/asrange/jquery-asRange.min.js"></script>
        <script src="../../../global/vendor/bootbox/bootbox.js"></script>
        <script src="../../../global/vendor/peity/jquery.peity.min.js"></script>
        <script src="../../../global/vendor/aspieprogress/jquery-asPieProgress.js"></script>
        <script src="../../../global/vendor/tablesaw/tablesaw.jquery.js"></script>
        <script src="../../../global/vendor/tablesaw/tablesaw-init.js"></script>
    
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
        <script src="../../../global/js/Plugin/asselectable.js"></script>
        <script src="../../../global/js/Plugin/selectable.js"></script>
        <script src="../../../global/js/Plugin/table.js"></script>
        <script src="../../../global/js/Plugin/asscrollable.js"></script>
        <script src="../../assets/examples/js/charts/peity.js"></script>
        <script src="../../../global/js/Plugin/aspieprogress.js"></script>
        <script src="../../assets/examples/js/charts/pie-progress.js"></script>
        <script src="../../../global/js/Plugin/tablesaw.js"></script>
        <script src="../../assets/examples/js/tables/responsive.js"></script>

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
