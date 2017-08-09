
<?php
	session_start();

	include('includes/header.html');
	require('connection.php');
	require('requires/post-validation.php');

	//error array
	$errors = array();

	//Database Connection
	$connection = ConnectToDatabase();

	//Vendor table, Vendor Number and Name query
	$vendorsQuery = $connection->prepare("SELECT VendorNo,VendorName from Vendors");
	$vendorsQuery -> execute();

	
	/*
	* Sets the value of an input field in a form if the page has been posted and the value is set
	*/
	function StickyForm($postVariable){
		if(isset($_POST[$postVariable])){
			return $_POST[$postVariable];
		} else {
			return null;
		}
	}
	
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
			echo '<span id="'. $postName . 'Error"'  .  'class="text-danger">' . $errors[$postName] . '</span>';
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
			$_SESSION['V_VendorNo'] = ValidatePostByPredicate("V_VendorNo","Vendor",$vendorDataValidation,$vendorValidationMessage,"DuplicateVendorPredicate","Duplicate vendor primary key, enter a different number");
			$_SESSION['VendorName'] = ValidatePost("VendorName","Vendor name",$vendorDataValidation,$vendorValidationMessage);
			$_SESSION['Address1'] = ValidatePost("Address1","Address 1",$vendorDataValidation,$vendorValidationMessage);
			$_SESSION['Address2'] = ValidatePost("Address2","Address 2",$vendorDataValidation,$vendorValidationMessage,true);
			$_SESSION['City'] = ValidatePost("City","City",$vendorDataValidation,$vendorValidationMessage);
			$_SESSION['Prov'] = ValidatePost("Prov","Province",$vendorDataValidation,$vendorValidationMessage);
			$_SESSION['PostCode'] = ValidatePost("PostCode","Postal code",$vendorDataValidation,$vendorValidationMessage);
			$_SESSION['Country'] = ValidatePost("Country","Country",$vendorDataValidation,$vendorValidationMessage);
			$_SESSION['Phone'] = ValidatePost("Phone","Phone number",$vendorDataValidation,$vendorValidationMessage);
			$_SESSION['Fax'] = ValidatePost("Fax","Fax number",$vendorDataValidation,$vendorValidationMessage);
		} else if (isset($_POST['VendorQuery'])){
			$_SESSION['Q_Description'] = ValidatePost("Q_Description","Part description",$vendorQueryDataValidation,$vendorQueryValidationMessage);
		}
		if (count($errors)==0){
			if (isset($_POST['Parts'])){
				$_SESSION['PartFormValid'] = true;
				header('Location: parts.php');
			} elseif (isset($_POST['Vendors'])){
				$_SESSION['VendorFormValid'] = true;
				header('Location: vendors.php');
			} elseif (isset($_POST['VendorQuery'])){
				$_SESSION['VendorQueryFormValid'] = true;
				header('Location: parameter.php');
			}
		} 
    } // END IF POST
?>

<div class="jumbotron">
	<div class="container">
		<h1>Welcome!</h1>
		<p>Click on the tabs below to insert records into the parts and vendors tables, as well as query the database for vendors by part description.</p>

		<div class="alert alert-dismissible alert-warning">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<p>
				<strong> Heads up! </strong>Each tab contains a form to fill out relevant information. 
				Fields that are <strong>required</strong> or have certain rules will give <strong>errors</strong> if incorrectly filled out.
			</p>
		</div>
		
		<p>
			<a href ="#page"class="btn btn-primary btn-lg">Lets go!</a>
		</p>
	</div>
</div>
<div id="page" class="container">

	<div role="tabpanel">
		<!-- Nav tabs -->
		<ul class="nav nav-tabs" role="tablist">
			<li role="presentation" class="<?php echo (isset($_POST['Vendors']) || isset($_POST['VendorQuery'])) ? '' : 'active' ?>">
				<a href="#Parts" aria-controls="Parts" role="tab" data-toggle="tab">Add new Parts</a>
			</li>
			<li role="presentation" class="<?php echo isset($_POST['Vendors'])? 'active' : '' ?>">
				<a href="#Vendors" aria-controls="tab" role="tab" data-toggle="tab">Add new Vendors</a>
			</li>
			<li role="presentation" class="<?php echo isset($_POST['VendorQuery'])? 'active' : '' ?>">
				<a href="#VendorQuery" aria-controls="tab" role="tab" data-toggle="tab">Vendor query by part description</a>
			</li>
		</ul>

		<!-- Tab panes -->
		<div class="tab-content">
			<div role="tabpanel" class="<?php echo (isset($_POST['Vendors']) || isset($_POST['VendorQuery'])) ? 'tab-pane' : 'tab-pane active' ?>" id="Parts">
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

			<div role="tabpanel" class="<?php echo isset($_POST['Vendors'])? 'tab-pane active' : 'tab-pane' ?>" id="Vendors" >
				<div class="well">
					<div id="VendorsFormErrors" class="errors">
						<?php
						if(count($errors)>0 && isset($_POST['Vendors'])){
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
					<form action="index.php" method="POST" role="form" id="VendorsForm">
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

			<div role="tabpanel" class="<?php echo isset($_POST['VendorQuery'])? 'tab-pane active' : 'tab-pane' ?>" id="VendorQuery" >
				<div class="well">
					<div id="VendorQueryFormErrors" class="errors">
						<?php
						if(count($errors)>0 && isset($_POST['VendorQuery'])){
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
					<form action="index.php" method="POST" role="form" id="VendorQueryForm">
						<legend><h2>Query Vendor names by Part description</h2></legend>
						
						<div class="form-group row">
							<label class="col-md-2" for="Q_Description">Part Description</label>
							<div class="col-md-10">
								<input type="text" class="form-control" id="Q_Description" name="Q_Description"  value="<?php echo StickyForm('Q_Description') ?>">
								<?php
								ValidationSummaryFor("Q_Description","VendorQuery");
								?>
							</div>
						</div>
						
						<button type="submit" name="VendorQuery" class="btn btn-primary">Submit</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
	include('includes/footer.html');	
?>



