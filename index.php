
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

	function ValidationSummaryFor($postName,$formSubmitName){
		global $errors;
		if (!empty($errors[$postName]) && isset($_POST[$formSubmitName])){
			echo '<span class="text-danger">' . $errors[$postName] . '</span>';
		}
	}
	
	//When the form is submitted
	if ($_SERVER['REQUEST_METHOD']=='POST'){
		if (isset($_POST['Parts'])){
			$_SESSION['P_VendorNo'] = ValidatePost("P_VendorNo","Vendor",$partDataValidation,$partValidationMessage,false,"A vendor must be selected");
			$_SESSION['Description'] = ValidatePost("Description","Part description",$partDataValidation,$partValidationMessage);
			$_SESSION['OnHand'] = ValidatePost("OnHand","Parts on hand",$partDataValidation,$partValidationMessage);
			$_SESSION['OnOrder'] = ValidatePost("OnOrder","Parts on order",$partDataValidation,$partValidationMessage);
			$_SESSION['Cost'] = ValidatePost("Cost","Cost",$partDataValidation,$partValidationMessage);
			$_SESSION['ListPrice'] = ValidatePost("ListPrice","List price",$partDataValidation,$partValidationMessage);
		} else if (isset($_POST['Vendors'])){
			$_SESSION['V_VendorNo'] = ValidatePost("V_VendorNo","Vendor",$vendorDataValidation,$vendorValidationMessage,false,"A vendor must be selected");
			$_SESSION['VendorName'] = ValidatePost("VendorName","Vendor name",$vendorDataValidation,$vendorValidationMessage);
			$_SESSION['Address1'] = ValidatePost("Address1","Address 1",$vendorDataValidation,$vendorValidationMessage);
			$_SESSION['Address2'] = ValidatePost("Address2","Address 2",$vendorDataValidation,$vendorValidationMessage);
			$_SESSION['City'] = ValidatePost("City","City",$vendorDataValidation,$vendorValidationMessage);
			$_SESSION['Prov'] = ValidatePost("Prov","Province",$vendorDataValidation,$vendorValidationMessage);
			$_SESSION['PostCode'] = ValidatePost("PostCode","Postal code",$vendorDataValidation,$vendorValidationMessage);
			$_SESSION['Country'] = ValidatePost("Country","Country",$vendorDataValidation,$vendorValidationMessage);
			$_SESSION['Phone'] = ValidatePost("Phone","Phone number",$vendorDataValidation,$vendorValidationMessage);
			$_SESSION['Fax'] = ValidatePost("Fax","Fax number",$vendorDataValidation,$vendorValidationMessage);
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
					<div id="PartsFormErrors" class="errors">
						<?php
						if(count($errors)>0 && isset($_POST['Parts'])){
						?>
						<div class="alert alert-dismissible alert-danger">
							<button type="button" class="close" data-dismiss="alert">&times;</button>
							<strong>Oh snap!</strong> <a href="#" class="alert-link">Change a few things up</a> and try submitting again.
						</div>
						<ul>
							<?php
								foreach($errors as $error){
									echo '<li class="text-danger">'.$error.'</li>';
								}
							?>
						</ul>
						<?php
						}
						?>
					</div>
					<form action="index.php" method="POST" role="form" id="PartsForm">
						<legend><h2>Insert record into the Parts table</h2></legend>
						
						<div class="form-group row">
							<label class="col-md-2" for="P_VendorNo">Vendor</label>
							<div class="col-md-10">
								<select name="P_VendorNo" id="P_VendorNo" class="form-control" >
									<option value="null">Select a vendor</option>
									<?php
										DropdownListFor($vendorsQuery,"VendorNo","VendorName");	
									?>
								</select>
								<?php
								ValidationSummaryFor("P_VendorNo","Parts");
								?>
							</div>
							
						</div>

						<div class="form-group row">
							<label class="col-md-2" for="Description">Part Description</label>
							<div class="col-md-10">
								<input type="text" class="form-control" id="Description" name="Description"  value="<?php echo StickyForm('Description') ?>">
								<?php
								ValidationSummaryFor("Description","Parts");
								?>
							</div>
						</div>

						<div class="form-group row">
							<label class="col-md-2" for="">Parts on hand</label>
							<div class="col-md-10">
								<input type="text" class="form-control" id="OnHand" name="OnHand" placeholder="Enter positive integers only" value="<?php echo StickyForm('OnHand') ?>">
								<?php
								ValidationSummaryFor("OnHand","Parts");
								?>
							</div>
						</div>

						<div class="form-group row">
							<label class="col-md-2" for="">Parts on order</label>
							<div class="col-md-10">
								<input type="text" class="form-control" id="OnOrder" name="OnOrder" placeholder="Enter positive integers only" value="<?php echo StickyForm('OnOrder') ?>">
								<?php
								ValidationSummaryFor("OnOrder","Parts");
								?>
							</div>
						</div>

						<div class="form-group row">
							<label class="col-md-2" for="">Cost</label>
							<div class="col-md-10">
								<input type="text" class="form-control" id="Cost" name="Cost" value="<?php echo StickyForm('Cost') ?>">
								<?php
								ValidationSummaryFor("Cost","Parts");
								?>
							</div>
						</div>

						<div class="form-group row">
							<label class="col-md-2" for="">List Price</label>
							<div class="col-md-10">
								<input type="text" class="form-control" id="ListPrice" name="ListPrice" value="<?php echo StickyForm('ListPrice') ?>">
								<?php
								ValidationSummaryFor("ListPrice","Parts");
								?>
							</div>
						</div>
						<button type="submit" name="Parts" class="btn btn-primary">Submit</button>
					</form>
				</div>
			</div>
			<div role="tabpanel" class="tab-pane" id="Vendors">
				<div class="well">
					<?php
					if(count($errors)>0 && isset($_POST['Vendors'])){
					?>
						<div id="VendorsFormErrors" class="errors">
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
							<label class="col-md-2" for="VendorNo">Vendor number</label>
							<div class="col-md-10">
								<input type="text" class="form-control" id="V_VendorNo" name="V_VendorNo" placeholder="Enter 4 digits, e.g 1234"  value="<?php echo StickyForm('V_VendorNo') ?>">
								<?php
								ValidationSummaryFor("V_VendorNo","Vendors");
								?>
							</div>
							
						</div>

						<div class="form-group row">
							<label class="col-md-2" for="VendorName">Vendor name</label>
							<div class="col-md-10">
								<input type="text" class="form-control" id="VendorName" name="VendorName"  value="<?php echo StickyForm('VendorName') ?>">
								<?php
								ValidationSummaryFor("VendorName","Vendors");
								?>
							</div>
						</div>

						<div class="form-group row">
							<label class="col-md-2" for="Address1">Address 1</label>
							<div class="col-md-10">
								<input type="text" class="form-control" id="Address1" name="Address1"  value="<?php echo StickyForm('Address1') ?>">
								<?php
								ValidationSummaryFor("Address1","Vendors");
								?>
							</div>
						</div>

						<div class="form-group row">
							<label class="col-md-2" for="Address2">Address 2</label>
							<div class="col-md-10">
								<input type="text" class="form-control" id="Address2" name="Address2" value="<?php echo StickyForm('Address2') ?>">
								<?php
								ValidationSummaryFor("Address2","Vendors");
								?>
							</div>
						</div>

						<div class="form-group row">
							<label class="col-md-2" for="City">City</label>
							<div class="col-md-10">
								<input type="text" class="form-control" id="City" name="City" value="<?php echo StickyForm('City') ?>">
								<?php
								ValidationSummaryFor("City","Vendors");
								?>
							</div>
						</div>

						<div class="form-group row">
							<label class="col-md-2" for="Prov">Province</label>
							<div class="col-md-10">
								<input type="text" class="form-control" id="Prov" name="Prov" placeholder="Enter province as two capital letters, eg AA" value="<?php echo StickyForm('Prov') ?>">
								<?php
								ValidationSummaryFor("Prov","Vendors");
								?>
							</div>
						</div>

						<div class="form-group row">
							<label class="col-md-2" for="PostCode">Postal code</label>
							<div class="col-md-10">
								<input type="text" class="form-control" id="PostCode" name="PostCode" placeholder="Acceptable format is N2L5S3 or 60093" value="<?php echo StickyForm('PostCode') ?>">
								<?php
								ValidationSummaryFor("PostCode","Vendors");
								?>
							</div>
						</div>

						<div class="form-group row">
							<label class="col-md-2" for="Country">Country</label>
							<div class="col-md-10">
								<input type="text" class="form-control" id="Country" name="Country" value="<?php echo StickyForm('Country') ?>">
								<?php
								ValidationSummaryFor("Country","Vendors");
								?>
							</div>
						</div>

						<div class="form-group row">
							<label class="col-md-2" for="Phone">Phone number</label>
							<div class="col-md-10">
								<input type="text" class="form-control" id="Phone" name="Phone" placeholder="Acceptable format is 999-999-9999" value="<?php echo StickyForm('Phone') ?>">
								<?php
								ValidationSummaryFor("Phone","Vendors");
								?>
							</div>
						</div>

						<div class="form-group row">
							<label class="col-md-2" for="Fax">Fax number</label>
							<div class="col-md-10">
								<input type="text" class="form-control" id="Fax" name="Fax" placeholder="Acceptable format is 999-999-9999" value="<?php echo StickyForm('Fax') ?>">
								<?php
								ValidationSummaryFor("Fax","Vendors");
								?>
							</div>
						</div>

						<button type="submit" name="Vendors" class="btn btn-primary">Submit</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
	include('includes/footer.html');	
?>



