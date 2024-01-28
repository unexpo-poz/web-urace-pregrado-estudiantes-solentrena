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

	foreach ($_POST as $Post => $value){
		//print $Post . " = " . $value . "<BR>";
	}

	$No_Permitir = 0;
	$Ya_Existe_Sol = 0;
	$Est_Inactivo = 0;
	if(isset($_POST['Sel_Tipo_Carta']) && isset($_POST['Sel_Tipo_Pasantia']) && isset($_POST['Sel_Fecha']) && isset($_POST['Form_Exp_E']) && isset($_POST['Sel_Empresa'])){
		$Exp_E = $_POST['Form_Exp_E'];
		$Fecha_E = explode("-", $_POST['Sel_Fecha']);
		$Empresa_E = $_POST['Sel_Empresa'];
		$Tipo_Carta = $_POST['Sel_Tipo_Carta'];
		$Tipo_Pasantia = $_POST['Sel_Tipo_Pasantia'];
		
		if ($Tipo_Carta == 2){// si es postulación

			// buscar que no haya postulacion para ese expediente.
			$No_Permitir = validar_post($Exp_E,$Tipo_Pasantia);
			//echo "1: ".$No_Permitir;
			If ((Validar_Requisitos() == 0) || ($No_Permitir == 1)){
				$No_Permitir = 1;
			}
			//echo "2: ".$No_Permitir;
		}else{// si es presentacion
			# rutina para nueva validacion 22/11/2010
			$No_Permitir = validar_pres($Exp_E,$Tipo_Pasantia);
		}
	}elseif(isset($_POST['F_Exp_E']) && isset($_POST['F_Fecha']) && isset($_POST['F_Emp']) && isset($_POST['F_Tipo_C']) && isset($_POST['F_Tipo_P']) && $_POST['Procesar']== 1){
	//	GRABAR LA SOLICITUD
		$F_Exp_E = $_POST['F_Exp_E'];
		$F_Fecha = $_POST['F_Fecha'];
		$F_Emp = $_POST['F_Emp'];
		$F_Tipo_C = $_POST['F_Tipo_C'];
		$F_Tipo_P = $_POST['F_Tipo_P'];
		Graba_Solicitud();
		
		$Exp_E = $F_Exp_E;
		//$Fecha_E = explode("-", D_M_Y__to__Y_M_D($F_Fecha));
		$Fecha_E = explode("-", $F_Fecha);
		$Empresa_E = $F_Emp;
		$Tipo_Carta = $F_Tipo_C;
		$Tipo_Pasantia = $F_Tipo_P;
//		return;
	//	GRABAR LA SOLICITUD
	}else{
		Redirect_URL($raizDelSitio);
//		print "ERROR - FALTA ALGUN PARAMETRO";
		return;
	}

//	FALTA COLOCAR VALIDACION::: CUANDO LA CEDULA NO EXISTA EN LA BASE DE DATOS
//	FALTA COLOCAR VALIDACION::: VERIFICAR LA CLAVE

	$Cdp = new ODBC_Conn($DSN, $user_db, $pass_db, $ODBCC_conBitacora, $laBitacora);
	$mSQL = "SELECT APELLIDOS, NOMBRES, CI_E, EXP_E, CARRERA, APELLIDOS2, NOMBRES2 "; 
	$mSQL = $mSQL."FROM DACE002 A, TBLACA010 B ";
	$mSQL = $mSQL."WHERE EXP_E ='".$Exp_E."' AND A.C_UNI_CA = B.C_UNI_CA";
	$Cdp->ExecSQL($mSQL,__LINE__,true);
	$datosp = $Cdp->result;
	foreach ($datosp as $dp){}

	//print_r($Fecha_E);

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
function Grabar(f) {
	f.Procesar.value = 1;
}	
function Salir(f) {
	if(confirm("Esta Seguro que desea Salir del Sistema?")){
		window.close();
	}
}	
</script>

</head>
<body>

