<?php
require '../config.php';
if(!isset($_SESSION["userId"]))
  header("Location: ../common/login.php");
?>

<!DOCTYPE html>
<html class="no-js css-menubar" lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="bootstrap admin template">
    <meta name="author" content="">
    
    <title>Invoice</title>
    
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

    <!-- Page -->
    <div class="page">
      <?php
      $query="select * from contract where contractId=".$_POST['contractId'];
      $result=$conn->query($query);
      if($row = $result->fetch_assoc())
      {
        $query1="select * from requirement where reqId=".$row['reqId'];
        $result1=$conn->query($query1);
        $row1 = $result1->fetch_assoc();
        $query2="select costPerTon,cropName from crop where cropId=".$row1['cropId'];
        $result2=$conn->query($query2);
        $row2 = $result2->fetch_assoc();
      ?>
        <div class="page-content  container-fluid">

          <div class="panel">
          <h3 class="panel-title"><?php echo $row2['cropName']." : ".$row['startDate']." - ".$row['endDate']; ?></h3>
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
              </tbody>
                
              <?php } ?>
              </table>

              <center>
                <div class="col-sm-12 col-md-4 col-xl-2">
                      <div class="example">
                        <ul class="list-unstyled">
                          <li class="mb-20">
                            <button class="btn btn-block btn-primary" id="redirect" onclick="javascript:window.print();">Print</button>
                          </li>
                        </ul>
                      </div>
                    </div>
                </center>

              </div>
          </div>
          </div>
        <!-- End Panel Form Elements -->
      </div>
      <?php
      }
      ?>
    </div>
    <!-- End Page -->


    <!-- Footer -->
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
