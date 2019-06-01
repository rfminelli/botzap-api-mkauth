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
	$sql= "SELECT nome, valor, velup AS upload, veldown AS download, REPLACE(REPLACE(descricao, '\r', ''), '\n', '<br>') AS descricao, oculto, desc_titulo FROM sis_plano WHERE oculto = 'nao' ORDER BY valor ASC;";
	
	$result = mysqli_query($con ,$sql);
	$response = array();
	while ($row = mysqli_fetch_assoc($result)) {
		//retirar	
		$row["nome"] = $row["nome"];
		$row["valor"] = $row["valor"];
		$row["upload"] = $row["upload"];
		$row["download"] = $row["download"];
		$row["descricao"] = $row["descricao"];
		$row["oculto"] = $row["oculto"];
		$row["desc_titulo"] = $row["desc_titulo"];
		
		$response[] = $row;
	}
	
	$erro = false;
	$arrayRet = array( 
		"listaPlanos" => $response,
		"msg" => "success",
		"erro" => $erro
	);
	
	//echo json_encode($arrayRet, JSON_UNESCAPED_UNICODE);
	echo json_encode_unicode($arrayRet);
    mysqli_free_result($result);
    mysqli_close($con);
 ?>