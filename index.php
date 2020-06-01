<?php

//index.php


$message = '';

$connect = new PDO("mysql:host=localhost;dbname=testing", "root", "");


function fetch_customer_data($connect)
{
	$query = "SELECT * FROM invoice";
	$statement = $connect->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();
	$output = '
	<div class="table-responsive">
		<table class="table table-striped table-bordered">
		<tr>
				<th>Abcde</th>
				<th>Mobile: ABC</th>
				
			</tr>	
		<tr>
				<th>Serial</th>
				<th>Medicine Name & Amount</th>
				<th>Generic Name</th>
				<th>Quantity</th>
				<th>Price</th>
			</tr>
	';
	foreach($result as $row)
	{
	 
		$output .= '



		
			<tr>
				<td>'.$row["m_id"].'</td>
				<td>'.$row["m_details"].'</td>
				<td>'.$row["generics"].'</td>
				<td>'.$row["quantity"].'</td>
				<td>'.$row["price"].'</td>
			</tr>
		';
	}
	$output .= '
		</table>
	</div>
	';
	return $output;
}

if(isset($_POST["action"]))
{
	include('pdf.php');
	$file_name = md5(rand()) . '.pdf';
	$html_code = '<link rel="stylesheet" href="bootstrap.min.css">';
	$html_code .= fetch_customer_data($connect);
	$pdf = new Pdf();
	$pdf->load_html($html_code);
	$pdf->render();
	$file = $pdf->output();
	file_put_contents($file_name, $file);
	
	require 'class/class.phpmailer.php';
	$mail = new PHPMailer;
	$mail->IsSMTP();								//Sets Mailer to send message using SMTP
	//$mail->Host = 'smtpout.secureserver.net';		//Sets the SMTP hosts of your Email hosting, this for Godaddy
	$mail->Host = 'smtp.gmail.com';
	//$mail->Port = '80';		
	$mail->Port = 465;								//Sets the default SMTP server port
	$mail->SMTPAuth = true;							//Sets SMTP authentication. Utilizes the Username and Password variables
	$mail->Username = '';					//Sets SMTP username
	$mail->Password = '';					//Sets SMTP password
	$mail->SMTPSecure = 'ssl';							//Sets connection prefix. Options are "", "ssl" or "tls"
	$mail->From = 'turjoahmed7420@gmail.com';			//Sets the From email address for the message
	$mail->FromName = 'Turjo ';			//Sets the From name of the message
	$mail->AddAddress('nahmed151086@bscse.uiu.ac.bd', 'Name');		//Adds a "To" address
	$mail->WordWrap = 50;							//Sets word wrapping on the body of the message to a given number of characters
	$mail->IsHTML(true);							//Sets message type to HTML				
	$mail->AddAttachment($file_name);     				//Adds an attachment from a path on the filesystem
	$mail->Subject = 'Invoice Testing By Tester Turjo';			//Sets the Subject of the message
	$mail->Body = 'Invoice Testing By Tester Turjo';				//An HTML or plain text message body
	if($mail->Send())								//Send an Email. Return true on success or false on error
	{
		$message = '<label class="text-success">Customer Details has been send successfully...</label>';
	}
	unlink($file_name);
}

?>
<!DOCTYPE html>
<html>
	<head>
		<title>Invoice Testing By Tester Turjo</title>
		<script src="jquery.min.js"></script>
		<link rel="stylesheet" href="bootstrap.min.css" />
		<script src="bootstrap.min.js"></script>
	</head>
	<body>
		<br />
		<div class="container">
			<h3 align="center">Invoice Testing By Tester Turjo</h3>
			<br />
			<form method="post">
				<input type="submit" name="action" class="btn btn-danger" value="Place Order" /><?php echo $message; ?>
			</form>
			<br />
			
			<?php
			echo fetch_customer_data($connect);
			?>			
		</div>
		<br />
		<br />
	</body>
</html>

