<?php
    include_once('C:/Appserv/www/Dace/inc/odbcss_c.php');
	include_once ('../stdagre/inc/config.php');
	include_once ('C:/Appserv/www/Dace/inc/activaerror.php');

	/////////////////
	$Cins = new ODBC_Conn($sede,"c","c",$ODBCC_conBitacora,$laBitacora);
	$mSQL = "SELECT inscripcion FROM dace002 WHERE exp_e='".$exped."'";
	$Cins->ExecSQL($mSQL,__LINE__,true);
	$conectado=$Cins->result[0][0];
	if($conectado=='1'){
		$mSQL = "UPDATE dace002 SET inscripcion='1' WHERE exp_e='".$exped."'";
		$Cins->ExecSQL($mSQL,__LINE__,true);
		echo "sesion finalizada";
	}else{
		echo "estudiante ya aun conectado";		
	}
?>