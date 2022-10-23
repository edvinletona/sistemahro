<?php
class PedidosModel extends Mysql
{

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Obtiene los datos generales de un pedido a modo de listado.
	 * Aca se pueda agregar algun filtro de estado o fecha y un limite para paginear.
	 */
	public function getPedidoList()
	{
		$sql = "SELECT ped.idpedido as idpedido, DATE_FORMAT(ped.fecha,'%d/%m/%Y') as fecha, per.nombres as nombres, per.apellidos as apellidos, ser.nombreservicio as nombreservicio
			FROM  pedido AS ped , persona as per , servicio as ser 
			WHERE ped.personaid = per.idpersona AND ped.servicioid = ser.idservicio";
		$request = $this->select_all($sql);
		return $request;
	}

	/**
	 * Crea un pedido, actualmente en estado de procesado y fecha de sistema.
	 */
	public function insertPedido(int $personid, int $servicioid, $insumos)
	{
		if (sizeof($insumos) == 0) {
			throw new Error("Invalid insumos, can't be empty");
		}
		//validamos la existencia de la persona y del servicio
		$sql = "SELECT * FROM persona WHERE idpersona = '{$personid}' ";
		$request = $this->select($sql);
		if (empty($request)) {
			throw new Error("Invalid persona id");
		}
		$sql = "SELECT * FROM servicio WHERE idservicio = '{$servicioid}' ";
		$request = $this->select($sql);
		if (empty($request)) {
			throw new Error("Invalid servicio id");
		}
		//validamos que cada uno de los insumos exista.
		//Aqui se podria controlar si esta activo el insumo e incluso la existencia de los mismo si hubiera.
		foreach ($insumos as $insumo) {
			$sql = "SELECT * FROM insumos WHERE idinsumos = '{$insumo->idinsumo}' ";
			$request = $this->select($sql);
			if (empty($request)) {
				throw new Error("Invalid insumo id");
			} else if ($insumo->cantidad < 1) {
				throw new Error("Invalid insumo cantidad, must be greather than 0");
			}
		}
		//Todos los datos son correctos para procesder a realizar el pedido
		$query_insert  = "INSERT INTO pedido(personaid,servicioid) VALUES(?,?)";
		$arrData = array($personid, $servicioid);
		$pedidoid = $this->insert($query_insert, $arrData);
		foreach ($insumos as $insumo) {
			$query_insert  = "INSERT INTO detalle_pedido(pedidoid,insumoid,cantidad) VALUES(?,?,?)";
			$arrData = array($pedidoid, $insumo->idinsumo, $insumo->cantidad);
			$this->insert($query_insert, $arrData);
		}
		return $pedidoid;
	}

	public function getPedido($pedidoid){
		$sql = "SELECT ped.idpedido as idpedido, DATE_FORMAT(ped.fecha,'%d/%m/%Y') as fecha, per.identificacion as identificacion, per.nombres as nombres, per.apellidos as apellidos, ser.nombreservicio as nombreservicio
			FROM  pedido AS ped , persona as per , servicio as ser 
			WHERE ped.idpedido = $pedidoid  AND ped.personaid = per.idpersona AND ped.servicioid = ser.idservicio ";
		$request = $this->select($sql);
		$response = new stdClass;
		$response->pedido = $request;
		$sql = "SELECT ins.idinsumos , ins.nombre , dp.cantidad, cat.nombrecat  
			FROM  detalle_pedido as dp , insumos as ins, categoria as cat  
			WHERE dp.pedidoid = $pedidoid AND dp.insumoid = ins.idinsumos AND ins.categoriaid = cat.idcategoria ";
		$request2 = $this->select_all($sql);
		$response->detalle = $request2;
		return $response;
	}
}
