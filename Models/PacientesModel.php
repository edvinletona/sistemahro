<?php 
class PacientesModel extends Mysql
{
	private $intIdUsuario;
	private $strIdentificacion;
	private $strNombre;
	private $strApellido;
	private $intEdad;	
	private $intTelefono;	
	private $strDireccion;
	private $strResponsable;
	private	$intTelefonoResp;	
	private $strToken;
	private $intTipoId;
	

	public function __construct()
	{
		parent::__construct();
	}	

	public function insertPaciente(string $identificacion, string $nombre, string $apellido, $edad, int $telefono, string $direccion, string $responsable, int $telefonoResp, int $tipoid){

		$this->strIdentificacion = $identificacion;
		$this->strNombre = $nombre;
		$this->strApellido = $apellido;
		$this->intEdad = $edad;
		$this->intTelefono = $telefono;		
		$this->strDireccion = $direccion;
		$this->strResponsable = $responsable;
		$this->intTelefonoResp = $telefonoResp;		
		$this->intTipoId = $tipoid;
		
		$return = 0;
		$sql = "SELECT * FROM persona WHERE 
				identificacion = '{$this->strIdentificacion}' ";
		$request = $this->select_all($sql);

		if(empty($request))
		{
			$query_insert  = "INSERT INTO persona(identificacion,nombres,apellidos,edad,telefono,direccion,responsable,telefonoResp,rolid) 
							  VALUES(?,?,?,?,?,?,?,?,?)";
        	$arrData = array($this->strIdentificacion,
    						$this->strNombre,
    						$this->strApellido,
							$this->intEdad,
    						$this->intTelefono,    						
							$this->strDireccion,
							$this->strResponsable,
							$this->intTelefonoResp,    						
    						$this->intTipoId);
        	$request_insert = $this->insert($query_insert,$arrData);
        	$return = $request_insert;
		}else{
			$return = "exist";
		}
        return $return;
	}

	public function selectPacientes()
	{
		$sql = "SELECT idpersona,identificacion,nombres,apellidos,edad,telefono,direccion,responsable,telefonoResp,status 
				FROM persona 
				WHERE rolid = 5 and status != 0 ";
		$request = $this->select_all($sql);
		return $request;
	}

	public function selectPaciente(int $idpersona){
		$this->intIdUsuario = $idpersona;
		$sql = "SELECT idpersona,identificacion,nombres,apellidos,edad,telefono,direccion,responsable,telefonoResp,status, DATE_FORMAT(datecreated, '%d-%m-%Y') as fechaRegistro 
				FROM persona
				WHERE idpersona = $this->intIdUsuario and rolid = 5";
		$request = $this->select($sql);
		return $request;
	}

	public function updatePaciente(int $idUsuario, string $identificacion, string $nombre, string $apellido, int $edad, int $telefono, string $direccion, string $responsable, int $telefonoResp){

		$this->intIdUsuario = $idUsuario;
		$this->strIdentificacion = $identificacion;
		$this->strNombre = $nombre;
		$this->strApellido = $apellido;
		$this->intEdad = $edad;
		$this->intTelefono = $telefono;		
		$this->strDireccion = $direccion;
		$this->strResponsable = $responsable;
		$this->intTelefonoResp = $telefonoResp;
		
		

		$sql = "SELECT * FROM persona WHERE (idpersona != $this->intIdUsuario)
									  OR (identificacion = '{$this->strIdentificacion}' AND idpersona != $this->intIdUsuario) ";
		$request = $this->select_all($sql);

		if(empty($request))
		{
			if($this->strPassword  != "")
			{
				$sql = "UPDATE persona SET identificacion=?, nombres=?, apellidos=?, edad=?,telefono=?, direccion=?, responsable=?, telefonoResp=?
						WHERE idpersona = $this->intIdUsuario ";
				$arrData = array($this->strIdentificacion,
        						$this->strNombre,
        						$this->strApellido,
								$this->intEdad,
        						$this->intTelefono,        						
								$this->strDireccion,
								$this->strResponsable,
								$this->intTelefonoResp,
        						);
			}else{
				$sql = "UPDATE persona SET identificacion=?, nombres=?, apellidos=?, edad=?,telefono=?, direccion=?, responsable=?, telefonoResp=?
						WHERE idpersona = $this->intIdUsuario ";
				$arrData = array($this->strIdentificacion,
        						$this->strNombre,
        						$this->strApellido,
        						$this->intTelefono,
								$this->intEdad,        						
								$this->strDireccion,
								$this->strResponsable,
								$this->intTelefonoResp
        						);
			}
			$request = $this->update($sql,$arrData);
		}else{
			$request = "exist";
		}
		return $request;
	}

	public function deletePaciente(int $intIdpersona)
	{
		$this->intIdUsuario = $intIdpersona;
		$sql = "UPDATE persona SET status = ? WHERE idpersona = $this->intIdUsuario ";
		$arrData = array(0);
		$request = $this->update($sql,$arrData);
		return $request;
	}
}

 ?>