<div style="visibility: block;">
	<form name="Solicitud" action="" method="post" onSubmit="return Grabar(this)">
		<input type="hidden" name="F_Exp_E" value="<?php echo $Exp_E ?>">
		<input type="hidden" name="F_Fecha" value="<?php echo $Fecha_E[0]."-".$Fecha_E[1]."-".$Fecha_E[2] ?>">
		<input type="hidden" name="F_Emp" value="<?php echo $Empresa_E ?>">
		<input type="hidden" name="F_Tipo_C" value="<?php echo $Tipo_Carta ?>">
		<input type="hidden" name="F_Tipo_P" value="<?php echo $Tipo_Pasantia ?>">
		<input type="hidden" name="Procesar" id="Procesar" value="0">

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
							<td width="20%" class="datosp" style="text-align:center"><strong>Tipo de Carta:</strong></td>
							<td width="20%" class="datosp" style="text-align:center"><strong>Tipo de Pasantia:</strong></td>
							<td width="20%" class="datosp" style="text-align:center"><strong>Fecha de Inicio:</strong></td>
						</tr>
						<tr>
							<td class="datosp" style="text-align:center"><?php print Tipo_Solicitud($Tipo_Carta) ?></td>
							<td class="datosp" style="text-align:center"><?php print Tipo_Pasantia($Tipo_Pasantia) ?></td>
							<td class="datosp" style="text-align:center">
						<?php print $Fecha_E[0]." de ".Mes_Txt($Fecha_E[1]) ." de ". $Fecha_E[2] ?></td>
						</tr>
						<tr>
							<td><BR></td>
						</tr>
						<tr>
							<td colspan="3" class="datosp" style="text-align:center"><strong>Empresa</strong></td>
						</tr>
						<tr>
							<td colspan="3" class="datosp" style="text-align:center">
							<?php Nombre_Empresa($Empresa_E, $ODBCC_conBitacora, $laBitacora, $DSN, $user_db, $pass_db) ?>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<?php if ($No_Permitir == 1){?>
			<?php 	if ($Est_Inactivo == 1){?>
				<tr><td colspan="5">&nbsp;</td></tr>
				<tr bgcolor="#FFFF00">
					<td class="datosp" colspan="5" style="text-align:center; color:#FF0000" valign="middle">
						<BR><strong>Estudiante INACTIVO</strong><BR><BR>
				  </td>
				</tr>
			<?php 	} 
				else{
			?>
				<tr><td colspan="5">&nbsp;</td></tr>
				<tr class="act">
					<td class="datosp" colspan="5" style="text-align:center" valign="middle">
						<BR>Solo seran procesadas las solicitudes que cumplan TODOS los Pre-Requisitos<BR><BR>
				  </td>
				</tr>
				<tr bgcolor="#FFFF00">
					<td class="datosp" colspan="5" style="text-align:center; color:#FF0000; font-size:16px;" valign="middle">
						<BR><strong><?php print $Error_Validacion;?></strong><BR>
				  </td>
				</tr>
			<?php 
				}
			} ?>
			<?php if ($Ya_Existe_Sol == 1){?>
			<tr><td colspan="5">&nbsp;</td></tr>
			<tr class="act">
				<td class="datosp" colspan="5" style="text-align:center" valign="middle">
			<?php
					$codval = $codPres;

					$id = substr($codval,6);
					$lapso = "20".substr($codval,3,2)."-".substr($codval,5,1);

					echo "Ya existe una carta de presentacion con esos datos.<br><br>";

					if (!empty($codval)) {
						echo "<a href=\"../../../solicitudes/reportes/report.php?id=".$id."&lapso=".$lapso."&prog=0\">Presione AQUI para ver su carta.</a>";
						echo "<br><br>La carta debe ser validada en el Dpto. de Entrenamiento Industrial, en horario de oficina.";
					}
			?>


			  </td>
			<?php } ?>
			<?php if (($No_Permitir <> 1) && ($Ya_Existe_Sol <> 1) && ($Est_Inactivo <> 1)){?>
				<tr>
					<td colspan="5" height="40" align="center" valign="bottom">
						<input type="submit" value='Confirmar Solicitud' class="boton">
					</td>
				</tr>
			<?php 
				} 
				else{?>
				<tr>
					<td colspan="5" height="40" valign="bottom">
						<table width="100%">
							<tr>
								<td align="center" width="40%">
									<input type="button" value='Volver' class="boton" onClick="history.back()">
								</td>
								<td align="center" width="20%">&nbsp;</td>
								<td align="center" width="40%">
									<input type="button" value='Salir' class="boton" onClick="Salir();">
								</td>
							</tr>
						</table>
					</td>
				</tr>
			<?php } ?>
		</table>
  </form>
	</div>
</body>
</html>
	
