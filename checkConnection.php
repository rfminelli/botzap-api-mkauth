<?php 	
	//START API CHECK 32
	header('Content-Type: application/json; charset=utf8');
	require("config.php");
	
	$msg = "Oops! Acesso negado";
	$erro = true;
	$con = mysqli_connect(SERVER, DB_USER, DB_PASSWORD, DB_NAME);
	
    if (mysqli_connect_errno()){
		$msg = "Oops! Erro ao conectar com banco de dados!";
		$erro = true;
		$arrayRet = array( 
			"msg" => $msg,
			"erro" => $erro
		);
		echo json_encode_unicode($arrayRet);
		return;
    }
	
	if(!isset($_GET["token"])){
		$msg = "Oops! Faltando parametros na requisição HTTP!";
		$erro = true;
		$arrayRet = array( 
			"msg" => $msg,
			"erro" => $erro
		);
		echo json_encode_unicode($arrayRet);
		return;
	}
	
	$token = $_GET["token"];
	if(strcasecmp($token, MY_TOKEN) != 0){
		$msg = "Oops! Acesso negado. Token não identificado!";
		$erro = true;
		$arrayRet = array( 
			"msg" => $msg,
			"erro" => $erro
		);
		echo json_encode_unicode($arrayRet);
		return;
	}
	
	$msg = "Conectado com sucesso!";
	$erro = false;
	$arrayRet = array( 
		"msg" => $msg,
		"erro" => $erro
	);
	echo json_encode_unicode($arrayRet);
    mysqli_free_result($result);
    mysqli_close($con);
 ?>