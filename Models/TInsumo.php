<?php 
require_once("Libraries/Core/Mysql.php");
trait TInsumo{
	private $con;
	private $strCategoria;
	private $intIdcategoria;
	private $intIdInsumo;
	private $strInsumo;
	private $cant;
	private $option;
	private $strRuta;
    

    public function getInsumosT() //listar en datatable
		{
            $this->con = new Mysql();
			$sql = "SELECT i.idinsumos,i.nombre,i.descripcion,c.idcategoria,c.nombrecat, i.ruta 
					FROM insumos i 
					INNER JOIN categoria c
					ON i.categoriaid = c.idcategoria
					WHERE i.status != 0 ORDER BY i.idinsumos DESC ";
				$request = $this->con->select_all($sql);
				if(count($request) > 0){
					for ($c=0; $c < count($request) ; $c++) { 
						$intIdInsumo = $request[$c]['idinsumos'];
						$sqlImg = "SELECT img
								FROM imagen
								WHERE insumoid = $intIdInsumo";
						$arrImg = $this->con->select_all($sqlImg);
						if(count($arrImg) > 0){
							for ($i=0; $i < count($arrImg); $i++) { 
								$arrImg[$i]['url_image'] = media().'/images/uploads/'.$arrImg[$i]['img'];
							}
						}
						$request[$c]['images'] = $arrImg;
                        }
                    }
					return $request;
		}


		public function getInsumosCategoriaT(int $idcategoria, string $ruta){
			$this->intIdcategoria = $idcategoria;
			$this->strRuta = $ruta;
			$this->con = new Mysql();
			$sql_cat = "SELECT idcategoria, nombrecat FROM categoria WHERE idcategoria = '{$this->intIdcategoria}'";
			$request = $this->con->select($sql_cat);
	
			if(!empty($request)){
				$this->strCategoria = $request['nombrecat'];
				$sql = "SELECT i.idinsumos,								
								i.nombre,
								i.descripcion,
								i.categoriaid,
								c.nombrecat as categoria,
								i.ruta								
								FROM insumos i 
						INNER JOIN categoria c
						ON i.categoriaid = c.idcategoria
						WHERE i.status != 0 AND i.categoriaid = '{$this->intIdcategoria}' AND c.ruta = '{$this->strRuta}' ";
						$request = $this->con->select_all($sql);
						if(count($request) > 0){
							for ($c=0; $c < count($request) ; $c++) { 
								$intIdInsumo = $request[$c]['idinsumos'];
								$sqlImg = "SELECT img
										FROM imagen
										WHERE insumoid = $intIdInsumo";
								$arrImg = $this->con->select_all($sqlImg);
								if(count($arrImg) > 0){
									for ($i=0; $i < count($arrImg); $i++) { 
										$arrImg[$i]['url_image'] = media().'/images/uploads/'.$arrImg[$i]['img'];
									}
								}
								$request[$c]['images'] = $arrImg;
							}
						}
				$request = array('idcategoria' => $this->intIdcategoria,
								'categoria' => $this->strCategoria,
								'insumos' => $request
				);
	
			}
			return $request;
		}

		public function getInsumoT(int $idinsumos,string $ruta){
			$this->con = new Mysql();
			$this->intIdInsumo = $idinsumos;
			$this->strRuta = $ruta;
			$sql = "SELECT i.idinsumos,							
							i.nombre,
							i.descripcion,
							i.categoriaid,
							c.nombrecat as categoria,
							i.ruta
							
					FROM insumos i
					INNER JOIN categoria c
					ON i.categoriaid = c.idcategoria
					WHERE i.status != 0 AND i.idinsumos = '{$this->intIdInsumo}' AND i.ruta = '{$this->strRuta}' ";
					$request = $this->con->select($sql);
					if(!empty($request)){
						$intIdInsumo = $request['idinsumos'];
						$sqlImg = "SELECT img
								FROM imagen
								WHERE insumoid = $intIdInsumo";
						$arrImg = $this->con->select_all($sqlImg);
						if(count($arrImg) > 0){
							for ($i=0; $i < count($arrImg); $i++) { 
								$arrImg[$i]['url_image'] = media().'/images/uploads/'.$arrImg[$i]['img'];
							}
						}else{
							$arrImg[0]['url_image'] = media().'/images/uploads/product.png';
						}
						$request['images'] = $arrImg;
					}
			return $request;
		}

		public function getInsumosRandom(int $idcategoria, int $cant, string $option){
			$this->intIdcategoria = $idcategoria;
			$this->cant = $cant;
			$this->option = $option;
	
			if($option == "r"){
				$this->option = " RAND() ";
			}else if($option == "a"){
				$this->option = " idinsumos ASC ";
			}else{
				$this->option = " idinsumos DESC ";
			}
	
			$this->con = new Mysql();
			$sql = "SELECT i.idinsumos,							
							i.nombre,
							i.descripcion,
							i.categoriaid,
							c.nombrecat as categoria,
							i.ruta
							
					FROM insumos i 
					INNER JOIN categoria c
					ON i.categoriaid = c.idcategoria
					WHERE i.status != 0 AND i.categoriaid = $this->intIdcategoria
					ORDER BY $this->option LIMIT  $this->cant ";
					$request = $this->con->select_all($sql);
					if(count($request) > 0){
						for ($c=0; $c < count($request) ; $c++) { 
							$intIdInsumo = $request[$c]['idinsumos'];
							$sqlImg = "SELECT img
									FROM imagen
									WHERE insumoid = $intIdInsumo";
							$arrImg = $this->con->select_all($sqlImg);
							if(count($arrImg) > 0){
								for ($i=0; $i < count($arrImg); $i++) { 
									$arrImg[$i]['url_image'] = media().'/images/uploads/'.$arrImg[$i]['img'];
								}
							}
							$request[$c]['images'] = $arrImg;
						}
					}
			return $request;
	
		}	
}

 ?>