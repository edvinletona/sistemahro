<?php

class Pedidos extends Controllers
{
    public function __construct()
    {
        sessionStart();
        parent::__construct();
        //session_regenerate_id(true);
        if (empty($_SESSION['login'])) {
            header('Location: ' . base_url() . '/login');
        }
        getPermisos(MPEDIDOS); //id del modulo
    }

    public function Pedidos()
    {
        if (empty($_SESSION['permisosMod']['r'])) {
            header("Location:" . base_url() . '/dashboard');
        }
        $data['page_tag'] = "Pedidos";
        $data['page_title'] = "PEDIDOS <small>Atención Al Usuario</small>";
        $data['page_name'] = "pedidos";
        $data['page_functions_js'] = "functions_pedidos.js";
        $this->views->getView($this, "pedidos", $data);
    }

    public function setPedido()
    {
        error_reporting(0);
        if ($_POST) {
            if (empty($_POST['personaid']) || empty($_POST['servicioid']) || empty($_POST['insumos'])) {
                $arrResponse = array("status" => false, "msg" => 'Datos incorrectos.');
            } else {
                $personaid = intval($_POST['personaid']);
                $servicioid = intval($_POST['servicioid']);
                $insumos = json_decode($_POST['insumos']);
                $request = $this->model->insertPedido($personaid, $servicioid, $insumos);
                if ($request > 0) {
                    $arrResponse = array("status" => true, "msg" => $request);
                }
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
    }

    public function getPedidosList()
    {
        $arrData = $this->model->getPedidoList();
        if (empty($arrData)) {
            $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
        } else {
            $arrResponse = array('status' => true, 'data' => $arrData);
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
    }

    public function getPedido($pedidoid){
        $arrData = $this->model->getPedido($pedidoid);
        if (empty($arrData)) {
            $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
        } else {
            $arrResponse = array('status' => true, 'data' => $arrData);
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
    }


}
