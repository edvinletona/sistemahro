<?php 

	class Insumos extends Controllers{
		public function __construct()
		{
			parent::__construct();
            session_start();
            session_regenerate_id(true);
            if(empty($_SESSION['login']))
			{
				header('Location: '.base_url().'/login');
			}
			getPermisos(MINSUMOS); //id del modulo
		}

		public function Insumos()
		{
			if(empty($_SESSION['permisosMod']['r'])){
				header("Location:".base_url().'/dashboard');
			}
			$data['page_tag'] = "Insumos";
			$data['page_title'] = "INSUMOS <small>Atención Al Usuario</small>";
			$data['page_name'] = "insumos";
			$data['page_functions_js'] = "functions_insumos.js";
			$this->views->getView($this,"insumos",$data);
		}

		public function setInsumo(){
			if($_POST){
				if(empty($_POST['txtNombre']) || empty($_POST['txtDescripcion']) || empty($_POST['listCategoria']) || empty($_POST['listStatus']) )
				{
					$arrResponse = array("status" => false, "msg" => 'Datos incorrectos.');
				}else{
					
					$IdInsumo = intval($_POST['idInsumo']);				
					$strNombre = ucwords(strClean($_POST['txtNombre']));
					$strDescripcion = ucwords(strClean($_POST['txtDescripcion']));					
					$intTipocategoria = intval(strClean($_POST['listCategoria']));
					$intStatus = intval(strClean($_POST['listStatus']));
					$request_insumo = "";

					$ruta = strtolower(clear_cadena($strNombre));
					$ruta =str_replace(" ","-",$ruta);

					if($IdInsumo == 0)
					{
						$option = 1;
						if($_SESSION['permisosMod']['w']){
							$request_insumo = $this->model->insertInsumo($strNombre, 
																		$strDescripcion, 																		
																		$intTipocategoria,	
																		$ruta,																	
																		$intStatus );
						}
					}else{
						$option = 2;
						if($_SESSION['permisosMod']['u']){
							$request_insumo = $this->model->updateInsumo($IdInsumo,
																		$strNombre,
																		$strDescripcion, 
																		$intTipocategoria,
																		$ruta,	
																		$intStatus);
						}
					}
					if($request_insumo > 0 )
					{
						if($option == 1){
							$arrResponse = array('status' => true, 'idinsumos' => $request_insumo, 'msg' => 'Datos guardados correctamente.');
						}else{
							$arrResponse = array('status' => true, 'idinsumos' => $IdInsumo, 'msg' => 'Datos Actualizados correctamente.');
						}
					}else if($request_insumo == 'exist'){
						$arrResponse = array('status' => false, 'msg' => '¡Atención! ya existe un Insumo con el Nombre Ingresado.');		
					}else{
						$arrResponse = array("status" => false, "msg" => 'No es posible almacenar los datos.');
					}
				}
				echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
			}
			die();
		}

		public function setInsumos(){
			if($_POST){			
				if(empty($_POST['txtNombre']) || empty($_POST['txtDescripcion']) || empty($_POST['listCategoria'])|| empty($_POST['listStatus']))
				{
					$arrResponse = array("status" => false, "msg" => 'Datos incorrectos.');
				}else{ 
					$IdInsumo = intval($_POST['idInsumo']);				
					$strNombre = ucwords(strClean($_POST['txtNombre']));
					$strDescripcion = ucwords(strClean($_POST['txtDescripcion']));					
					$intTipocategoria = intval(strClean($_POST['listCategoria']));
					$intStatus = intval(strClean($_POST['listStatus']));
					$request_insumo = "";
					if($IdInsumo == 0)
					{
						$option = 1;						
						if($_SESSION['permisosMod']['w']){
							$request_insumo = $this->model->insertInsumo(		$strNombre, 
																				$strDescripcion,																				
																				$intTipocategoria, 
																				$intStatus );
						}
					}else{
						$option = 2;						
						if($_SESSION['permisosMod']['u']){							
							$request_insumo = $this->model->updateInsumo($IdInsumo,
																		$strNombre, 
																		$strDescripcion,																				
																		$intTipocategoria, 
																		$intStatus);
						}

					}

					if($request_insumo > 0 )
					{
						if($option == 1){
							$arrResponse = array('status' => true, 'msg' => 'Datos guardados correctamente.');
						}else{
							$arrResponse = array('status' => true, 'msg' => 'Datos Actualizados correctamente.');
						}
					}else if($request_insumo == 'exist'){
						$arrResponse = array('status' => false, 'msg' => '¡Atención! Insumo ya existe, ingrese otro.');		
					}else{
						$arrResponse = array("status" => false, "msg" => 'No es posible almacenar los datos.');
					}
				}
				echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
			}
			die();
		}

		public function getInsumos()
		{
			if($_SESSION['permisosMod']['r']){
				$arrData = $this->model->selectInsumos();
				for ($i=0; $i < count($arrData); $i++) {
					$btnView = '';
					$btnEdit = '';
					$btnDelete = '';

					if($arrData[$i]['status'] == 1)
					{
						$arrData[$i]['status'] = '<span class="badge badge-success">Activo</span>';
					}else{
						$arrData[$i]['status'] = '<span class="badge badge-danger">Inactivo</span>';
					}

					if($_SESSION['permisosMod']['r']){
						$btnView = '<button class="btn btn-info btn-sm" onClick="fntViewInfo('.$arrData[$i]['idinsumos'].')" title="Ver insumo"><i class="far fa-eye"></i></button>';
					}
					if($_SESSION['permisosMod']['u']){
						$btnEdit = '<button class="btn btn-primary  btn-sm" onClick="fntEditInfo(this,'.$arrData[$i]['idinsumos'].')" title="Editar insumo"><i class="fas fa-pencil-alt"></i></button>';
					}
					if($_SESSION['permisosMod']['d']){	
						$btnDelete = '<button class="btn btn-danger btn-sm" onClick="fntDelInfo('.$arrData[$i]['idinsumos'].')" title="Eliminar insumo"><i class="far fa-trash-alt"></i></button>';
					}
					$arrData[$i]['options'] = '<div class="text-center">'.$btnView.' '.$btnEdit.' '.$btnDelete.'</div>';
										
				}
				echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
			}
			die();
		}

		public function getInsumo($idinsumos){
			if($_SESSION['permisosMod']['r']){
				$idinsumos = intval($idinsumos);
				if($idinsumos > 0){
					$arrData = $this->model->selectInsumo($idinsumos);
					
					if(empty($arrData)){
						$arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
					}else{
						$arrImg = $this->model->selectImages($idinsumos);
						
						if(count($arrImg) > 0){
							for ($i=0; $i < count($arrImg); $i++) { 
								$arrImg[$i]['url_image'] = media().'/images/uploads/'.$arrImg[$i]['img'];
							}
						}
						$arrData['images'] = $arrImg;
						$arrResponse = array('status' => true, 'data' => $arrData);
					}
						echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
				}
			}
			
			die();
		}


		public function delInsumo()
		{
			if($_POST){
				if($_SESSION['permisosMod']['d']){
					$intInsumo = intval($_POST['idInsumo']);
					$requestDelete = $this->model->deleteInsumo($intInsumo);
					if($requestDelete)
					{
						$arrResponse = array('status' => true, 'msg' => 'Se ha eliminado el insumo');
					}else{
						$arrResponse = array('status' => false, 'msg' => 'Error al eliminar el insumo.');
					}
					echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
				}
			}
			die();
		}
		

		public function setImage(){			
			if($_POST){
					if(empty($_POST['idinsumos'])){	
							
						
						$arrResponse = array('status' => false, 'msg' => 'Error de Dato.');
						//dep($_POST);
						//dep($_FILES);	
						
						
					}else{
						$idInsumo = intval($_POST['idinsumos']);					
						$foto      = $_FILES['foto'];
						$imgNombre = 'pro_'.md5(date('d-m-Y H:m:s')).'.jpg';
						$request_image = $this->model->insertImage($idInsumo,$imgNombre);
						if($request_image){
							$uploadImage = uploadImage($foto,$imgNombre);
							$arrResponse = array('status' => true, 'imgname' => $imgNombre, 'msg' => 'Archivo cargado.');
						}else{
							$arrResponse = array('status' => false, 'msg' => 'Error de carga.');
						}					
					}
					echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
				}			
			die();
		}
		
		public function delFile(){
			if($_POST){
				if(empty($_POST['idinsumos']) || empty($_POST['file'])){
					$arrResponse = array("status" => false, "msg" => 'Datos incorrectos.');
				}else{
					//Eliminar de la DB
					$idInsumo = intval($_POST['idinsumos']);
					$imgNombre  = strClean($_POST['file']);
					$request_image = $this->model->deleteImage($idInsumo,$imgNombre);

					if($request_image){
						$deleteFile =  deleteFile($imgNombre);
						$arrResponse = array('status' => true, 'msg' => 'Archivo eliminado');
					}else{
						$arrResponse = array('status' => false, 'msg' => 'Error al eliminar');
					}
				}
				echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
			}
			die();
		}
	}
 ?>