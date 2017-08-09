<?php
    session_start();
    include('includes/header.html');
    require('connection.php');
    
    $connection = ConnectToDatabase();



    if (!empty($_SESSION['PartFormValid'])){
        $vendorNo = $_SESSION['P_VendorNo'];
        $description = AddQuotesToString($_SESSION['Description']);
        $onHand = $_SESSION['OnHand'];
        $onOrder =$_SESSION['OnOrder'];
        $cost =$_SESSION['Cost'];
        $listPrice = $_SESSION['ListPrice'];

        $cmd = 'INSERT INTO Parts (VendorNo,Description,OnHand,OnOrder,Cost,ListPrice) VALUES (' . $vendorNo . ',' . $description  . ',' . $onHand . ',' . $onOrder . ',' . $cost . ',' . $listPrice . ');';   
        
        if($connection->query($cmd)){
            echo "New record created successfully";
        } else {
            echo "Error: " . $cmd . "<br>" . $connection->error;
        }
        
    }

    
    	
?>


<?php
    include('includes/footer.html');
?>