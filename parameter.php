<?php
    session_start();
    include('includes/header.html');
    require('connection.php');
    
    $connection = ConnectToDatabase();

    if (!empty($_SESSION['VendorQueryFormValid'])) {
        $description = $_SESSION['Q_Description'];

        $cmd = "SELECT Parts.Description, Vendors.VendorName
                FROM Vendors INNER JOIN Parts ON Vendors.VendorNo = Parts.VendorNo
                WHERE (((Parts.Description) Like '%$description%' ));";

        $vendorQuery = $connection->prepare($cmd);
        $vendorQuery -> execute();

        $rows = ($vendorQuery->fetchAll());
        $numRows = count($rows);
        
    }
?>
<div class="container">
    <?php
    if (!empty($_SESSION['VendorQueryFormValid'])) {
    ?>
    <div class="alert alert-dismissible alert-success">
       <button type="button" class="close" data-dismiss="alert">&times;</button>
       <strong>Vendor query successful!</strong>  View details below.
    </div>
    <div class="panel panel-success">
       <div class="panel-heading">
           <h3 class="panel-title">Vendor names which contain the phrase <?php echo "'".$description."'";?></h3>
       </div>
       <div class="panel-body">
            <ul>
            <?php
            if($numRows>0){
                foreach ($rows as $row){
                    echo "<li>{$row['VendorName']}</li>";
                }
            ?>
           <?php
            } else {
            ?>
            <li>No Vendors supply a part with the phrase <?php echo "'".$description."'";?></li>
            <?php
            }
           ?>
           </ul>
       </div>
    </div>

    <?php
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
