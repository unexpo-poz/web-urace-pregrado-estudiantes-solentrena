<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>Solicitud de Cartas de Presentacion y Postulacion</title>
<?php
//include_once ('inc/vImage.php');
include_once ('inc/odbcss_c.php');
include_once ('inc/config.php');
include_once ('inc/activaerror.php');
include_once ('inc/funciones.php');

	if(isset($_POST['cedula']) && isset($_POST['contra'])) {
        $cedula = $_POST['cedula'];
        $contra = $_POST['contra'];
        // limpiemos la cedula y coloquemos los ceros faltantes
        $cedula = ltrim(preg_replace("/[^0-9]/","",$cedula),'0');
        $cedula = substr("00000000".$cedula, -8);
	}else{
		print "ERROR - FALTA ALGUN PARAMETRO";
		return;
	}
	
//	FALTA COLOCAR VALIDACION::: CUANDO LA CEDULA NO EXISTA EN LA BASE DE DATOS
//	FALTA COLOCAR VALIDACION::: VERIFICAR LA CLAVE

	$Cdp = new ODBC_Conn($DSN, $user_db, $pass_db, $ODBCC_conBitacora, $laBitacora);
	$mSQL = "SELECT APELLIDOS, NOMBRES, CI_E, EXP_E, CARRERA, APELLIDOS2, NOMBRES2 "; 
	$mSQL = $mSQL."FROM DACE002 A, TBLACA010 B ";
	$mSQL = $mSQL."WHERE CI_E ='".$cedula."' AND A.C_UNI_CA = B.C_UNI_CA";
	$Cdp->ExecSQL($mSQL,__LINE__,true);
	$datosp = $Cdp->result;
	foreach ($datosp as $dp){}

	$C_Emp = new ODBC_Conn($DSN, $user_db, $pass_db, $ODBCC_conBitacora, $laBitacora);
	$SQL_Emp = "SELECT ID_EMPRESA, NOMBRE_EMPRESA FROM SOL_EMPRESAS ";
	$C_Emp->ExecSQL($SQL_Emp,__LINE__,true);
	$Datos_Emp = $C_Emp->result;

?>

<style type="text/css">
.boton {
  text-align: center; 
  font-family:Arial; 
  font-size: 11px;
  font-weight: normal;
  background-color:#e0e0e0; 
  font-variant: small-caps;
  height: 20px;
  padding: 0px;
  }

  .datospf {
  text-align: left; 
  font-family:Arial; 
  font-size: 11px;
  font-weight: normal;
  background-color:#FFFFFF; 
  border-style: solid;
  border-width: 1px;
  border-color: #96BBF3;
  }
  .enc_p {
  color:#FFFFFF;
  text-align: center; 
  font-family:Helvetica; 
  font-size: 14px; 
  font-weight: bold;
  background-color:#3366CC;
  height:20px;
  font-variant: small-caps;
  }
  .datosp {
  text-align: left; 
  font-family:Arial; 
  font-size: 12px;
  font-weight: normal; 
  font-variant: small-caps;
  }
  .act { 
  text-align: center; 
  font-family:Arial; 
  font-size: 12px; 
  font-weight: normal;
  background-color:#99CCFF;
}
.dp {
	text-align: left; 
	font-family: Arial; 
	font-size: 11px;
	font-weight: normal;
	background-color: #FFFFFF; 
	font-variant: small-caps;
}
.titulo {
	text-align: center; 
	font-family:Arial; 
	font-size: 12px; 
	font-weight: normal;
	background-color: #FFFFFF; 
	margin-top:0;
	margin-bottom:0;	
	font-variant: small-caps;
}
</style>  

<script languaje="Javascript">
function validar(f) {
	if (f.Sel_Tipo_Carta.value == "") {
		alert("Debe Seleccionar el Tipo de Solicitud");
		return false;
	}
	if (f.Sel_Tipo_Pasantia.value == "") {
		alert("Debe Seleccionar el Tipo de Pasantia");
		return false;
	}
	if (f.Sel_Fecha.value == "") {
		alert("Debe Colocar la Fecha de Inicio");
		return false;
	}
	if (f.Sel_Empresa.value == "") {
		alert("Debe Seleccionar la Empresa");
		return false;
	}
}	
</script>

<script language='javascript' src='popcalendar.js'></script>

</head>
<body>

