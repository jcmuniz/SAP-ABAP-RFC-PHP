<?php 
class BaseDatos
{
//----------------------------------------------------------
//-----------Inicio de sesion-------------------------  
   var $servidor="192.168.0.220\CGARZON";
   var $usuario="SA";
   var $password="";
   var $BD="Intranet";
    
	var $server = "localhost";
	var $VAD10 = "VAD10";
	var $VAD20 = "VAD20";
   
//----------------------------------------------------------  


//----------------------------------------------------------    
   
    function numfilas($resultado)
	{
		  $n=mssql_num_rows($resultado);
		  return $n;
	}
	//---------------------------------------------------------------------------------------------	
	function filas($resultado)
	{
	   $campo=mssql_fetch_array($resultado);
	   return $campo;
	}
	
	//---------------------------------------------------------------------------------------------	
	function cerrar()
	{
	    mssql_close();
	}	
//---------------------------------------------------------------------------------------------		

	function sentencia($query)
	{
		$resultado=mssql_query($query);
		return $resultado;
	}
//---------------------------------------------------------------------------------------------	
	function xfilas($resultado)
	{
	   $campo=mssql_fetch_row($resultado);
	   return $campo;
	}	
//---------------------------------------------------------------------------------------------
	
 function conectarGen($servidor,$usuario,$password,$BD)
	{
		$cnx=mssql_connect($servidor,$usuario,$password);
		if($cnx)
		 $conex=mssql_select_db($BD,$cnx) or die("Error al Selecionar La Base de Datos");
		return $cnx;
	}
//---------------------------------------------------------------------------------------------  
 
//---------------------------------------------------------------------------------------------	
    function conectar()
	{
		$cnx=mssql_connect($this->servidor,$this->usuario,$this->password) or die("Error de Conexion");
		$conex=mssql_select_db($this->BD,$cnx) or die("Error al Selecionar La Base de Datos");
		return $conex;
	}
	

   
}//clase base
?>