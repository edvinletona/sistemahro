<?php 

	const BASE_URL = "http://localhost/sistemahro";
	//const BASE_URL = "https://pacientehro.qualitysmd.com/";

	//zona horaria
	date_default_timezone_set('America/Guatemala');
	

	//Datos de conexion a Base de Datos
	const DB_HOST = "localhost";
	const DB_NAME = "pacientes";
	const DB_USER = "root";
	const DB_PASSWORD = "";
	const DB_CHARSET = "utf8";

	//delimitadores decimal y millar 
	const SPD = ".";
	const SPM = ",";

	//simbolo de moneda
	const SMONEY = "Q.";

	//Datos envio de correo
	const NOMBRE_REMITENTE = "Atención al Usuario";
	const EMAIL_REMITENTE = "no-reply@qualitysmd.com";
	const NOMBRE_EMPRESA = "Atención al Usuario";
	const WEB_EMPRESA = "http://pacientehro.qualitysmd.com/";


	//Modulos
	const MDASHBOARD = 1;
	const MUSUARIOS = 2;
	const MPACIENTES = 3;
	const MINSUMOS = 4;
	const MCATEGORIAS = 5;
	const MPEDIDOS = 6;
	const MSERVICIOS = 7;

	//Roles

	const RSUPERUSUARIO = 1;
	const RADMINISTRADOR = 2;
	const RDIGITADOR = 3;
	const RPACIENTE = 5;

	const CAT_SLIDER = "1,2,3";
	const CAT_BANNER = "1,2,4";

 ?>