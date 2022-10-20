<?php 

	class ServiciosModel extends Mysql
	{
		public $intIdServicio;
		public $strServicio;		
		public $intStatus;		

		public function __construct()
		{
			parent::__construct();
		}

		public function insertServicio(string $nombreservicio, int $status){

			$return = 0;
			$this->strServicio = $nombreservicio;		
			$this->intStatus = $status;

			$sql = "SELECT * FROM servicio WHERE nombreservicio = '{$this->strServicio}' ";
			$request = $this->select_all($sql);

			if(empty($request))
			{
				$query_insert  = "INSERT INTO servicio(nombreservicio,status) VALUES(?,?)";
	        	$arrData = array($this->strServicio, 								 
								 $this->intStatus);
	        	$request_insert = $this->insert($query_insert,$arrData);
	        	$return = $request_insert;
			}else{
				$return = "exist";
			}
			return $return;
		}

		public function selectServicios()
		{
			$sql = "SELECT * FROM servicio
					WHERE status != 0 ";
			$request = $this->select_all($sql);
			return $request;
		}

		public function selectServicio(int $idservicio){
			$this->intIdServicio = $idservicio;
			$sql = "SELECT * FROM servicio
					WHERE idservicio = $this->intIdServicio";
			$request = $this->select($sql);
			return $request;
		}

		public function updateServicio(int $idservicio, string $nombreservicio, int $status){
			$this->intIdServicio = $idservicio;
			$this->strServicio = $nombreservicio;
			$this->intStatus = $status;

			$sql = "SELECT * FROM servicio WHERE nombreservicio = '{$this->strServicio}' AND idservicio != $this->intIdServicio";
			$request = $this->select_all($sql);

			if(empty($request))
			{
				$sql = "UPDATE servicio SET nombreservicio = ? status = ? WHERE idservicio = $this->intIdServicio ";
				$arrData = array($this->strServicio, 								
								 $this->intStatus);
				$request = $this->update($sql,$arrData);
			}else{
				$request = "exist";
			}
		    return $request;			
		}

		public function deleteServicio(int $idservicio)
		{
			$this->intIdServicio = $idservicio;
			$sql = "SELECT * FROM pedido WHERE servicioid = $this->intIdServicio";
			$request = $this->select_all($sql);
			if(empty($request))
			{
				$sql = "UPDATE servicio SET status = ? WHERE idservicio = $this->intIdServicio ";
				$arrData = array(0);
				$request = $this->update($sql,$arrData);
				if($request)
				{
					$request = 'ok';	
				}else{
					$request = 'error';
				}
			}else{
				$request = 'exist';
			}
			return $request;
		}	


	}
 ?>