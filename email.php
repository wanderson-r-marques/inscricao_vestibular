<?php



function email($email, $nome, $cpf)
{



	//Envia e-mail para o aluno

	$checaEmail = explode("@", $email);
	$checaEmail2 = $checaEmail[1];
	$inicias = substr($checaEmail2, 0, 3);
	$de = 'inscricao@isolucoes.inf.br';
	$assunto = "Cadastro no Processo Seletivo de Palmares";
	$corpo = '<div class="section">
	<div class="container">
	<div class="row">
	<div class="col-md-12">
	<p style="text-align: center;">
	<a href="#"><img class="center-block img-responsive" src="https://palmares.saude.isolucoes.inf.br/images/logo_aemasul.png" /></a></p>
	<div class="page-header text-center">
	<h1 style="text-align: center;">
	<span style="color:#006400;"><small>PARABÉNS </small></span>' . $nome . '</h1>
	</div>
	<p class="text-center" style="text-align: center;">
	Estamos confirmando a solicitação de isenção ao  
PROCESSO SELETIVO SIMPLIFICADO SECRETARIA MUNICIPAL DE SAÚDE DO MUNICÍPIO DE PALMARES Nº 001/2021

Nome: ' . $nome . '
cpf: ' . $cpf . '<br /><br />						
	</p>
	</div>
	</div>
	</div>
	';


	$mail = new PHPMailer;

	$mail->SMTPDebug = 3;

	// Enable verbose debug output

	$mail->isSMTP();
	$mail->CharSet = 'UTF-8'; // Set mailer to use SMTP
	$mail->Host = 'mail.isolucoes.inf.br';
	$mail->SMTPOptions = array(
		'ssl' => array(
			'verify_peer' => false,
			'verify_peer_name' => false,
			'allow_self_signed' => true
		)
	); // Specify main and backup SMTP servers
	$mail->SMTPAuth = true; // Enable SMTP authentication
	$mail->Username = 'inscricao@isolucoes.inf.br'; // SMTP username
	$mail->Password = 'infor2525@'; // SMTP password
	$mail->SMTPSecure = false; // Enable TLS encryption, `ssl` also accepted
	$mail->Port = 587; // TCP port to connect to

	$mail->setFrom('inscricao@isolucoes.inf.br', 'Inscrição Palmares');
	// Add a recipient

	$mail->addAddress($email); // Name is optional

	// Optional name
	$mail->isHTML(true); // Set email format to HTML

	$mail->Subject = $assunto;

	$mail->Body = $corpo;
	$mail->AltBody = '';

	if ($mail->send()) {
		echo 'enviado';
	} else {
		echo 'n enviado';
	}
}