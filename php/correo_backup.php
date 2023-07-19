<?php
ini_set( 'display_errors', 1 );
error_reporting( E_ALL );

require __DIR__. '/PHPMailer/src/Exception.php';
require __DIR__. '/PHPMailer/src/PHPMailer.php';
require __DIR__. '/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
		//Server settings
		$mail->SMTPDebug =0; // SMTP::DEBUG_SERVER;                      //Enable verbose debug output
		$mail->isSMTP();                                            //Send using SMTP
		$mail->Host       = '';                     //Set the SMTP server to send through
		$mail->SMTPAuth   = true;                                   //Enable SMTP authentication
		$mail->Username   = '';                     //SMTP username
		$mail->Password   = '';                               //SMTP password
		$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
		$mail->Port       = 465;                                   //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

		//Recipients
		$mail->setFrom('', "Grupo Tecnológico");
		$mail->addAddress($_POST['correo']);     //Add a recipient
		$mail->addCC('');     //Add a recipient
		
		//Content
		$mail->isHTML(true);                                  //Set email format to HTML
		$serie = $_POST['serie']==""? 'Interno': $_POST['serie'];
		$mail->Subject = 'Comprobante de compra '. $serie .'-'.$_POST['correlativo'];
		$mail->Body    = '
		<table width="100%" height="100%" style="min-width:348px" border="0" cellspacing="0" cellpadding="0" lang="es">
		<tbody>
			<tr>
			<td align="center">
				<table border="0" cellspacing="0" cellpadding="0" style="padding-bottom:20px;max-width:516px;min-width:220px">
				<tbody>
					<tr>
						<td width="8" style="width:8px"></td>
						<td align="center">
							<div style="border-style:solid;border-width:thin;border-color:#dadce0;border-radius:8px;padding:20px 20px; font-family:Roboto,RobotoDraft,Helvetica,Arial,sans-serif;">
								<div style="text-align:center;"><img src="https://grupotecnologicoperu.com/facturador/images/empresa.png" style="width: 150px;"></div>
								<h1 style="text-align:center;">Grupo Tecnológico C&C S.A.C.<h1>
								<div style="font-family:Roboto-Regular,Helvetica,Arial,sans-serif;font-size:14px;color:rgba(0,0,0,0.87);line-height:20px;padding:0px 20px;font-weight: 400;text-align:left;">
									<p>Tenga buen día. Se ha procesado exitosamente su comprobante de pago por la compra en nuestro establecimiento: </p>
									<p><strong>Serie</strong>: '.$serie.'</p>
									<p><strong>Correlativo</strong>: '.$_POST['correlativo'].' </p>
									<div style="padding-top:5px;text-align:center">
										<a href="https://grupotecnologicoperu.com/facturador/ticket.php?serie='.$_POST['serie'].'&correlativo='.$_POST['correlativo'].'" target="_blank" style="line-height: 16px;color: #ffffff;font-weight: 400;text-decoration: none;font-size: 14px;display: inline-block;padding: 10px 24px;background-color: #0e5de1;border-radius: 5px;	min-width: 90px;">Ver documento</a>
									</div>
									<div>
										<p><small>Este es un sistema automático de aviso, por favor no responda este mensaje.</small></p>
									</div>
									
								</div>
							</div>
						</td>
						<td width="8" style="width:8px"></td>
					</tr>
				</tbody>
				</table>
			</td>
			</tr>
		</tbody>
		</table>
	
		';
		$mail->CharSet = 'UTF-8';
		$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

		$mail->send();
		echo 'Mensaje entregado';
} catch (Exception $e) {
		echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}