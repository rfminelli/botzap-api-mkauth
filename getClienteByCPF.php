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
	if(!isset($_GET["token"]) || !isset($_GET["cpf"])){
		$arrayRet = array( 
			"msg" => $msg,
			"erro" => $erro
		);
		echo json_encode_unicode($arrayRet);
		return;
	}
	
	$token = $_GET["token"];
	$cpf = $_GET["cpf"];
	if(strcasecmp($token, MY_TOKEN) != 0){
		$arrayRet = array( 
			"msg" => $msg,
			"erro" => $erro
		);
		echo json_encode_unicode($arrayRet);
		return;
	}
	
	//END API CHECK	
	$sql= "SELECT id, nome, cadastro, login, senha, email, venc AS vencimento, observacao, rem_obs, bloqueado, NOW() AS dataHora, plano
		   FROM sis_cliente
		   WHERE cpf_cnpj = '".$cpf."' AND cli_ativado = 's';";
	
	$result = mysqli_query($con ,$sql);
	$response = array();
	while ($row = mysqli_fetch_assoc($result)) {
		//retirar	
		$row["id"] = $row["id"];
		$row["nome"] = $row["nome"];
		$row["cadastro"] = $row["cadastro"];
		$row["login"] = $row["login"];
		$row["senha"] = $row["senha"];
		$row["email"] = $row["email"];
		$row["vencimento"] = $row["vencimento"];
		$row["observacao"] = $row["observacao"];
		$row["rem_obs"] = $row["rem_obs"];
		$row["bloqueado"] = $row["bloqueado"];
		$row["dataHora"] = $row["dataHora"];
		$row["plano"] = $row["plano"];
		
		$sqlFaturas = "SELECT datavenc AS vencimento, linhadig, valor FROM sis_lanc WHERE (status = 'aberto' OR status = 'vencido') AND login = '".$row["login"]."';";
		$resultFaturas = mysqli_query($con, $sqlFaturas);
		$faturasArray = array();
		while ($faturas = mysqli_fetch_assoc($resultFaturas)) {
			$faturasArray[] = array_map('utf8_encode', $faturas);
		}
		
		$row["faturas"] = $faturasArray;
		$response[] = $row;
	}
	
	$erro = false;
	$arrayRet = array( 
		"cliente" => $response,
		"msg" => "success",
		"erro" => $erro
	);
	
	//echo json_encode($arrayRet, JSON_UNESCAPED_UNICODE);
	echo json_encode_unicode($arrayRet);
    mysqli_free_result($result);
    mysqli_close($con);
 ?>