<?php
    session_start();
    include('includes/header.html');
    if (!empty($_SESSION['PartFormValid'])){
        $_SESSION['P_VendorNo']; 
        $_SESSION['Description'];
        $_SESSION['OnHand'];
        $_SESSION['OnOrder'];
        $_SESSION['Cost'];
        $_SESSION['ListPrice'];
    }
    	
?>


<?php
    include('includes/footer.html');
?>