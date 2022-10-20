<?php 
	require_once("Models/TCategoria.php");
	require_once("Models/TInsumo.php");

	class Tienda extends Controllers{
		use TCategoria, TInsumo;
		public function __construct()
		{
			parent::__construct();
		}

		public function tienda()
		{
			$data['page_tag'] = NOMBRE_EMPRESA;
			$data['page_title'] = NOMBRE_EMPRESA;
			$data['page_name'] = "tienda";
			$data['insumos'] = $this->getInsumosT();
			$this->views->getView($this,"tienda",$data);
		}

		public function categoria($params){
			if(empty($params)){
				header("Location:".base_url());
			}else{
				$arrParams = explode(",",$params);
				$idcategoria = intval($arrParams[0]);
				$ruta = strClean($arrParams[1]);
				
				$infoCategoria = $this->getInsumosCategoriaT($idcategoria,$ruta);
				$categoria = strClean($params);
				$data['page_tag'] = NOMBRE_EMPRESA." - ".$infoCategoria['categoria'];
				$data['page_title'] = $infoCategoria['categoria'];
				$data['page_name'] = "categoria";
				$data['insumos'] = $infoCategoria['insumos'];
				$this->views->getView($this,"categoria",$data);
			}
		}

		public function insumo($params){
			if(empty($params)){
				header("Location:".base_url());
			}else{
				$arrParams = explode(",",$params);
				$idinsumos = intval($arrParams[0]);
				$ruta = strClean($arrParams[1]);
				$infoInsumo = $this->getInsumoT($idinsumos,$ruta);
				if(empty($infoInsumo)){
					header("Location:".base_url());
				}

				$data['page_tag'] = NOMBRE_EMPRESA." - ".$infoInsumo['nombre'];
				$data['page_title'] = $infoInsumo['nombre'];
				$data['page_name'] = "insumo";
				$data['insumo'] = $infoInsumo;
				$data['insumos'] = $this->getInsumosRandom($infoInsumo['categoriaid'],8,"r");
				$this->views->getView($this,"insumo",$data);
			}
		}

	}
 ?>