<?php 
function Graba_Solicitud(){
	global $Ya_Existe_Sol, $DSN, $user_db, $pass_db, $F_Exp_E, $F_Tipo_C, $F_Tipo_P, $F_Emp, $F_Fecha, $ODBCC_conBitacora, $laBitacora, $C_Graba;

	global $DSN_Solicita, $user_s, $pass_s, $lapsoProceso, $C_Graba;

	global $codPres;

	//echo $DSN_Solicita, $user_s, $pass_s, $lapsoProceso, $C_Graba;

	$C_Graba = new ODBC_Conn($DSN, $user_db, $pass_db, $ODBCC_conBitacora, $laBitacora);
	$SQL_Get_Last_Id = "SELECT MAX(@VALUE(ID_SOLICITUD)) AS ULTIMO FROM SOL_SOLICITUDES ORDER BY ID_SOLICITUD DESC"; 
	$C_Graba->ExecSQL($SQL_Get_Last_Id,__LINE__,true);
	$Result = $C_Graba->result;
	foreach ($Result as $R){}
	$Sig_ID = $R[0] + 1;

	$C_Emp = new ODBC_Conn($DSN, $user_db, $pass_db, $ODBCC_conBitacora, $laBitacora);
	$SQL_Emp = "SELECT NOMBRE_EMPRESA, DESTINATARIO, CARGO_DPTO FROM SOL_EMPRESAS WHERE ID_EMPRESA = ".$F_Emp ;
	$C_Emp->ExecSQL($SQL_Emp,__LINE__,true);
	$Datos_Emp = $C_Emp->result;
	foreach ($Datos_Emp as $D_Emp){}

	$SQL_Verif = "SELECT EXP_E,codval FROM SOL_SOLICITUDES WHERE EXP_E = '".$F_Exp_E."' AND TIPO_SOLICITUD = ".$F_Tipo_C." AND TIPO_PASANTIA = ".$F_Tipo_P;
	$SQL_Verif = $SQL_Verif ." AND ID_EMPRESA = '".$F_Emp."' AND FECHA_INICIO = '".D_M_Y__to__M_D_Y($F_Fecha)."'";
	$C_Graba->ExecSQL($SQL_Verif,__LINE__,true);
	$Datos_Verif = $C_Graba->result;

	if (empty($Datos_Verif)) {
		$conex_solicita = new ODBC_Conn($DSN_Solicita, $user_s, $pass_s, true, $laBitacora);

		# 1.- Buscar MAX(id_solicitud)
		$max_id = "SELECT MAX(id_solicitud) FROM solicitudes WHERE lapso='".$lapsoProceso."' ";
		$conex_solicita->ExecSQL($max_id,__LINE__,true);
		$max_id_solicitud = $conex_solicita->result[0][0];//El max id_solicitud + 1

		$max_id_solicitud = $max_id_solicitud+1;
		
		#combinacion de $F_Tipo_P+$F_Tipo_C
		switch ($F_Tipo_P.$F_Tipo_C){
			case 11:
			$documento = "22";
			break;
			case 12:
			$documento = "23";
			break;
			case 21:
			$documento = "24";
			break;
			case 22:
			$documento = "25";
			break;
		}
		$fecha_solicitud = date('m-d-Y');
		$estatus = "1";

		$time = time();
		$id_codigo = $time.'-'.$documento;

		// Insertar en solicita.solicitudes el registro
		$insert = "id_solicitud,exp_e,programa,documento,lapso,fecha_solicitud,estatus,id_codigo";
		$valores_s = "'".$max_id_solicitud."','".$F_Exp_E."',0,'".$documento."', ";
		$valores_s.= "'".$lapsoProceso."','".$fecha_solicitud."','".$estatus."','".$id_codigo."'";

		$sSQL = "INSERT INTO solicitudes (".$insert.")";
		$sSQL.= " VALUES ";
		$sSQL.= "(".$valores_s.") ";
	
		$conex_solicita->ExecSQL($sSQL,__LINE__,true);
			
		// gENERAR VALOR DE CARTA (MAX)+1

		$carta2 = date("Y");
		$sqlCARTA = "SELECT MAX(CARTA) FROM SOL_SOLICITUDES WHERE CARTA2 = '".$carta2."' AND TIPO_SOLICITUD = '".$F_Tipo_C."'";
		$C_Graba->ExecSQL($sqlCARTA,__LINE__,true);

		$carta = ++$C_Graba->result[0][0];

		$codval = $documento."0".substr($lapsoProceso,2,2).substr($lapsoProceso,-1).$max_id_solicitud;

		$SQL_Graba = "INSERT INTO SOL_SOLICITUDES (ID_SOLICITUD, EXP_E, TIPO_SOLICITUD, TIPO_PASANTIA, ID_EMPRESA, DESTINATARIO, CARGO_DPTO, FECHA_INICIO, ESTATUS, FECHA_SOLICITUD, CODVAL, CARTA, CARTA2) ";
		$SQL_Graba = $SQL_Graba . "VALUES (".$Sig_ID.", '".$F_Exp_E."', ".$F_Tipo_C.", ";
		$SQL_Graba = $SQL_Graba . $F_Tipo_P.", '".$F_Emp."', '".$D_Emp[1]."', '".$D_Emp[2]."', ".D_M_Y__to__M_D_Y($F_Fecha).", 0, ".date('m-d-Y h:i:s A').",'".$codval."','".$carta."','".$carta2."')";
		$C_Graba->ExecSQL($SQL_Graba,__LINE__,true);

		//$codval
		
		
		// $F_Tipo_C = tipo de solicitud (presentacion o postulacion)
		// $F_Tipo_P = tipo de pasantia (practica o trabajo de grado)

		// Insertar en solicita.solicitudes el registro
		# 1.- Buscar MAX(id_solicitud) from solicitudes e incrementarle 1
		# 2.- MAX(id_solicitud)+1, $F_Exp_E, 0, combinacion de $F_Tipo_C+$F_Tipo_P, $lapsoProceso, date('m-d-Y'), 1, id_codigo(buscar);

		// Conectarse a SOLICITA

		//echo $DSN_Solicita, $user_s, $pass_s;

		$guarda = ($conex_solicita->fmodif > 0);
		if ($guarda){
			echo "<script languaje=\"javascript\"> window.open('../../../solicitudes/reportes/report_e.php?id=".$max_id_solicitud."&lapso=".$lapsoProceso."&prog=0&id_sol=".$Sig_ID."&t_solicitud=".$F_Tipo_C."&f_inicio=".D_M_Y__to__M_D_Y($F_Fecha)."&t_pasantia=".$F_Tipo_P."','','left=100,top=100,width=790,height=600,scrollbars=1,resizable=1,status=0');</script>";
		}
		
		// Emitir la carta en formato pdf
		
	} else {
		$codPres = $Datos_Verif[0][1];
		$Ya_Existe_Sol = 1;
	}
}

