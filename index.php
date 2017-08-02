
<?php
	include('includes/header.html');
	require('connection.php');

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
		<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="POST" role="form">
			<legend><h2>Insert record into the Parts table</h2></legend>
			
			<div class="form-group row">
				<label class="col-md-2" for="VendorNo">Vendor</label>
				<div class="col-md-10">
					<select name="VendorNo" id="VendorNo" class="form-control" required="required">
						<option value="">Select a vendor</option>
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
			<button type="submit" class="btn btn-primary">Submit</button>
		</form>
	</div>
</div>

<?php
	include('includes/footer.html');	
?>



