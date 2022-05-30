<?php

/*
 * -------------------------------------
 * Database.php
 * -------------------------------------
 */

class Database
{
    private static $conexion;
	
	public function __construct() {
		//parent::__construct(DB_HOST,DB_USER,DB_PASS,DB_NAME);
		include_once BASE_ROOT.'application/config.php';
		include_once BASE_ROOT.'libs/adodb5/adodb.inc.php';
		
		
		//self::$conexion = ADONewConnection('oci8');
		self::$conexion = ADONewConnection('mysqli');
		
		self::$conexion -> Connect(DB_HOST1,DB_USER1,DB_PASS1,DB_NAME1);				 
	}
	
	public static function abrir_conexion(){
		if(!isset(self::$conexion)){
			try{
	
				include_once BASE_ROOT.'application/config.php';
				include_once BASE_ROOT.'libs/adodb5/adodb.inc.php';
	
				//self::$conexion = ADONewConnection('oci8');
				self::$conexion = ADONewConnection('mysqli');

				self::$conexion -> Connect(DB_HOST1,DB_USER1,DB_PASS1,DB_NAME1);
		
			} catch (Exception $ex) {
				print "ERROR: ".$ex -> getMessage(). "<br>";
				die();
			}
		}
	
	}

	public static function cerrar_conexion() {
		if(isset(self::$conexion)){
			self::$conexion = null;
			 
			//print "CONEXION CERRADA"."<br>";
		}
	}
	
	public static function obtener_conexion() {
		return self::$conexion;
	}

	/*
	 * $query:  query de la consulta
	 * $pagina:  # de pagina a  mostrar
	 * $limite:  # de registros por pagina
	 */

	public function paginar($query, $pagina = false, $limite = false)
	{
		/*   Define el valor de la variable  */
		if(!($limite && is_numeric($limite))){
			$limite = LIMIT_REGXPAG;
		}

		/*  Define el inicio de la pagina */
		if ($pagina && is_numeric($pagina)){
			$inicio = ($pagina - 1) * $limite;
		} else {
			$pagina = 1;
			$inicio = 0;
		}


		/*  Consulta a la BD */
		$result = $this->query($query);
		$registros =  $result->num_rows;


		/*  Define el total de paginas */
		$total = ceil($registros / $limite);
		
		if ($pagina>$total) $pagina=$total;
		
		
	/*  Define el inicio de la pagina */
		if ($pagina && is_numeric($pagina)){
			$inicio = ($pagina - 1) * $limite;
		} else {
			$pagina = 1;
			$inicio = 0;
		}

		/*  Consulta con limites  */
		$query = $query . " LIMIT $inicio, $limite";
		$result = $this->query($query);


		/* Define las otras variables de la paginaci�n */
		$this->_paginacion['actual'] = $pagina;
		$this->_paginacion['total'] = $total;

		if($pagina > 1){
			$this->_paginacion['primero'] = 1;
			$this->_paginacion['anterior'] = $pagina - 1;
		} else {
			$this->_paginacion['primero'] = '';
			$this->_paginacion['anterior'] = '';
		}

		if($pagina < $total){
			$this->_paginacion['ultimo'] = $total;
			$this->_paginacion['siguiente'] = $pagina + 1;
		} else {
			$this->_paginacion['ultimo'] = '';
			$this->_paginacion['siguiente'] = '';
		}

		$total_paginas = $this->_paginacion['total'];
		$pagina_seleccionada = $this->_paginacion['actual'];
		$rango = ceil(LIMIT_PAGEVIEW / 2);

		if($pagina_seleccionada < $rango) $rango_derecho = LIMIT_PAGEVIEW;
		 else 
		$rango_derecho = $pagina_seleccionada + $rango;
		
		if ($rango_derecho >$total_paginas) $rango_derecho=$total_paginas;

		if ($total_paginas<LIMIT_PAGEVIEW) $rango_derecho = $total_paginas;

		$rango_izquierdo = $pagina_seleccionada - ($rango-1);

		if ($rango_izquierdo<=0) $rango_izquierdo=1;
	
		$this->_paginacion['rango'] = range($rango_izquierdo,$rango_derecho);
		return $result;
	}

	

	






}

?>
