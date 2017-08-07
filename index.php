
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
	* echoes a concatenated output of all of the options
	*/
	function DropdownListFor($theQuery,$valueID,$valueDescription){
		$output = "";
		while($row = $theQuery ->fetch()){
			if (StickyForm($valueID)!=null && StickyForm($valueID)==$row[$valueID]){				
				$output .='<option value="' . $row[$valueID] .'" SELECTED>' . $row[$valueDescription] . '</option>';	
			} else {
				$output .='<option value="' . $row[$valueID] .'">' . $row[$valueDescription] . '</option>';
			}
		}
		echo $output;
	}

	function ValidationSummaryFor($postName){
		global $errors;
		if (!empty($errors[$postName])){
			echo '<p class="error">' . $errors[$postName] . '</p>';
		}
	}
	
	//When the form is submitted
	if ($_SERVER['REQUEST_METHOD']=='POST'){
		if (isset($_POST['SubmitParts'])){
			$_SESSION['VendorNo'] = ValidatePost("VendorNo","Vendor",$partDataValidation,$partValidationMessage,false,"A vendor must be selected");
			$_SESSION['Description'] = ValidatePost("Description","Part description",$partDataValidation,$partValidationMessage);
			$_SESSION['OnHand'] = ValidatePost("OnHand","Parts on hand",$partDataValidation,$partValidationMessage);
			$_SESSION['OnOrder'] = ValidatePost("OnOrder","Parts on order",$partDataValidation,$partValidationMessage);
			$_SESSION['Cost'] = ValidatePost("Cost","Cost",$partDataValidation,$partValidationMessage);
			$_SESSION['ListPrice'] = ValidatePost("ListPrice","List price",$partDataValidation,$partValidationMessage);
		} else if (isset($_POST['SubmitVendors'])){
			
		}
		if (count($errors)==0){
			if (isset($_POST['SubmitParts'])){
				header('Location: parts.php');
			} elseif (isset($_POST['SubmitVendors'])){
				header('Location: vendors.php');
			}
		}
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

<div role="tabpanel">
	<!-- Nav tabs -->
	<ul class="nav nav-tabs" role="tablist">
		<li role="presentation" class="active">
			<a href="#Parts" aria-controls="Parts" role="tab" data-toggle="tab">Parts</a>
		</li>
		<li role="presentation">
			<a href="#Vendors" aria-controls="tab" role="tab" data-toggle="tab">Vendors</a>
		</li>
	</ul>

	<!-- Tab panes -->
	<div class="tab-content">
		<div role="tabpanel" class="tab-pane active" id="Parts">
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
									echo '<li class="error">'.$error.'</li>';
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
							<select name="VendorNo" id="VendorNo" class="form-control" >
								<option value="null">Select a vendor</option>
								<?php
									DropdownListFor($vendorsQuery,"VendorNo","VendorName");	
								?>
							</select>
							<?php
							ValidationSummaryFor("VendorNo");
							?>
						</div>
						
					</div>

					<div class="form-group row">
						<label class="col-md-2" for="Description">Part Description</label>
						<div class="col-md-10">
							<input type="text" class="form-control" id="Description" name="Description"  value="<?php echo StickyForm('Description') ?>">
							<?php
							ValidationSummaryFor("Description");
							?>
						</div>
					</div>

					<div class="form-group row">
						<label class="col-md-2" for="">Parts on hand</label>
						<div class="col-md-10">
							<input type="text" class="form-control" id="OnHand" name="OnHand" placeholder="Enter positive integers only" value="<?php echo StickyForm('OnHand') ?>">
							<?php
							ValidationSummaryFor("OnHand");
							?>
						</div>
					</div>

					<div class="form-group row">
						<label class="col-md-2" for="">Parts on order</label>
						<div class="col-md-10">
							<input type="text" class="form-control" id="OnOrder" name="OnOrder" placeholder="Enter positive integers only" value="<?php echo StickyForm('OnOrder') ?>">
							<?php
							ValidationSummaryFor("OnOrder");
							?>
						</div>
					</div>

					<div class="form-group row">
						<label class="col-md-2" for="">Cost</label>
						<div class="col-md-10">
							<input type="text" class="form-control" id="Cost" name="Cost" value="<?php echo StickyForm('Cost') ?>">
							<?php
							ValidationSummaryFor("Cost");
							?>
						</div>
					</div>

					<div class="form-group row">
						<label class="col-md-2" for="">List Price</label>
						<div class="col-md-10">
							<input type="text" class="form-control" id="ListPrice" name="ListPrice" value="<?php echo StickyForm('ListPrice') ?>">
							<?php
							ValidationSummaryFor("ListPrice");
							?>
						</div>
					</div>
					<button type="submit" name="SubmitParts" class="btn btn-primary">Submit</button>
				</form>
			</div>
		</div>
		<div role="tabpanel" class="tab-pane" id="Vendors">
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
									echo '<li class="error">'.$error.'</li>';
								}
							?>
						</ul>
					</div>
				<?php
				}
				?>
				<form action="index.php" method="POST" role="form">
					<legend><h2>Insert record into the Vendors table</h2></legend>
					
					<div class="form-group row">
						<label class="col-md-2" for="VendorNo">Vendor</label>
						<div class="col-md-10">
							<select name="VendorNo" id="VendorNo" class="form-control" >
								<option value="null">Select a vendor</option>
								<?php
									DropdownListFor($vendorsQuery,"VendorNo","VendorName");	
								?>
							</select>
							<?php
							ValidationSummaryFor("VendorNo");
							?>
						</div>
						
					</div>

					<div class="form-group row">
						<label class="col-md-2" for="Description">Part Description</label>
						<div class="col-md-10">
							<input type="text" class="form-control" id="Description" name="Description"  value="<?php echo StickyForm('Description') ?>">
							<?php
							ValidationSummaryFor("Description");
							?>
						</div>
					</div>

					<div class="form-group row">
						<label class="col-md-2" for="">Parts on hand</label>
						<div class="col-md-10">
							<input type="text" class="form-control" id="OnHand" name="OnHand" placeholder="Enter positive integers only" value="<?php echo StickyForm('OnHand') ?>">
							<?php
							ValidationSummaryFor("OnHand");
							?>
						</div>
					</div>

					<div class="form-group row">
						<label class="col-md-2" for="">Parts on order</label>
						<div class="col-md-10">
							<input type="text" class="form-control" id="OnOrder" name="OnOrder" placeholder="Enter positive integers only" value="<?php echo StickyForm('OnOrder') ?>">
							<?php
							ValidationSummaryFor("OnOrder");
							?>
						</div>
					</div>

					<div class="form-group row">
						<label class="col-md-2" for="">Cost</label>
						<div class="col-md-10">
							<input type="text" class="form-control" id="Cost" name="Cost" value="<?php echo StickyForm('Cost') ?>">
							<?php
							ValidationSummaryFor("Cost");
							?>
						</div>
					</div>

					<div class="form-group row">
						<label class="col-md-2" for="">List Price</label>
						<div class="col-md-10">
							<input type="text" class="form-control" id="ListPrice" name="ListPrice" value="<?php echo StickyForm('ListPrice') ?>">
							<?php
							ValidationSummaryFor("ListPrice");
							?>
						</div>
					</div>
					<button type="submit" name="SubmitParts" class="btn btn-primary">Submit</button>
				</form>
			</div>
		</div>
	</div>
</div>

	
</div>

<?php
	include('includes/footer.html');	
?>



