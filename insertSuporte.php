<?php 	
	//START API CHECK
	header('Content-Type: application/json; charset=utf8');
	require("config.php");
	$con = mysqli_connect(SERVER, DB_USER, DB_PASSWORD, DB_NAME);
    if (mysqli_connect_errno())
    {
       echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
	
	mysqli_query($con, "SET NAMES utf8");
	
	$erro = true;
	$msg = "Oops! Acesso negado";
	
	if(!isset($_GET["token"]) || !isset($_GET["chamado"]) || !isset($_GET["nome"]) || !isset($_GET["login"]) || !isset($_GET["email"]) || !isset($_GET["assunto"]) 
	   || !isset($_GET["atendente"]) || !isset($_GET["login_atend"])){
		$arrayRet = array( 
			"msg" => $msg,
			"erro" => $erro
		);
		echo json_encode_unicode($arrayRet);
		return;
	}
	
	
	$token = $_GET["token"];
	$chamado = $_GET["chamado"];
	$nome = $_GET["nome"];
	$login = $_GET["login"];
	$email = $_GET["email"];
	$assunto = $_GET["assunto"];
	$atendente = $_GET["atendente"];
	$login_atend = $_GET["login_atend"];
	if(strcasecmp($token, MY_TOKEN) != 0){
		$arrayRet = array( 
			"msg" => $msg,
			"erro" => $erro
		);
		echo json_encode_unicode($arrayRet);
		return;
	}
	
	$sql = "INSERT INTO sis_suporte (chamado, nome, login, email, assunto, atendente, login_atend, abertura, visita) 
	        values ('$chamado', '$nome', '$login', '$email', '$assunto', '$atendente', '$login_atend', NOW(), NOW() + INTERVAL 2 DAY);"; //NOW())";
	if(mysqli_query($con, $sql) === TRUE){
		$erro = false;
		$msg = "O seu *chamado*, *$assunto*, foi enviado com sucesso. Logo, logo entraremos em contato com você, *$nome*. " .
				"Este é o *número de protocolo* do seu *chamado*: _*$chamado*_";
	}else{
		$erro = true;
		$msg = "Oops! Erro ao inserir no banco de dados!";
	}
	
	$arrayRet = array( 
		"msg" => $msg,
		"erro" => $erro
	);
	
	//echo json_encode($arrayRet, JSON_UNESCAPED_UNICODE);
	echo json_encode_unicode($arrayRet);
    mysqli_free_result($result);
    mysqli_close($con);
 ?>