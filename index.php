
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
	* the <option> value as $valueName, the <option> description as $descriptionName
	* Returns a concatenated output of all of the options
	*/
	function DropdownListFor($theQuery,$valueName,$descriptionName){
		$output = "";
		while($row = $theQuery ->fetch()){
			$output .='<option value="' . $row[$valueName] . '">' . $row[$descriptionName] . '</option>';
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
	
	<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="POST" role="form">
		<legend>Form title</legend>
		
		<div class="form-group">
			<label for="VendorNo">Vendor</label>
			
			<select name="VendorNo" id="VendorNo" class="form-control" required="required">
				<option value="">Select a vendor</option>
				<?php
					echo DropdownListFor($vendorsQuery,"VendorNo","VendorName");	
				?>
			</select>
		</div>
		<div class="form-group">
			<label for="">label</label>
			<input type="text" class="form-control" id="" placeholder="Input field">
		</div>
	
		
	
		<button type="submit" class="btn btn-primary">Submit</button>
	</form>
	
</div>

<?php
	include('includes/footer.html');	
?>



