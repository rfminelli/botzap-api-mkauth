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
	
	if(!isset($_GET["token"]) || !isset($_GET["login"]) || !isset($_GET["dias"])){
		$arrayRet = array( 
			"msg" => $msg,
			"erro" => $erro
		);
		echo json_encode_unicode($arrayRet);
		return;
	}
	
	
	$token = $_GET["token"];
	$login = $_GET["login"];
	$dias = $_GET["dias"];
	if(strcasecmp($token, MY_TOKEN) != 0){
		$arrayRet = array( 
			"msg" => "Broken token!",
			"erro" => $erro
		);
		echo json_encode_unicode($arrayRet);
		return;
	}
	
	$sql = "UPDATE sis_cliente SET observacao = 'sim', rem_obs = NOW()+INTERVAL $dias DAY, bloqueado = 'nao', data_bloq = NULL, data_desbloq = NOW() WHERE login = '$login'"; //NOW())";
	if(mysqli_query($con, $sql) === TRUE){
		$erro = false;
		$msg = "Pronto! Tudo feito.";
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