/*function Validar_Requisitos(){
	global $Est_Inactivo, $V_Pensum, $Tipo_Pasantia, $Exp_E, $DSN, $user_db, $pass_db, $ODBCC_conBitacora, $laBitacora, $Error_Validacion;
	
	$C_Valida = new ODBC_Conn($DSN, $user_db, $pass_db, $ODBCC_conBitacora, $laBitacora);
	$SQL_Valida = "SELECT EXP_E, PENSUM, C_UNI_CA, ESTATUS_E, U_CRED_PEN_T FROM DACE002 WHERE EXP_E = '".$Exp_E."'"; 
	$C_Valida->ExecSQL($SQL_Valida,__LINE__,true);
	$Result = $C_Valida->result;
	foreach ($Result as $R){}
		$V_Pensum = $R[1];
		$V_Carrera = $R[2];
		$V_Estatus_E = $R[3];
		$V_UC_Aprob = $R[4];
	
	# Funcion añadida el 08/05/2015 en atencion a comunicacion UREI/067/15 del Dpto de Entrenamiento Industrial.
	//$V_UC_Aprob += creditos_inscritos($Exp_E);

	//echo $V_UC_Aprob."<br>".creditos_inscritos($Exp_E);

	If ($V_Estatus_E != 1){
		$Est_Inactivo = 1;
	}
	else{
		if ($V_Pensum <= 4){$V_Pensum = 4;}
//	print $R[0]." - ".$V_Pensum." - ".$V_Carrera. "<BR>";
//	print "Codigo Pasantia ==>". Get_Codigo_Practica($V_Pensum, $V_Carrera, $Tipo_Pasantia). "<BR>";
	

	$SQL = "SELECT PRE_COD_ASIG1, PRE_COD_ASIG2, PRE_COD_ASIG3, PRE_COD_ASIG4, PRE_COD_ASIG5, ";
	$SQL.= "PRE_COD_ASIG6, PRE_COD_ASIG7, UNI_CRED_REQ, PAR_COD_ASIG1, PAR_COD_ASIG2, ";
	$SQL.= "PAR_COD_ASIG3, C_ASIGNA, C_UNI_CA, PENSUM ";
	$SQL.= "FROM TBLACA009 WHERE C_ASIGNA = ";
	$SQL.= "'".Get_Codigo_Practica($V_Pensum, $V_Carrera, $Tipo_Pasantia)."' ";
	$SQL.= "AND C_UNI_CA = ".$V_Carrera." AND PENSUM = ".$V_Pensum; 
	$C_Valida->ExecSQL($SQL,__LINE__,true);
	$Result = $C_Valida->result;

	foreach ($Result as $Res){}
	
		@$V_Pre_Req = array($Res[0],$Res[1],$Res[2],$Res[3],$Res[4],$Res[5],$Res[6]);	// Tiene que estar en DACE004
		@$V_UC_Req = $Res[7];												// Tiene que coincidir con valor en DACE002
		@$V_Co_Req = array($Res[8],$Res[9],$Res[10]);							// Tiene que estar en DACE004 o DACE006
	
	$Test_UC = 1;	//	Test de Unidades Creditos
	$Test_Pre = 1;	//	Test de Pre_Requisitos
	$Test_Co = 1;	//	Test de Co_Requisitos
	
	# Rutina añadida el 12/05/2015 en atencion a comunicacion UREI/067/15 del Dpto de Entrenamiento Industrial.
	
	if ($Tipo_Pasantia == 1) {// SI es PP (todas las carreras, excepto electronica) o PPG (solo electronica)
		switch ($V_Carrera) {
			case '2':
				$V_UC_Req = 141;
				break;
			case '3':
				$V_UC_Req = 130;
				break;
			case '4':
				$V_UC_Req = 140;
				break;
			case '5':
				######### AÑADIR CONDICIONAL PARA POSTULACION #####
				$V_UC_Req = 136;
				break;
			case '6':
				$V_UC_Req = 139;
				break;		
		}
	}

	//echo $V_UC_Aprob." < ".$V_UC_Req; 
	
	
	If ($V_UC_Aprob < $V_UC_Req){
	//if (false) {
		$Test_UC = 0; // NO Pasa la Validacion de las UC
		$Error_Validacion = "NO Tiene Suficientes U.C. Aprobadas (Faltan ".($V_UC_Req - $V_UC_Aprob)." U.C.) "."<BR>";
	}

//-----------	VERIFICA PRE-REQUISITOS	--------------------
	for ($i = 0; $i <= count($V_Pre_Req) - 1; $i++){
		if (!empty($V_Pre_Req[$i])){
//			print "-".$i."-".$V_Pre_Req[$i]. "<BR>";
			If (Verifica_Materia($V_Pre_Req[$i], 1) == 0){
				$Test_Pre = 0; // NO Pasa la Validacion de los Pre-Requisitos
				$Error_Validacion = $Error_Validacion ."NO Tiene Todos los Pre-Requisitos (Le Falta Cursar: ".$V_Pre_Req[$i].") "."<BR>";
			}
		}
	}
	
//-----------	VERIFICA CO-REQUISITOS	--------------------
	for ($i = 0; $i <= count($V_Co_Req) - 1; $i++){
		if (!empty($V_Co_Req[$i])){
//			print "-".$i."-".$V_Co_Req[$i]. "<BR>";
			If ((Verifica_Materia($V_Co_Req[$i], 0) == 0)&&	(Verifica_Materia($V_Co_Req[$i], 1) == 0)){
				$Test_Co = 0; // NO Pasa la Validacion de los Co-Requisitos
				$Error_Validacion = $Error_Validacion ."NO Tiene Todos los Co-Requisitos (Le Falta Cursar: ".$V_Co_Req[$i].") "."<BR>";
			}
		}
	}

//		print $V_UC_Req. "<BR>";
//		print_r ($V_Pre_Req);
//		print "<BR>";
//		print_r ($V_Co_Req);
//		print "<BR>";
		
		If (($Test_UC == 0) || ($Test_Pre == 0) || ($Test_Co == 0)){
			return 0;
		}
		else{
			return 1;
		}
	}	
}*/

