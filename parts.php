<?php
    session_start();
    include('includes/header.html');
    require('connection.php');
    require('requires/common.php');
    
    $connection = ConnectToDatabase();

    if (!empty($_SESSION['PartFormValid'])) {
        $vendorNo = $_SESSION['P_VendorNo'];
        $description = AddQuotesToString($_SESSION['Description']);
        $onHand = $_SESSION['OnHand'];
        $onOrder =$_SESSION['OnOrder'];
        $cost =$_SESSION['Cost'];
        $listPrice = $_SESSION['ListPrice'];

        $cmd = 'INSERT INTO Parts (VendorNo,Description,OnHand,OnOrder,Cost,ListPrice) VALUES (' . $vendorNo . ',' . $description  . ',' . $onHand . ',' . $onOrder . ',' . $cost . ',' . $listPrice . ');';
    }
?>
<div class="container">
    <?php
    if (!empty($_SESSION['PartFormValid'])) {
        if ($connection->query($cmd)) {
    ?>
    <div class="alert alert-dismissible alert-success">
       <button type="button" class="close" data-dismiss="alert">&times;</button>
       <strong>New Part Record Created Successfully!</strong>  View details below.
    </div>
    <div class="panel panel-success">
       <div class="panel-heading">
           <h3 class="panel-title">Part record summary</h3>
       </div>
       <div class="panel-body">
           <ul>
              <li>VendorNo: <?php echo $vendorNo; ?></li>
              <li>Description: <?php echo $description; ?></li>
              <li>OnHand: <?php echo $onHand; ?></li>
              <li>OnOrder: <?php echo $onOrder; ?></li>
              <li>Cost: <?php echo $cost; ?></li>
              <li>ListPrice: <?php echo $listPrice; ?></li>
           </ul>
       </div>
    </div>

    <?php
        }
    } else {
        ?>
            <div class="alert alert-dismissible alert-danger">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>An error has occurred!</strong> Please navigate to the <a href="index.php" class="alert-link" >previous page</a> and try again.
            </div>
        <?php
    }
    ?>
</div>


<?php
    include('includes/footer.html');
    session_destroy();
?>
