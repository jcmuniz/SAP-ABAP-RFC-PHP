<?php
date_default_timezone_set("America/Caracas");
setlocale(LC_ALL,"es_ES@euro","es_ES","esp");
 
class BaseDatosSap
{
 var $login;
 var $funcion;
 var $rfc;
 var $fce;

 function BaseDatosSap()
 {
	$this->login = array (
			"ASHOST"=>"192.168.125.120",
			"SYSNR"=>"00",
			"CLIENT"=>"300",
			"USER"=>"sapadm",
			"PASSWD"=>"Multiple2013",
			"LANG"=>"es",
			"CODEPAGE"=>"1100");	 
 } 
 function ConectaRfc()
 {
	$this->rfc = saprfc_open ($this->login);
	if (! $this->rfc ) { echo "RFC connection failed"; exit; }
	return $this->rfc;
  }
  function ConectaFce($funcion)
 { 
	//Discover interface for function module Z_OBTENER_DATOS_EMPLEADOS
	$this->fce = saprfc_function_discover($this->rfc,$funcion);
	if (! $this->fce ) { echo "Discovering interface of function module failed"; exit; }
 	return $this->fce;
 }
 function Desconectar()
 {
 	//saprfc_function_debug_info($this->fce);
	saprfc_function_free($this->fce);
	saprfc_close($this->rfc);
 }
 


}//Fin Class
?>