##### OJO ESTA FUNCION SE EJECUTA SOLO PARA POSTULACION
function Validar_Requisitos(){
	global $Est_Inactivo, $V_Pensum, $Tipo_Pasantia, $Exp_E, $DSN, $user_db, $pass_db, $ODBCC_conBitacora, $laBitacora, $Error_Validacion;

	### DESACTIVADO SE ASUME QUE ES POSTULACION
	//global $Tipo_Carta;// Tipo de Solicitud (Pres/Post);
	
	$C_Valida = new ODBC_Conn($DSN, $user_db, $pass_db, $ODBCC_conBitacora, $laBitacora);
	$SQL_Valida = "SELECT EXP_E, PENSUM, C_UNI_CA, ESTATUS_E, U_CRED_PEN_T FROM DACE002 WHERE EXP_E = '".$Exp_E."'"; 
	$C_Valida->ExecSQL($SQL_Valida,__LINE__,true);
	$Result = $C_Valida->result;
	foreach ($Result as $R){}
		$V_Pensum = $R[1];
		$V_Carrera = $R[2];
		$V_Estatus_E = $R[3];
		$V_UC_Aprob = $R[4];

	If ($V_Estatus_E != 1){
		$Est_Inactivo = 1;
	}
	else{
		if ($V_Pensum <= 4){$V_Pensum = 4;}

	$SQL = "SELECT PRE_COD_ASIG1, PRE_COD_ASIG2, PRE_COD_ASIG3, PRE_COD_ASIG4, PRE_COD_ASIG5, ";
	$SQL.= "PRE_COD_ASIG6, PRE_COD_ASIG7, UNI_CRED_REQ, PAR_COD_ASIG1, PAR_COD_ASIG2, ";
	$SQL.= "PAR_COD_ASIG3, C_ASIGNA, C_UNI_CA, PENSUM ";
	$SQL.= "FROM TBLACA009 WHERE C_ASIGNA = ";
	$SQL.= "'".Get_Codigo_Practica($V_Pensum, $V_Carrera, $Tipo_Pasantia)."' ";
	$SQL.= "AND C_UNI_CA = ".$V_Carrera." AND PENSUM = ".$V_Pensum; 
	$C_Valida->ExecSQL($SQL,__LINE__,true);
	$Result = $C_Valida->result;

	foreach ($Result as $Res){}
	
		@$V_Pre_Req = array($Res[0],$Res[1],$Res[2],$Res[3],$Res[4],$Res[5],$Res[6]);	// Tiene que estar en DACE004
		@$V_UC_Req = $Res[7];												// Tiene que coincidir con valor en DACE002
		@$V_Co_Req = array($Res[8],$Res[9],$Res[10]);							// Tiene que estar en DACE004 o DACE006
	
	$Test_UC = 1;	//	Test de Unidades Creditos
	$Test_Pre = 1;	//	Test de Pre_Requisitos
	$Test_Co = 1;	//	Test de Co_Requisitos

	# Rutina añadida el 12/05/2015 en atencion a comunicacion UREI/067/15 del Dpto de Entrenamiento Industrial.
	## Modificacion añadida el 27/04/2016
	### (1) Se toman para postulacion los creditos necesarios segun pensum.
	if ($Tipo_Pasantia == 1) {
		switch ($V_Carrera) {
			case '2':
				//$V_UC_Req = 141;
				$V_UC_Req = 147; //Requisito del pensum
				
				##Si es Postulacion
				//$V_UC_Req = ($Tipo_Carta == '2') ? '147' : '141' ;
				break;
			case '3':
				//$V_UC_Req = 130;
				$V_UC_Req = 142; //Requisito del pensum

				##Si es Postulacion
				//$V_UC_Req = ($Tipo_Carta == '2') ? '142' : '130' ;
				break;
			case '4':
				//$V_UC_Req = 140;
				$V_UC_Req = 148; //Requisito del pensum
				
				##Si es Postulacion
				//$V_UC_Req = ($Tipo_Carta == '2') ? '148' : '140' ;
				break;
			case '5':
				//$V_UC_Req = 136;
				$V_UC_Req = 166; //Requisito del pensum

				##Si es Postulacion
				//$V_UC_Req = ($Tipo_Carta == '2') ? '166' : '136' ;
				break;
			case '6':
				//$V_UC_Req = 139;
				$V_UC_Req = 145; //Requisito del pensum

				##Si es Postulacion
				//$V_UC_Req = ($Tipo_Carta == '2') ? '145' : '139' ;
				break;		
		}
	}

	//echo $V_UC_Aprob." < ".$V_UC_Req;
	
	If ($V_UC_Aprob < $V_UC_Req){
		$Test_UC = 0; // NO Pasa la Validacion de las UC
		$Error_Validacion = "NO Tiene Suficientes U.C. Aprobadas (Faltan ".($V_UC_Req - $V_UC_Aprob)." U.C.) "."<BR>";
	}

//-----------	VERIFICA PRE-REQUISITOS	--------------------
	for ($i = 0; $i <= count($V_Pre_Req) - 1; $i++){
		if (!empty($V_Pre_Req[$i])){
//			print "-".$i."-".$V_Pre_Req[$i]. "<BR>";
			If (Verifica_Materia($V_Pre_Req[$i], 1) == 0){
				$Test_Pre = 0; // NO Pasa la Validacion de los Pre-Requisitos
				$Error_Validacion = $Error_Validacion ."NO Tiene Todos los Pre-Requisitos (Le Falta Cursar: ".$V_Pre_Req[$i].") "."<BR>";
			}
		}
	}
	
//-----------	VERIFICA CO-REQUISITOS	--------------------
	for ($i = 0; $i <= count($V_Co_Req) - 1; $i++){
		if (!empty($V_Co_Req[$i])){
//			print "-".$i."-".$V_Co_Req[$i]. "<BR>";
			If ((Verifica_Materia($V_Co_Req[$i], 0) == 0)&&	(Verifica_Materia($V_Co_Req[$i], 1) == 0)){
				$Test_Co = 0; // NO Pasa la Validacion de los Co-Requisitos
				$Error_Validacion = $Error_Validacion ."NO Tiene Todos los Co-Requisitos (Le Falta Cursar: ".$V_Co_Req[$i].") "."<BR>";
			}
		}
	}

		If (($Test_UC == 0) || ($Test_Pre == 0) || ($Test_Co == 0)){
			return 0;
		}
		else{
			return 1;
		}
	}	
}// FIn Validar_Requisitos

