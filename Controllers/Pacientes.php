<?php 

class Pacientes extends Controllers{
	public function __construct()
	{
		parent::__construct();
		session_start();
		session_regenerate_id(true);
		if(empty($_SESSION['login']))
		{
			header('Location: '.base_url().'/login');
		}
		getPermisos(MPACIENTES);
	}

	public function Pacientes()
	{
		if(empty($_SESSION['permisosMod']['r'])){
			header("Location:".base_url().'/dashboard');
		}
		$data['page_tag'] = "Pacientes";
		$data['page_title'] = "PACIENTES <small>Atención al Paciente</small>";
		$data['page_name'] = "pacientes";
		$data['page_functions_js'] = "functions_pacientes.js";
		$this->views->getView($this,"pacientes",$data);
	}

	public function setPaciente(){
		error_reporting(0);
		if($_POST){
			if(empty($_POST['txtIdentificacion']) || empty($_POST['txtNombre']) || empty($_POST['txtApellido']) || empty($_POST['txtEdad']) || empty($_POST['txtTelefono']) || empty($_POST['txtDireccion']) || empty($_POST['txtResponsable'])|| empty($_POST['txtTelefonoResp']))
			{
				$arrResponse = array("status" => false, "msg" => 'Datos incorrectos.');
			}else{ 
				$idUsuario = intval($_POST['idUsuario']);
				$strIdentificacion = strClean($_POST['txtIdentificacion']);
				$strNombre = ucwords(strClean($_POST['txtNombre']));
				$strApellido = ucwords(strClean($_POST['txtApellido']));
				$intEdad = intval(strClean($_POST['txtEdad']));
				$intTelefono = intval(strClean($_POST['txtTelefono']));				
				$strDireccion = (strClean($_POST['txtDireccion']));
				$strResponsable = ucwords(strClean($_POST['txtResponsable']));
				$intTelefonoResp = intval(strClean($_POST['txtTelefonoResp']));
				$intTipoId = 5; //id del paciente en bd
				$request_user = "";
				if($idUsuario == 0)
				{
					$option = 1;
					
					if($_SESSION['permisosMod']['w']){
						$request_user = $this->model->insertPaciente($strIdentificacion,
																			$strNombre, 
																			$strApellido,
																			$intEdad, 
																			$intTelefono, 																			
																			$strDireccion,
																			$strResponsable,
																			$intTelefonoResp,																			
																			$intTipoId 
																			 );
					}
				}else{
					$option = 2;
					
					if($_SESSION['permisosMod']['u']){
						$request_user = $this->model->updatePaciente($idUsuario,
																	$strIdentificacion, 
																	$strNombre,
																	$strApellido,
																	$intEdad,  
																	$intTelefono, 																	
																	$strDireccion,
																	$strResponsable,
																	$intTelefonoResp
																	);
					}
				}

				if($request_user > 0 )
				{
					if($option == 1){
						$arrResponse = array('status' => true, 'msg' => 'Datos guardados correctamente.');
						
					}else{
						$arrResponse = array('status' => true, 'msg' => 'Datos Actualizados correctamente.');
					}
				}else if($request_user == 'exist'){
					$arrResponse = array('status' => false, 'msg' => '¡Atención! la identificación ya existe, ingrese otro.');		
				}else{
					$arrResponse = array("status" => false, "msg" => 'No es posible almacenar los datos.');
				}
			}
			echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
		}
		die();
	}

	public function getPacientes()
	{
		if($_SESSION['permisosMod']['r']){
			$arrData = $this->model->selectPacientes();
			for ($i=0; $i < count($arrData); $i++) {
				$btnView = '';
				$btnEdit = '';
				$btnDelete = '';
				if($_SESSION['permisosMod']['r']){
					$btnView = '<button class="btn btn-info btn-sm" onClick="fntViewInfo('.$arrData[$i]['idpersona'].')" title="Ver paciente"><i class="far fa-eye"></i></button>';
				}
				if($_SESSION['permisosMod']['u']){
					$btnEdit = '<button class="btn btn-primary  btn-sm" onClick="fntEditInfo(this,'.$arrData[$i]['idpersona'].')" title="Editar paciente"><i class="fas fa-pencil-alt"></i></button>';
				}
				if($_SESSION['permisosMod']['d']){	
					$btnDelete = '<button class="btn btn-danger btn-sm" onClick="fntDelInfo('.$arrData[$i]['idpersona'].')" title="Eliminar paciente"><i class="far fa-trash-alt"></i></button>';
				}
				$arrData[$i]['options'] = '<div class="text-center">'.$btnView.' '.$btnEdit.' '.$btnDelete.'</div>';
			}
			echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
		}
		die();
	}

	public function getPaciente($idpersona){
		if($_SESSION['permisosMod']['r']){
			$idusuario = intval($idpersona);
			if($idusuario > 0)
			{
				$arrData = $this->model->selectPaciente($idusuario);
				if(empty($arrData))
				{
					$arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
				}else{
					$arrResponse = array('status' => true, 'data' => $arrData);
				}
				echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
			}
		}
		die();
	}

	public function delPaciente()
	{
		if($_POST){
			if($_SESSION['permisosMod']['d']){
				$intIdpersona = intval($_POST['idUsuario']);
				$requestDelete = $this->model->deletePaciente($intIdpersona);
				if($requestDelete)
				{
					$arrResponse = array('status' => true, 'msg' => 'Se ha eliminado al paciente ');
				}else{
					$arrResponse = array('status' => false, 'msg' => 'Error al eliminar al paciente.');
				}
				echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
			}
		}
		die();
	}



}

?>