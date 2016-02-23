<?php
require_once 'class.phpmailer.php';
include ("class_base_sql.php");
include("Class.BaseDatosSap.php");


//Obtener Dia Anterior
$date=date('d/m/Y');
$valor=1;

//$date="01/03/2011";

$date_x = explode("/",$date); 
$ano = $date_x[2]; 
$mes = $date_x[1]; 
$dia = $date_x[0]; 
 
$total_dia_v=0;
$total_dia_p=0;


$t = mktime(0,0,0,$mes,$dia,$ano);
$t2=$t-86400;

$dia_anterior=date("d/m/y",$t2);

$Objeto=new Basedatos();
$Objeto->conectar() or die("Error de conexión");
$sql = "SELECT fecha, localidad, cantidad, bsf
FROM VENTA_DIA
WHERE fecha='".$dia_anterior."'
order by localidad";

$html="<table border='0'><tr align='center' bgcolor='#009933' style='color:#FFFF00'><td><strong>Sucursal</strong></td><td><strong>Unid. SAP</strong></td><td><strong>Bsf. SAP</strong></td><td><strong>Unid. Stellar</strong></td><td><strong>Bsf. Stellar</strong></td><td><strong>Dif. Unidades</strong></td><td><strong>Dif. Bsf</strong></td></tr>";
$sucursal="";
$stellar=0;
$bsf_stellar=0;
$sap=0;
$sap_bsf=0;
$cont=0;
$res=$Objeto->sentencia($sql);

while($row=$Objeto->filas($res)){
	$sucursal=trim($row["localidad"]);
	$stellar=$row["cantidad"];
	$bsf_stellar=$row["bsf"];
	if($sucursal=="02" || $sucursal=="08" || $sucursal=="10" || $sucursal=="14"){
		$sucursal="GH".$sucursal;
	}else{
		$sucursal="EG".$sucursal;
	}
	$sapt=explode('y',datos_sap($sucursal));
	$sap=$sapt['0'];
	$sap_bsf=$sapt['1'];
	$diferencia=$sap-$stellar;
	if($diferencia<=-0.9 || $diferencia>=0.9){
		$html=$html."<tr align='center'><td><strong>".$sucursal."</strong></td><td>".number_format($sap,2,',','.')."</td><td>".number_format($sap_bsf,2,',','.')."</td><td >".number_format($stellar,2,',','.')."</td><td>".number_format($bsf_stellar,2,',','.')."</td><td bgcolor='#FF0000'>".number_format($diferencia,2,',','.')."</td><td>".number_format($sap_bsf-$bsf_stellar,2,',','.')."</td></tr>";
		$cont=$cont+1;
	}else{
		
		$html=$html."<tr align='center'><td><strong>".$sucursal."</strong></td><td>".number_format($sap,2,',','.')."</td><td>".number_format($sap_bsf,2,',','.')."</td><td >".number_format($stellar,2,',','.')."</td><td>".number_format($bsf_stellar,2,',','.')."</td><td bgcolor='#0033FF'>".number_format($diferencia,2,',','.')."</td><td>".number_format($sap_bsf-$bsf_stellar,2,',','.')."</td></tr>";
	}
	
	$sap=0;
}

$html=$html."<tr bgcolor='#009933' style='color:#FFFF00'><td colspan='5' align='right'><strong>Diferencias:</strong></td><td align='center' colspan='2'><strong>".$cont."</strong></td></tr></table>";

echo $html;


//Envio Correo
/*
$mail = new PHPMailer ();

$mail -> From = "rperez@elgarzon.com";

$mail -> FromName = "Departamento Sistemas";

$mail -> AddAddress ("rperez@elgarzon.com");
$mail -> AddAddress ("rbuitrago@elgarzon.com");
$mail -> AddAddress ("ncamargo@elgarzon.com");
$mail -> AddAddress ("jlabrador@elgarzon.com");
$mail -> AddAddress ("hdelgado@elgarzon.com");
$mail -> AddAddress ("emendez@elgarzon.com");
$mail -> AddAddress ("kgarcia@elgarzon.com");
//$mail->AddCC("rperez@elgarzon.com");
//$mail->AddCC("kgarcia@elgarzon.com");
//$mail->AddCC("hdelgado@elgarzon.com");

$mail -> Subject = "Diferencia Venta Stellar vs SAP del dia: ".$dia_anterior;

$mail -> Body = $html;

$mail -> IsHTML (true);



$mail->IsSMTP();

$mail->Host = 'ssl://smtp.gmail.com';

$mail->Port = 465;

$mail->SMTPAuth = true;

$mail->Username = 'rperez@elgarzon.com';

$mail->Password = 'jose5347457';



if(!$mail->Send()) {

        echo 'Error: ' . $mail->ErrorInfo;

}
*/
//fin envio correo


function datos_sap($sucursal){
$sapt=0;
$sap = new BaseDatosSap;
$rfc=$sap->ConectaRfc();
$fce=$sap->ConectaFce("ZGSD_MD_VENTAS");
saprfc_import ($fce,"IV_WERKS",$sucursal);

//Fill internal tables
//Do RFC call of function Z_OBTENER_DATOS_EMPLEADOS, for handling exceptions use saprfc_exception()
$rfc_rc = saprfc_call_and_receive ($fce);
if ($rfc_rc != SAPRFC_OK) { if ($rfc == SAPRFC_EXCEPTION ) echo ("Exception raised: ".saprfc_exception($fce)); else echo (saprfc_error($fce)); exit; }
//Retrieve export parameters
 $sapt = saprfc_export ($fce,"EV_CANTID");
 $saptt = saprfc_export ($fce,"EV_BSF");
 
 $sapt = $sapt ."y". $saptt;
	return $sapt;
}

?>




<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Conectar SAP</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script type="text/javascript" src="js/jquery-1.6.2.js"></script>
<script type="text/javascript">
$(document).ready(function()
{
    //wait a few seconds and close the window
    function closeWindow() {
    	//netscape.security.PrivilegeManager.enablePrivilege("UniversalBrowserWrite");
    	//alert("This will close the window");
    	window.open('','_self');
    	window.close();
	}
    //wait a few seconds and close the window 
        closeWindow(); 
});
window.close();
</script>
</head>

<body>


</body>
</html>
