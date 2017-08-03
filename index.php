
<?php
	session_start();

	include('includes/header.html');
	require('connection.php');
	require('requires/post-validation.php');

	//error array
	$errors = array();

	//Database Connection
	$connection = ConnectToDatabase();

	//Vendor Number and Name query
	$vendorsQuery = $connection->prepare("SELECT VendorNo,VendorName from Vendors");
	$vendorsQuery -> execute();

	/*
	* Creates a <select> dropdown list, taking a SELECT query ($theQuery) as the first parameter,
	* the <option> value as $valueID, the <option> description as $valueDescription
	* Returns a concatenated output of all of the options
	*/
	function DropdownListFor($theQuery,$valueID,$valueDescription){
		$output = "";
		while($row = $theQuery ->fetch()){
			$output .='<option value="' . $row[$valueID] . '">' . $row[$valueDescription] . '</option>';
		}
		return $output;
	}

	//When the form is submitted
	if ($_SERVER['REQUEST_METHOD']=='POST'){
        $_SESSION['VendorNo'] = ValidatePost("VendorNo","Vendor is a required field.",$errors);
        $_SESSION['Description'] = ValidatePost("Description","Part description is a required field.",$errors);
        $_SESSION['OnHand'] = ValidatePost("OnHand","Parts on hand is a required field.",$errors);
        $_SESSION['OnOrder'] = ValidatePost("OnOrder","Part on order is a required field.",$errors);
        $_SESSION['Cost'] = ValidatePost("Cost","Cost is a required field.",$errors);
        $_SESSION['ListPrice'] = ValidatePost("ListPrice","List price is a required field.",$errors);

    } // END IF POST
?>

<div class="jumbotron">
	<div class="container">
		<h1>Hello, world!</h1>
		<p>Contents ...</p>
		<p>
			<a class="btn btn-primary btn-lg">Learn more</a>
		</p>
	</div>
</div>
<div class="container">
	<div class="well">
		<?php
		if(count($errors)>0){
		?>
			<div class="errors">
				<div class="alert alert-dismissible alert-danger">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<strong>Oh snap!</strong> <a href="#" class="alert-link">Change a few things up</a> and try submitting again.
				</div>
				<ul>
					<?php
						foreach($errors as $error){
							echo '<li class="">'.$error.'</li>';
						}
					?>
				</ul>
			</div>
		<?php
		}
		?>
		<form action="index.php" method="POST" role="form">
			<legend><h2>Insert record into the Parts table</h2></legend>
			
			<div class="form-group row">
				<label class="col-md-2" for="VendorNo">Vendor</label>
				<div class="col-md-10">
					<select name="VendorNo" id="VendorNo" class="form-control" required="required">
						<?php
							echo DropdownListFor($vendorsQuery,"VendorNo","VendorName");	
						?>
					</select>
				</div>
			</div>

			<div class="form-group row">
				<label class="col-md-2" for="Description">Part Description</label>
				<div class="col-md-10">
					<input type="text" class="form-control" id="Description" name="Description">
				</div>
			</div>

			<div class="form-group row">
				<label class="col-md-2" for="">Parts on hand</label>
				<div class="col-md-10">
					<input type="text" class="form-control" id="OnHand" name="OnHand" placeholder="Enter positive integers only">
				</div>
			</div>

			<div class="form-group row">
				<label class="col-md-2" for="">Parts on order</label>
				<div class="col-md-10">
					<input type="text" class="form-control" id="OnOrder" name="OnOrder" placeholder="Enter positive integers only">
				</div>
			</div>

			<div class="form-group row">
				<label class="col-md-2" for="">Cost</label>
				<div class="col-md-10">
					<input type="text" class="form-control" id="Cost" name="Cost">
				</div>
			</div>

			<div class="form-group row">
				<label class="col-md-2" for="">List Price</label>
				<div class="col-md-10">
					<input type="text" class="form-control" id="ListPrice" name="ListPrice">
				</div>
			</div>
			<button type="submit" name="submit" class="btn btn-primary">Submit</button>
		</form>
	</div>
</div>

<?php
	include('includes/footer.html');	
?>



