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
	if(!isset($_GET["token"])){
		$arrayRet = array( 
			"msg" => $msg,
			"erro" => $erro
		);
		echo json_encode_unicode($arrayRet);
		return;
	}
	
	$token = $_GET["token"];
	if(strcasecmp($token, MY_TOKEN) != 0){
		$arrayRet = array( 
			"msg" => $msg,
			"erro" => $erro
		);
		echo json_encode_unicode($arrayRet);
		return;
	}
	
	//END API CHECK	
	$sql= "SELECT SUP.id, SUP.chamado, CLI.nome, CLI.login, CLI.email, SUP.assunto, SUP.status as status, SUP.abertura, SUP.visita, SUP.atendente, SUP.login_atend 
		   FROM sis_suporte AS SUP, sis_cliente AS CLI 
		   WHERE CLI.login = SUP.login AND CLI.cli_ativado = 's' ORDER BY id DESC LIMIT 1;";
	
	$result = mysqli_query($con ,$sql);
	$response = array();
	while ($row = mysqli_fetch_assoc($result)) {
		//retirar	
		$row["id"] = $row["id"];
		$row["chamado"] = $row["chamado"];
		$row["nome"] = $row["nome"];
		$row["login"] = $row["login"];
		$row["email"] = $row["email"];
		$row["assunto"] = $row["assunto"];
		$row["atendente"] = $row["atendente"];
		$row["login_atend"] = $row["login_atend"];
		$row["abertura"] = $row["abertura"];
		$row["visita"] = $row["visita"];
		$row['status'] = "aberto";
		
		$response[] = $row;
	}
	
	$erro = false;
	$arrayRet = array( 
		"listaSuportes" => $response,
		"msg" => "success",
		"erro" => $erro
	);
	
	//echo json_encode($arrayRet, JSON_UNESCAPED_UNICODE);
	echo json_encode_unicode($arrayRet);
    mysqli_free_result($result);
    mysqli_close($con);
 ?>