<div style="visibility: block;">
	<form name="Solicitud" action="Planilla_Proceso.php" method="post" onSubmit="return validar(this)">
		<table border="0px" align="center">
			<tr>
				<td colspan="5" align="center">
					<table border="0" width="600">
						<tr>
							<td width="50">
								<p align="right" style="margin-top: 0; margin-bottom: 0">
									<img border="0" src="/img/unex15.gif" width="75" height="75">
								</p>
							</td>
							<td width="500">
								<p class="titulo">UNIVERSIDAD NACIONAL EXPERIMENTAL POLIT&Eacute;CNICA</p>
								<p class="titulo">"ANTONIO JOSE DE SUCRE"</p>
								<p class="titulo">VICE-RECTORADO <?php echo strtoupper($vicerrectorado); ?></font></p>
								<p class="titulo"><?php echo strtoupper($nombreDependencia) ?></font>
							</td>
							<td width="50">&nbsp;</td>
						</tr>
					</table>
				</td>
			<tr><td colspan="5" style="background-color:#99CCFF;"><font style="font-size:2px;">&nbsp;</font></td></tr>
			</tr>
			<tr><td class="enc_p" colspan="5">FORMULARIO DE SOLICITUD</td></tr>
			<tr align="center">
				<td colspan="5">
					<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;">
					<tbody>
						<tr>
							<td style="width: 200px;" bgcolor="#FFFFFF"><div class="datosp"><B>Apellidos:</B></div></td>
							<td style="width: 200px;" bgcolor="#FFFFFF"><div class="datosp"><B>Nombres:</B></div></td>
							<td style="width: 200px;" bgcolor="#FFFFFF"><div class="datosp"><B>C&eacute;dula:</B></div></td>
						</tr>
						<tr>
							<td bgcolor="#FFFFFF"><div class="datosp"><?php echo $dp[0]." ".$dp[5] ?></div></td>
							<td bgcolor="#FFFFFF"><div class="datosp"><?php echo $dp[1]." ".$dp[6] ?></div></td>
							<td bgcolor="#FFFFFF"><div class="datosp"><?php echo $dp[2] ?></div></td>
						</tr>
						<tr>
							<td bgcolor="#FFFFFF">
								<div class="datosp"><B>Expediente:</B> <?php echo $dp[3] ?></div>
							</td>
							<td bgcolor="#FFFFFF" colspan="3">
								<div class="datosp"><B>Especialidad:</B> <?php echo $dp[4] ?></div>
							</td>
						</tr>
					</tbody>
					</table>
				</td>
			</tr>
			<tr><td colspan="5" style="background-color:#99CCFF;"><font style="font-size:2px;">&nbsp;</font></td></tr>
			<tr><td colspan="5"><font style="font-size:10px;">&nbsp;</font></td></tr>
            <tr>
				<td width="100%">
					<table width="100%">
						<tr>
							<td width="20%" class="datosp" style="text-align:center">
								<strong>Tipo de Solicitud:</strong>
						   </td>
							<td width="20%" class="datosp" style="text-align:center">
								<strong>Tipo de Pasantia</strong>
						   </td>
							<td width="20%" class="datosp" style="text-align:center">
								<strong>Fecha de Inicio:</strong>
						   </td>
						</tr>
						<tr>
							<td class="datosp" style="text-align:center">
								<select name="Sel_Tipo_Carta" id="Sel_Tipo_Carta" class="datospf">
									<option value="">SELECCIONE UNA OPCI&Oacute;N</option>
									<option value="1">Carta de Presentación</option>
									<option value="2">Carta de Postulacion</option>		
								</select>                    
							</td>
							<td class="datosp" style="text-align:center">
								<select name="Sel_Tipo_Pasantia" id="Sel_Tipo_Pasantia" class="datospf">
									<option value="">SELECCIONE UNA OPCI&Oacute;N</option>
									<option value="1">Practica Profesional</option>
									<option value="2">Trabajo de Grado</option>		
								</select>                    
							</td>
							<td class="datosp" style="text-align:center">
								<input name="Sel_Fecha" type="text" id="dateArrival" onClick="popUpCalendar(this, Solicitud.dateArrival, 'dd-mm-yyyy');" readonly size="10" class="datospf"> <img src="images/cal.jpeg" width="18" height="15" border="0" alt="Presione para seleccionar una fecha" title="Presione para seleccionar una fecha" onClick="popUpCalendar(document.Solicitud.Sel_Fecha, Solicitud.dateArrival, 'dd-mm-yyyy');">

								<!-- <select name="Sel_Fecha" id="Sel_Fecha" class="datospf">
									<option value="">SELECCIONE UNA OPCI&Oacute;N</option>
									<?php
										for ($i = 0; $i < 12; $i++) {
											$Fecha_Mes = mktime(0, 0, 0, date("m") + $i, 1, date("Y"));
											?>
											<option value="<?php echo date("Y-m", $Fecha_Mes); ?>">
												<?php echo Mes_Txt(date("m", $Fecha_Mes)) ." ". date("Y", $Fecha_Mes); ?>
											</option>
											<?php
										}
									?>
								</select> -->
							</td>
						</tr>
						<tr>
							<td colspan="5">
								<table width="100%">
									<tr>
										<td class="datosp" style="text-align:center">
											<strong>Empresa</strong>
									   </td>
									</tr>
									<tr>
										<td class="datosp" style="text-align:center">
											<select name="Sel_Empresa" id="Sel_Empresa" class="datospf">
												<option value="">SELECCIONE UNA OPCI&Oacute;N</option>
												<?php foreach ($Datos_Emp as $D_Emp){ ?>	
													<option value="<?php echo $D_Emp[0] ?>"><?php echo $D_Emp[1] ?></option>
												<?php }?>
											</select>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr><td colspan="5">&nbsp;</td></tr>
			<tr class="act">
				<td class="datosp" colspan="5" style="text-align:center" valign="middle">
					<BR>Solo seran procesadas las solicitudes que cumplan TODOS los Pre-Requisitos<BR><BR>
			  </td>
			</tr>
			<tr>
				<td colspan="5" height="40" align="center" valign="bottom">
					<input type="hidden" name="Form_Exp_E" value='<?php echo $dp[3] ?>'>
					<input type="submit" value='Enviar Solicitud' class="boton">
				</td>
			</tr>
		</table>
  </form>
	</div>
</body>
</html>
	