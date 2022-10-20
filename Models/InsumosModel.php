<?php 

	class InsumosModel extends Mysql
	{
		private $intIdInsumo;		
		private $strNombre;
		private $strDescripcion;	
		private $intTipocategoria;
		private $intStatus;
        public $intIdcategoria;
		private $strRuta;
		private $strImagen;
		
		
		public function __construct()
		{
			parent::__construct();
		}	

		public function insertInsumo(string $nombre, string $descripcion, int $categoriaid, string $ruta,int $status){

			$this->strNombre        = $nombre;
			$this->strDescripcion   = $descripcion;			
			$this->intTipocategoria = $categoriaid;
			$this->strRuta   		= $ruta;
			$this->intStatus        = $status;
			$return = 0;

			$sql = "SELECT * FROM insumos WHERE nombre = '{$this->strNombre}' ";
			$request = $this->select_all($sql);

			if(empty($request))
			{
				$query_insert  = "INSERT INTO insumos(categoriaid,nombre,descripcion,ruta,status) 
								  VALUES(?,?,?,?,?)";
	        	$arrData = array($this->intTipocategoria,
								$this->strNombre,
        						$this->strDescripcion, 
								$this->strRuta,					
        						$this->intStatus);
	        	$request_insert = $this->insert($query_insert,$arrData);
	        	$return = $request_insert;
			}else{
				$return = "exist";
			}
			
	        return $return;
		}

		public function updateInsumo(int $IdInsumo, string $nombre, string $descripcion, int $categoriaid, string $ruta, int $status){			
			$this->intIdInsumo 		= $IdInsumo;
			$this->strNombre 		= $nombre;
			$this->strDescripcion 	= $descripcion;
			$this->intTipocategoria = $categoriaid;
			$this->strRuta   		= $ruta;
			$this->intStatus 		= $status;

			$sql = "SELECT * FROM insumos WHERE nombre = '{$this->strNombre}' AND idinsumos != $this->intIdInsumo";
			$request = $this->select_all($sql);

			if(empty($request))
			{
				$sql = "UPDATE insumos SET nombre = ?, descripcion = ?, categoriaid=?, ruta=?, status=?  WHERE idinsumos = $this->intIdInsumo ";
				$arrData = array($this->strNombre, 
								 $this->strDescripcion, 
								 $this->intTipocategoria, 
								 $this->strRuta,
								 $this->intStatus);
				$request = $this->update($sql,$arrData);
			}else{
				$request = "exist";
			}
		    return $request;			
		}

		public function selectInsumos() //listar en datatable
		{
			$sql = "SELECT i.idinsumos,i.nombre,i.descripcion,i.status,c.idcategoria,c.nombrecat 
					FROM insumos i 
					INNER JOIN categoria c
					ON i.categoriaid = c.idcategoria
					WHERE i.status != 0 ";
					$request = $this->select_all($sql);
					return $request;
		}
        public function selectInsumo(int $idinsumos){
			$this->intIdInsumo = $idinsumos;
			$sql = "SELECT i.idinsumos,i.nombre,i.descripcion,i.categoriaid,c.nombrecat as categoria,i.status
                    FROM insumos i
					INNER JOIN categoria c
					ON i.categoriaid = c.idcategoria
					WHERE idinsumos = $this->intIdInsumo";
			$request = $this->select($sql);
			return $request;
		}		

       

		
		public function deleteInsumo(int $idinsumos)
		{
			$this->intIdInsumo = $idinsumos;
			$sql = "UPDATE insumos SET status = ? WHERE idinsumos = $this->intIdInsumo ";
			$arrData = array(0);
			$request = $this->update($sql,$arrData);
			return $request;
		}

		public function insertImage(int $idinsumos, string $imagen){
			$this->intIdInsumo = $idinsumos;
			$this->strImagen = $imagen;
			$query_insert  = "INSERT INTO imagen(insumoid,img) VALUES(?,?)";
	        $arrData = array($this->intIdInsumo,
        					$this->strImagen);
	        $request_insert = $this->insert($query_insert,$arrData);
	        return $request_insert;
		}

		public function selectImages(int $idinsumos){
			$this->intIdInsumo = $idinsumos;
			$sql = "SELECT insumoid,img
					FROM imagen
					WHERE insumoid = $this->intIdInsumo";
			$request = $this->select_all($sql);
			return $request;
		}

		public function deleteImage(int $idinsumos, string $imagen){
			$this->intIdInsumo = $idinsumos;
			$this->strImagen = $imagen;
			$query  = "DELETE FROM imagen 
						WHERE insumoid = $this->intIdInsumo 
						AND img = '{$this->strImagen}'";
	        $request_delete = $this->delete($query);
	        return $request_delete;
		}



	
	}
 ?>