function Verifica_Materia($C_Asig, $Temp){
	global $V_Pensum, $Exp_E, $DSN, $user_db, $pass_db, $ODBCC_conBitacora, $laBitacora;
	$C_Ver_Mat = new ODBC_Conn($DSN, $user_db, $pass_db, $ODBCC_conBitacora, $laBitacora);

	if ($Temp == 1){	//	VERIFICA LOS PRE-REQUISITOS, SI LA TIENE APROBADA
		if ($V_Pensum == 5){
			$SQL_Valida = "SELECT C_ASIGNA FROM DACE004 WHERE EXP_E ='".$Exp_E."' AND C_ASIGNA = '".$C_Asig."' AND STATUS IN ('0','3','B')";
		}
		else{
			$SQL_Valida = "SELECT @NULLVALUE(C_ASIGNA_2001, C_ASIGNA) AS C_ASIGNA FROM DACE004 WHERE EXP_E ='".$Exp_E."' AND C_ASIGNA = '".$C_Asig."' AND STATUS IN ('0','3','B')";
		}
	}
	else{	//	VERIFICA LOS CO-REQUISITOS, SOLO SI LA TIENE INSCRITA
		$SQL_Valida = "SELECT C_ASIGNA FROM DACE006 WHERE EXP_E ='".$Exp_E."' AND C_ASIGNA = '".$C_Asig."' AND STATUS IN ('7','A')";
	}
	$C_Ver_Mat->ExecSQL($SQL_Valida,__LINE__,true);
	$Result = $C_Ver_Mat->result;
	foreach ($Result as $V_M){}
	if ((!empty($V_M[0])) && ($V_M[0] == $C_Asig)){
		return 1;	//	SI TIENE LA MATERIA INSCRITA
	}
	else{
		return 0;
	}
}

function Get_Codigo_Practica($Pensum, $Carrera, $Tipo_P){
	if ($Pensum == 4){
		switch($Carrera){
			case "2":
				$PP= 324931;
				$TG= 322044;
				break;
			case "3":
				$PP= 314931;
				$TG= 311044;
				break;
			case "4":
				$PP= 334931;
				$TG= 333044;
				break;
			case "5":
				$PP= 355060;
				$TG= 355954;
				break;
			case "6":
				$PP= 344831;
				$TG= 344044;
				break;
		}
	}
	elseif ($Pensum == 5){
		switch($Carrera){
			case "2":// mecanica
				$PP= 322939;
				$TG= 322040;
				break;
			case "3":// electrica
				$PP= 311939;
				$TG= 311040;
				break;
			case "4":// metalurgica
				$PP= 333939;
				$TG= 333040;
				break;
			case "5":// electronica
				$PP= 355069;
				$TG= 355959;
				break;
			case "6":// industrial
				$PP= 344939;
				$TG= 344040;
				break;
		}
	}
	
	If ($Tipo_P == 1){		// Practica Profesional
		return $PP;
	}
	elseif ($Tipo_P == 2){	//	Trabajo de Grado
		return $TG;
	}
}

function creditos_inscritos ($exp_e) {
	global $DSN, $user_db, $pass_db;
	
	$conex = New ODBC_Conn($DSN, $user_db, $pass_db, true, 'test.log');

	#Seleccionar creditos inscritos en dace006
	$cSQL = "SELECT SUM(u_creditos*1) ";
	$cSQL.= "FROM tblaca009 a, dace006 b, dace002 c ";
	$cSQL.= "WHERE b.exp_e='".$exp_e."' AND b.status IN ('7','A') AND a.c_asigna=b.c_asigna ";
	$cSQL.= "AND b.exp_e=c.exp_e AND a.pensum=c.pensum AND a.c_uni_ca=c.c_uni_ca ";
	$conex->ExecSQL($cSQL,__LINE__,true);

	//echo $cSQL;

	$uc_inscritas = $conex->result[0][0];

	return $uc_inscritas;
}

