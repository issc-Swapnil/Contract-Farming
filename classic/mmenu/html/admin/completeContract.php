<?php
require '../config.php';
if(!isset($_SESSION["userId"]))
  header("Location: ../common/login.php");
if(isset($_SESSION['contractId']))
{
  $query="select * from contract where contractId=".$_SESSION['contractId'];
      $result=$conn->query($query);
      $row = $result->fetch_assoc();
        $query1="select * from requirement where reqId=".$row['reqId'];
        $result1=$conn->query($query1);
        $row1 = $result1->fetch_assoc();
        $query2="select costPerTon,cropName from crop where cropId=".$row1['cropId'];
        $result2=$conn->query($query2);
        $row2 = $result2->fetch_assoc();
  $total1=$row1['basicCost']+($row1['landCost']*$row['landArea'])+($row2['costPerTon']*$row['quantity']);
                  $query3="select * from contract_supply where contractId=".$row['contractId'];
              $result3=$conn->query($query3);
              $total2=0;
              while($row3 = $result3->fetch_assoc())
              {
              $query4="select costPerUnit from supply where supplyId=".$row3['supplyId'];
              $result4=$conn->query($query4);
              $row4 = $result4->fetch_assoc();
              $total2 = $total2 + ($row4['costPerUnit']*$row3['supplyQuantity']);
            }
            $total=$total1-$total2;
  $query="update contract set status=5,totalCost=".$total." where contractId=".$_SESSION['contractId'];
  $conn->query($query);
  header("Location: viewCompletedContracts.php");
}
?>