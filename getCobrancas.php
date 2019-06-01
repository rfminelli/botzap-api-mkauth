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
	$sql= "SELECT login, celular, nossonum, titulo, descricao, datavenc, linhadig, valor 
			FROM vtab_titulos 
			WHERE (DATE(datavenc) BETWEEN  DATE(NOW()) - INTERVAL 5 DAY AND DATE(NOW())) AND status != 'pago' AND `deltitulo` = 0 ORDER BY datavenc DESC;";
	$result = mysqli_query($con ,$sql);
	$response = array();
	while ($row = mysqli_fetch_assoc($result)) {
		//retirar	
		$row["login"] = $row["login"];
		$row["celular"] = $row["celular"];
		$row["titulo"] = $row["titulo"];
		$row["descricao"] = $row["descricao"];
		$row["datavenc"] = $row["datavenc"];
		if(is_null($row["linhadig"]))
			$row["linhadig"] = "";
		else
			$row["linhadig"] = $row["linhadig"];
		$row["valor"] = $row["valor"];
		
		$response[] = $row;
	}
	
	$erro = false;
	$arrayRet = array( 
		"listaClientesInadimplentes" => $response,
		"msg" => "success",
		"erro" => $erro
	);
	
	//echo json_encode($arrayRet, JSON_UNESCAPED_UNICODE);
	echo json_encode_unicode($arrayRet);
    mysqli_free_result($result);
    mysqli_close($con);
 ?>