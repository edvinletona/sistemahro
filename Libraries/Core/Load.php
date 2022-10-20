<?php 
	//load
    $controller = ucwords($controller); //coloca la primera letra en mayuscula
	$controllerFile = "Controllers/".$controller.".php";
	if (file_exists($controllerFile)) 
	{
		require_once($controllerFile);
		$controller = new $controller();
		if (method_exists($controller, $method)) //validar el metodo
		{
			$controller->{$method}($params);
		}else{
			require_once("controllers/Error.php");  //dirigirse al metodo de pagina no encontrada
		}
	
	}else{
		require_once("controllers/Error.php");
	}


 ?>

