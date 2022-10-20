<?php 

	require_once("Models/TCategoria.php");
	require_once("Models/TInsumo.php");

	class Home extends Controllers{
		use TCategoria, TInsumo;

		public function __construct()
		{
			parent::__construct(); //metodo constructor de controllers

		}

		public function home()
		{
			
			$data['page_tag'] = "Home";
			$data['page_title'] = "Pagina principal";
			$data['page_name'] = "home";
			
			$data['slider'] = $this->getCategoriasT(CAT_SLIDER);
			$data['banner'] = $this->getCategoriasT(CAT_BANNER);
			$data['insumos'] = $this->getInsumosT();

			$this->views->getView($this,"home",$data);
		}

				
	}

 ?>