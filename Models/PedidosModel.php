<?php
class PedidosModel extends Mysql
{

	public function __construct()
	{
		parent::__construct();
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
			} else if ($insumo->cantidad < 1){
				throw new Error("Invalid insumo cantidad, must be greather than 0");
			}
		}
		//Todos los datos son correctos para procesder a realizar el pedido
		$query_insert  = "INSERT INTO pedido(personaid,servicioid) VALUES(?,?)";
		$arrData = array($personid, $servicioid);
		$pedidoid = $this->insert($query_insert, $arrData);
		foreach ($insumos as $insumo) {
			$query_insert  = "INSERT INTO detalle_pedido(pedidoid,insumoid,cantidad) VALUES(?,?,?)";
			$arrData = array($pedidoid,$insumo->idinsumo, $insumo->cantidad);
			$this->insert($query_insert, $arrData);
		}
		return $pedidoid;
	}
}