function validar_pres($exp_e,$tipo){
	global $DSN, $user_db, $pass_db, $ODBCC_conBitacora, $laBitacora,$Error_Validacion;
	
	$conex = New ODBC_Conn($DSN, $user_db, $pass_db, true, 'pruebas.log');
	$sql = "SELECT estatus_e,pensum,c_uni_ca,u_cred_pen_t ";
	$sql.= "FROM dace002 ";
	$sql.= "WHERE exp_e='".$exp_e."' ";
	$conex->ExecSQL($sql,__LINE__,true);
	
	$estatus_e	= $conex->result[0][0];
	$pensum		= $conex->result[0][1];
	$c_uni_ca	= $conex->result[0][2];
	$creditos	= $conex->result[0][3];

	# Funcion añadida el 08/05/2015 en atencion a comunicacion UREI/067/15 del Dpto de Entrenamiento Industrial.
	$creditos += creditos_inscritos($exp_e);

	switch ($c_uni_ca) {
		case 2:// Mecanica
			switch ($tipo) {
				case 1: // Practica Profesional
					($pensum == 5) ? $req = "'322728','324862'" : $req = "'322828','324862'";

					#creditos necesarios
					$credn = 141;					
					
					# Consulta requisito				
					$sql = "SELECT distinct c_asigna,status ";
					$sql.= "FROM dace004 ";
					$sql.= "WHERE exp_e='".$exp_e."' AND c_asigna in (".$req.") ";
					$sql.= "AND status in (0) ";
					$sql.= "UNION ";
					$sql.= "SELECT distinct c_asigna,status ";
					$sql.= "FROM dace006 ";
					$sql.= "WHERE exp_e='".$exp_e."' AND c_asigna in (".$req.") ";
					$sql.= "AND status in (7,'A') ";
					$conex->ExecSQL($sql,__LINE__,true);

					if (($conex->filas == 2) && ($creditos >= $credn)) {
						return false;		
					}else{
						
						if ($creditos < $credn){// mostrar error por UC faltantes
							$Error_Validacion.="No cumple con ".$credn." UC. Aprobadas (Faltan ".($credn - $creditos).")<br>";							
						}

						if ($conex->filas < 2){// mostrar error por REQUISITOS faltantes
							$Error_Validacion.="No cumple con ".str_replace(',',' y ',str_replace('\'','',$req))." Inscrito/Aprobado.<br>";
						}
						
						return true;
					}					
					break;
				case 2: // Trabajo de Grado
					$c_asigna = Get_Codigo_Practica($pensum, $c_uni_ca, 1);
					
					# Consulta requisito				
					$sql = "SELECT distinct c_asigna,status ";
					$sql.= "FROM dace004 ";
					$sql.= "WHERE exp_e='".$exp_e."' AND c_asigna='".$c_asigna."' ";
					$sql.= "AND status in (0) ";
					$sql.= "UNION ";
					$sql.= "SELECT distinct c_asigna,status ";
					$sql.= "FROM dace006 ";
					$sql.= "WHERE exp_e='".$exp_e."' AND c_asigna='".$c_asigna."' ";
					$sql.= "AND status in (7,'A') ";
					$conex->ExecSQL($sql,__LINE__,true);

					if ($conex->filas >= 1) {
						return false;		
					}else{
						$Error_Validacion.="No cumple con requisito (".$c_asigna." - ";
						$nsql = "SELECT asignatura FROM tblaca008 ";
						$nsql.= "WHERE c_asigna='".$c_asigna."' ";
						$conex->ExecSQL($nsql);
						$Error_Validacion.=$conex->result[0][0];
						$Error_Validacion.=") Inscrito/Aprobado.<br>";
						return true;
					}
					break;
				default:
					return true;
					break;
			}
			break;// fin Mecanica
		case 3:// Electrica
			switch ($tipo) {
				case 1: // Practica Profesional

					#creditos necesarios
					$credn = 130;

					if ($creditos >= $credn) {
						return false;		
					}else{
						$Error_Validacion.="No cumple con ".$credn." UC. Aprobadas (Faltan ".($credn - $creditos).").<br>";
						return true;
					}					
					break;
				case 2: // Trabajo de Grado
					$c_asigna = Get_Codigo_Practica($pensum, $c_uni_ca, 1);
					
					# Consulta requisito				
					$sql = "SELECT distinct c_asigna,status ";
					$sql.= "FROM dace004 ";
					$sql.= "WHERE exp_e='".$exp_e."' AND c_asigna='".$c_asigna."' ";
					$sql.= "AND status in (0) ";
					$sql.= "UNION ";
					$sql.= "SELECT distinct c_asigna,status ";
					$sql.= "FROM dace006 ";
					$sql.= "WHERE exp_e='".$exp_e."' AND c_asigna='".$c_asigna."' ";
					$sql.= "AND status in (7,'A') ";
					$conex->ExecSQL($sql,__LINE__,true);

					if ($conex->filas >= 1) {
						return false;		
					}else{
						$Error_Validacion.="No cumple con requisito (".$c_asigna." - ";
						$nsql = "SELECT asignatura FROM tblaca008 ";
						$nsql.= "WHERE c_asigna='".$c_asigna."' ";
						$conex->ExecSQL($nsql);
						$Error_Validacion.=$conex->result[0][0];
						$Error_Validacion.=") Inscrito/Aprobado.<br>";
						return true;
					}
					break;
				default:
					return true;
					break;
			}
			break;// fin Electrica
		case 4:// Metalurgica
			switch ($tipo) {
				case 1: // Practica Profesional

					#creditos necesarios
					$credn = 140;

					if ($creditos >= $credn) {
						return false;		
					}else{
						$Error_Validacion.="No cumple con ".$credn." UC. Aprobadas (Faltan ".($credn - $creditos).").<br>";
						return true;
					}					
					break;
				case 2: // Trabajo de Grado
					$c_asigna = Get_Codigo_Practica($pensum, $c_uni_ca, 1);
					
					# Consulta requisito				
					$sql = "SELECT distinct c_asigna,status ";
					$sql.= "FROM dace004 ";
					$sql.= "WHERE exp_e='".$exp_e."' AND c_asigna='".$c_asigna."' ";
					$sql.= "AND status in (0) ";
					$sql.= "UNION ";
					$sql.= "SELECT distinct c_asigna,status ";
					$sql.= "FROM dace006 ";
					$sql.= "WHERE exp_e='".$exp_e."' AND c_asigna='".$c_asigna."' ";
					$sql.= "AND status in (7,'A') ";
					$conex->ExecSQL($sql,__LINE__,true);

					if ($conex->filas >= 1) {
						return false;		
					}else{
						$Error_Validacion.="No cumple con requisito (".$c_asigna." - ";
						$nsql = "SELECT asignatura FROM tblaca008 ";
						$nsql.= "WHERE c_asigna='".$c_asigna."' ";
						$conex->ExecSQL($nsql);
						$Error_Validacion.=$conex->result[0][0];
						$Error_Validacion.=") Inscrito/Aprobado.<br>";
						return true;
					}
					break;
				default:
					return true;
					break;
			}
			break;// fin Metalurgica
		case 5:// Electronica
			switch ($tipo) {
				case 2: // Trabajo de Grado
					
					#creditos necesarios
					$credn = 136;

					if ($creditos >= $credn) {
						return false;		
					}else{
						$Error_Validacion.="No cumple con ".$credn." UC. Aprobadas (Faltan ".($credn - $creditos).").<br>";
						return true;
					}					
					break;
				case 1: // Practica Profesional
					$c_asigna = Get_Codigo_Practica($pensum, $c_uni_ca, 2);
					
					# Consulta requisito				
					$sql = "SELECT distinct c_asigna,status ";
					$sql.= "FROM dace004 ";
					$sql.= "WHERE exp_e='".$exp_e."' AND c_asigna='".$c_asigna."' ";
					$sql.= "AND status in (0) ";
					$sql.= "UNION ";
					$sql.= "SELECT distinct c_asigna,status ";
					$sql.= "FROM dace006 ";
					$sql.= "WHERE exp_e='".$exp_e."' AND c_asigna='".$c_asigna."' ";
					$sql.= "AND status in (7,'A') ";
					$conex->ExecSQL($sql,__LINE__,true);

					if ($conex->filas >= 1) {
						return false;		
					}else{
						$Error_Validacion.="No cumple con requisito (".$c_asigna." - ";
						$nsql = "SELECT asignatura FROM tblaca008 ";
						$nsql.= "WHERE c_asigna='".$c_asigna."' ";
						$conex->ExecSQL($nsql);
						$Error_Validacion.=$conex->result[0][0];
						$Error_Validacion.=") Inscrito/Aprobado.<br>";
						return true;
					}
					break;
				default:
					return true;
					break;
			}
			break;// fin Electronica
			
		case 6:// Industrial
			switch ($tipo) {
				case 1: // Practica Profesional
					($pensum == 5) ? $req = "'344834','344812'" : $req = "'344834','344712'";
					
					#creditos necesarios
					$credn = 139;

					# Consulta requisito				
					$sql = "SELECT distinct c_asigna,status ";
					$sql.= "FROM dace004 ";
					$sql.= "WHERE exp_e='".$exp_e."' AND c_asigna in (".$req.") ";
					$sql.= "AND status in (0) ";
					$sql.= "UNION ";
					$sql.= "SELECT distinct c_asigna,status ";
					$sql.= "FROM dace006 ";
					$sql.= "WHERE exp_e='".$exp_e."' AND c_asigna in (".$req.") ";
					$sql.= "AND status in (7,'A') ";
					$conex->ExecSQL($sql,__LINE__,true);

					if (($conex->filas == 2) && ($creditos >= 139)) {
						return false;		
					}else{
						
						/*$Error_Validacion.="No cumple con ".$credn." UC. Aprobadas (Faltan ".($credn - $creditos).")<br>No cumple con ".$req." Inscrito/Aprobado.<br>";
						return true;*/

						if ($creditos < $credn){// mostrar error por UC faltantes
							$Error_Validacion.="No cumple con ".$credn." UC. Aprobadas (Faltan ".($credn - $creditos).")<br>";							
						}

						if ($conex->filas < 2){// mostrar error por REQUISITOS faltantes
							$Error_Validacion.="No cumple con ".str_replace(',',' y ',str_replace('\'','',$req))." Inscrito/Aprobado.<br>";
						}
						
						return true;
					}					
					break;
				case 2: // Trabajo de Grado
					$c_asigna = Get_Codigo_Practica($pensum, $c_uni_ca, 1);
					
					# Consulta requisito				
					$sql = "SELECT distinct c_asigna,status ";
					$sql.= "FROM dace004 ";
					$sql.= "WHERE exp_e='".$exp_e."' AND c_asigna='".$c_asigna."' ";
					$sql.= "AND status in (0) ";
					$sql.= "UNION ";
					$sql.= "SELECT distinct c_asigna,status ";
					$sql.= "FROM dace006 ";
					$sql.= "WHERE exp_e='".$exp_e."' AND c_asigna='".$c_asigna."' ";
					$sql.= "AND status in (7,'A') ";
					$conex->ExecSQL($sql,__LINE__,true);

					if ($conex->filas >= 1) {
						return false;		
					}else{
						$Error_Validacion.="No cumple con requisito (".$c_asigna." - ";
						$nsql = "SELECT asignatura FROM tblaca008 ";
						$nsql.= "WHERE c_asigna='".$c_asigna."' ";
						$conex->ExecSQL($nsql);
						$Error_Validacion.=$conex->result[0][0];
						$Error_Validacion.=") Inscrito/Aprobado.<br>";
						return true;
					}
					break;
				default:
					return true;
					break;
			}
			break;// fin Industrial
	}// fin switch c_uni_ca
}// fin validar_pres

