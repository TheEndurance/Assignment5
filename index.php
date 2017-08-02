<html>

	<head>

		<link rel="stylesheet" href="assignment5.css" />

		<?php

			require("vendorTableData.php");

		?>

	</head>

	<body>

		<table>

			<?php

				CreateVendorTableHeader();
				FillVendorTable();

			?>

		</table>

	</body>

</html>



