<?php

	include("connection.php");

	function FillVendorTable()
	{

		$tableBodyText = "";

		$connection = ConnectToDatabase();

		$querySelect = "SELECT VendorName FROM Vendors";
		$preparedQuerySelect = $connection -> prepare($querySelect);
		$preparedQuerySelect -> execute();

		while ($row = $preparedQuerySelect -> fetch())
		{

			$vendorNo = "";
			$vendorName = $row['VendorName'];

			$tableBodyText .= "<tr>";
			$tableBodyText .= "<td>$vendorNo</td>";
			$tableBodyText .= "<td class='text'>$vendorName</td>";
			$tableBodyText .= "</tr>";

		}

		echo $tableBodyText;

	}


	function CreateVendorTableHeader()
	{

		$text = "<tr id='tableHeader'>";
		$text .= "<th>VendorNo</th>";
		$text .= "<th>VendorName</th>";
/*
		$text .= "<th>Address1</th>";
		$text .= "<th>Address2</th>";
		$text .= "<th>City</th>";
		$text .= "<th>Prov</th>";
		$text .= "<th>PostCode</th>";
		$text .= "<th>Count</th>";
		$text .= "<th>Phone</th>";
		$text .= "<th>Fax</th>";
*/
		$text .= "</tr>";

		echo $text;

	}

?>



