<?php
    class Servicios extends Controllers{
            public function __construct()
            {
                parent::__construct();
                session_start();
                session_regenerate_id(true);
                if(empty($_SESSION['login']))
                {
                    header('Location: '.base_url().'/login');
                }
                getPermisos(MSERVICIOS); //id de la tabla modulos
            }

            public function Servicios()
            {
                if(empty($_SESSION['permisosMod']['r'])){
                    header("Location:".base_url().'/dashboard');
                }
                $data['page_tag'] = "Servicios";
                $data['page_title'] = "SERVICIOS <small>Atención Al Usuario</small>";
                $data['page_name'] = "servicios";
                $data['page_functions_js'] = "functions_servicios.js";
                $this->views->getView($this,"servicios",$data);
            }


            public function setServicio(){
                if($_POST){			
                    if(empty($_POST['txtNombre']) || empty($_POST['listStatus']))
                    {
                        $arrResponse = array("status" => false, "msg" => 'Datos incorrectos.');
                    }else{ 
                        $intIdServicio = intval($_POST['idServicio']);				
                        $strServicio = ucwords(strClean($_POST['txtNombre']));                       
                        $intStatus = intval(strClean($_POST['listStatus']));
                        $request_servicio = "";
                        if($intIdServicio == 0)
                        {
                            $option = 1;						
                            if($_SESSION['permisosMod']['w']){
                                $request_servicio = $this->model->insertServicio(	$strServicio,                                                                                      
                                                                                    $intStatus );
                            }
                        }else{
                            $option = 2;						
                            if($_SESSION['permisosMod']['u']){							
                                $request_servicio = $this->model->updateServicio($intIdServicio,                                                                           
                                                                                  $intStatus);
                            }
    
                        }
    
                        if($request_servicio > 0 )
                        {
                            if($option == 1){
                                $arrResponse = array('status' => true, 'msg' => 'Datos guardados correctamente.');
                            }else{
                                $arrResponse = array('status' => true, 'msg' => 'Datos Actualizados correctamente.');
                            }
                        }else if($request_servicio == 'exist'){
                            $arrResponse = array('status' => false, 'msg' => '¡Atención! Servicio ya existe, ingrese otro.');		
                        }else{
                            $arrResponse = array("status" => false, "msg" => 'No es posible almacenar los datos.');
                        }
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
                die();
            }
            
          
            public function getServicios()
            {
                if($_SESSION['permisosMod']['r']){
                    $arrData = $this->model->selectServicios();
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
                            $btnView = '<button class="btn btn-info btn-sm" onClick="fntViewServicios('.$arrData[$i]['idservicio'].')" title="Ver servicios"><i class="far fa-eye"></i></button>';
                        }
                        if($_SESSION['permisosMod']['u']){
                            $btnEdit = '<button class="btn btn-primary  btn-sm" onClick="fntEditServicios(this,'.$arrData[$i]['idservicio'].')" title="Editar servicios"><i class="fas fa-pencil-alt"></i></button>';
                        }
                        if($_SESSION['permisosMod']['d']){	
                            $btnDelete = '<button class="btn btn-danger btn-sm" onClick="fntDelServicios('.$arrData[$i]['idservicio'].')" title="Eliminar servicio"><i class="far fa-trash-alt"></i></button>';
                        }
                        $arrData[$i]['options'] = '<div class="text-center">'.$btnView.' '.$btnEdit.' '.$btnDelete.'</div>';
                    }
                    echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                }
                die();
            }

		public function getServicio($idservicio)
		{
			if($_SESSION['permisosMod']['r']){
				$intIdServicio = intval($idservicio);
				if($intIdServicio > 0)
				{
					$arrData = $this->model->selectServicio($intIdServicio);
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

		public function delServicio()
		{
			if($_POST){
				if($_SESSION['permisosMod']['d']){
					$intIdServicio = intval($_POST['idservicio']);
					$requestDelete = $this->model->deleteServicio($intIdServicio);
					if($requestDelete == 'ok')
					{
						$arrResponse = array('status' => true, 'msg' => 'Se ha eliminado el servicio');
					}else if($requestDelete == 'exist'){
						$arrResponse = array('status' => false, 'msg' => 'No es posible eliminar un servcio con persona asociada.');
					}else{
						$arrResponse = array('status' => false, 'msg' => 'Error al eliminar el servicio.');
					}
					echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
				}
			}
			die();
		}

		public function getSelectServicios(){
			$htmlOptions = "";
			$arrData = $this->model->selectServicios();
			if(count($arrData) > 0 ){
				for ($i=0; $i < count($arrData); $i++) { 
					if($arrData[$i]['status'] == 1 ){
					$htmlOptions .= '<option value="'.$arrData[$i]['idservicio'].'">'.$arrData[$i]['nombreservicio'].'</option>';
					}
				}
			}
			echo $htmlOptions;
			die();	
		}    


    }

?>