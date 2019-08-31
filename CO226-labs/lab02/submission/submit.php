<!DOCTYPE html>
<html>
<head>
	<title>Submission</title>
</head>

<body>

<?php
	//print_r($_POST);

	$size = $_POST['size'];
	$color = $_POST['color']; 
	$extraItem = $_POST['extra'];

	$firstName = $_POST['firstName'];
	$lastName  = $_POST['lastName'];

	$address1 = $_POST['address1'];
	$address2 = $_POST['address2'];
	$address3 = $_POST['address3'];
?>

<center>
	<div style="width:600px;border:3px solid; padding:5px 15px; margin:50px; text-align: left;">
		<h1>Your Information System</h1>

		<?php
			echo "<p>Thank you, $firstName for your purchase from our web site.</p>";
			echo "<p>Your item color is : $color & T-Shirt size : $size </p>";

			if(isset($_POST['extraCap']) || isset($_POST['extraWristBand'])){

				echo "<p>Selected items/item are :</p>";
				echo "<ul>";
				if(isset($_POST['extraCap']))echo "<li>".$_POST['extraCap']."</li>";
				if(isset($_POST['extraWristBand'])) echo "<li>".$_POST['extraWristBand']."</li>";
				echo "</ul>";
			}
		?>

		<p>Your items will be sent to :</p>

		<div style="margin-left: 10px;"><i>
			<?php
				echo "$firstName $lastName<br>";
				echo "$address1<br>$address2<br>$address3";
			?>
		</i></div>

		<?php
			if (isset($_POST['comments'])){
				$comments = $_POST['comments'];
				echo "<p>Thank you for submitting your comments. We appreciate it. You said:<br>
						<div style='margin-left: 10px;'><i>$comments</i></div><br></p>";	
			}
		?>
		
	</div>
</center>
</body>
</html>