function validar_post($exp_e,$tipo){
	global $DSN, $user_db, $pass_db, $ODBCC_conBitacora, $laBitacora,$Error_Validacion;
	$conex = New ODBC_Conn($DSN, $user_db, $pass_db, true, 'pruebas.log');
	$sql = "SELECT codval ";
	$sql.= "FROM sol_solicitudes ";
	$sql.= "WHERE tipo_solicitud='2' and exp_e='".$exp_e."' and tipo_pasantia='".$tipo."' ";
	$conex->ExecSQL($sql,__LINE__,true);

	($tipo == 2) ? $trabajo="Trabajo de Grado" : $trabajo="Practica Profesional";

	$d = ($tipo == 1) ? '23' : '25';

	if ($conex->filas < 1) {
		return false;
	}else{
		
		$codval = $conex->result[0][0];

		$id = substr($codval,6);
		$lapso = "20".substr($codval,3,2)."-".substr($codval,5,1);

		$Error_Validacion.="Ya existe una solicitud de postulación para ".$trabajo." para este estudiante.<br><br>";
		if (!empty($codval)) {
			$Error_Validacion.="<a href=\"../../../solicitudes/reportes/report.php?id=".$id."&lapso=".$lapso."&prog=0&d=".$d."\">Presione AQUI para ver su carta.</a>";
		}
		return true;
	}

}// fin validar_post

?>

