<?php
    session_start();
    include('includes/header.html');
    require('connection.php');
    
    $connection = ConnectToDatabase();

    if (!empty($_SESSION['VendorFormValid'])) {
        $vendorNo = $_SESSION['V_VendorNo'];
        $vendorName = $_SESSION['VendorName'];
        $address1 =$_SESSION['Address1'];
        $address2 =$_SESSION['Address2'];
        $city = $_SESSION['City'];
        $prov = $_SESSION['Prov'];
        $postCode =$_SESSION['PostCode'];
        $country =$_SESSION['Country'];
        $phone = $_SESSION['Phone'];
        $fax = $_SESSION['Fax'];
        
        if (strlen($address2)>0){
            $cmd = "INSERT INTO Vendors (VendorNo,VendorName,Address1,Address2,City,Prov,PostCode,Country,Phone,Fax)
            VALUES ($vendorNo,'$vendorName', '$address1','$address2','$city','$prov','$postCode','$country','$phone','$fax');";
        } else {
            $cmd = "INSERT INTO Vendors (VendorNo,VendorName,Address1,City,Prov,PostCode,Country,Phone,Fax)
            VALUES ($vendorNo,'$vendorName', '$address1','$city','$prov','$postCode','$country','$phone','$fax');";
        }
    }
?>
<div class="container">
    <?php
    if (!empty($_SESSION['VendorFormValid'])) {
        if ($connection->query($cmd)) {
    ?>
    <div class="alert alert-dismissible alert-success">
       <button type="button" class="close" data-dismiss="alert">&times;</button>
       <strong>New Vendor Record Created Successfully!</strong>  View details below.
    </div>
    <div class="panel panel-success">
       <div class="panel-heading">
           <h3 class="panel-title">Vendor record summary</h3>
       </div>
       <div class="panel-body">
           <ul>
              <li>VendorNo: <?php echo $vendorNo; ?></li>
              <li>VendorName: <?php echo $vendorName; ?></li>
              <li>Address1: <?php echo $address1; ?></li>
              <li>Address2: <?php echo $address2; ?></li>
              <li>City: <?php echo $city; ?></li>
              <li>Prov: <?php echo $prov; ?></li>
              <li>PostCode: <?php echo $postCode; ?></li>
              <li>Country: <?php echo $country; ?></li>
              <li>Phone: <?php echo $phone; ?></li>
              <li>Fax: <?php echo $fax; ?></li>
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
