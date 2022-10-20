<?php 

	require_once("Config/Config.php");
	require_once("Helpers/Helpers.php");

	$url = !empty($_GET['url']) ? $_GET['url'] : 'home/home';
	$arrUrl = explode("/", $url);
	$controller = $arrUrl[0]; //cuando no venga data de la direccion tomara la posicion 0
	$method = $arrUrl[0];
	$params = "";

	if (!empty($arrUrl[1]))   //validamos que la posicion 1 lleve data
	 {
		if($arrUrl[1] != "")
		{
			$method = $arrUrl[1];
		}
	}

	if (!empty($arrUrl[2]))
	 {
		if ($arrUrl[2] != "") 
		{
			for ($i=2; $i < count($arrUrl); $i++) { 
				$params .=  $arrUrl[$i].',';
			}
			$params = trim($params,',');  //elimina la ultima coma de los parametros
		}
	}

	require_once("Libraries/Core/Autoload.php");

	require_once("Libraries/Core/Load.php");

 ?>