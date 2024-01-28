<?php
//print_r($_GET);
# Aumento de memoria para evitar errores al abrir el recibo
ini_set('memory_limit','64M');
require_once('html2pdf/_tcpdf/config/lang/eng.php');
require_once('html2pdf/_tcpdf/tcpdf.php');
require_once('MYPDF.php');
require_once('MYPDF2.php');
require_once('../inc/odbcss_c.php');
require_once('../inc/config.php');

function lapsoPost($mes){
	switch ($mes){
		case 01: return "ENERO";break;
		case 02: return "FEBRERO";break;
		case 03: return "MARZO";break;
		case 04: return "ABRIL";break;
		case 05: return "MAYO";break;
		case 06: return "JUNIO";break;
		case 07: return "JULIO";break;
		case 08: return "AGOSTO";break;
		case 09: return "SEPTIEMBRE";break;
		case 10: return "OCTUBRE";break;
		case 11: return "NOVIEMBRE";break;
		case 12: return "DICIEMBRE";break;
	}
}
$conex = new ODBC_Conn($ODBC_sistema,$user,$pass,true,$bitacora);

if (isset($_GET['id'])){
	$id = $_GET['id'];
	$lapsos = $_GET['lapso'];
	$prog = $_GET['prog'];
	$id_sol = $_GET['id_sol'];
	
	$mSQL = "SELECT documento,programa,lapso,exp_e FROM solicitudes ";
	$mSQL.= "WHERE id_solicitud='".$id."' AND lapso = '".$lapsos."' AND programa = '".$prog."'";
	//echo "$mSQL<br>";
	$conex->ExecSQL($mSQL,__LINE__,true);
	$filas_busq = $conex->filas;
	if($filas_busq == 0){
		echo utf8_encode("<script language='javascript'> alert('El codigo ingresado es incorrecto.'); window.close(); </script>");
	}
	else{
		
		$cod	= $conex->result[0][0];//Codigo del documento
		$i		= $conex->result[0][1];//programa
		switch ($i){
			case 0: case 2: case 3: case 4: 
				$lap	= substr($conex->result[0][2],2,2).substr($conex->result[0][2],5);
				break;
			case 1:
				$lap	= substr($conex->result[0][2],0,2).substr($conex->result[0][2],4);
		}
		
	}

	$exp_e	= $conex->result[0][3];
	//echo "<br>$exp_e<br>";
}else if (isset($_GET['cod'])){
	$cod = $_GET['cod'];
	$i = $_GET['i'];
	$exp_e = $_GET['exp_e'];
	$id = $_GET['ide'];
	$estatus = "2";
	$lapsos = $_GET['lapso'];
	$lap = $lapso[$i];
	switch ($i){
		case 0: case 2: case 3: case 4: 
			$lap = substr($lap,2,2).substr($lap,5);
			break;
		case 1:
			$lap = substr($lap,0,2).substr($lap,4);
	}
}
$conex2 = new ODBC_Conn($ODBC_academia[$i],$user,$pass,true,$bitacora);
$SQL = "SELECT estatus_e FROM dace002 WHERE exp_e='".$exp_e."' ";
//echo "<br>$SQL<br>"; die();
$conex2->ExecSQL($SQL,__LINE__,true);

$estatus = $conex2->result[0][0];

switch ($i){
	case 0: // PREGRADO
		if($estatus == '1' || $estatus == '0'){
			$pers = "ci_e,apellidos||' '||apellidos2,nombres||' '||nombres2";
			$tabla = "dace002";
		}else{
			$pers = "ci_e,apellidos,nombres";
			$tabla = "dace002_grad";
		}
		$SQL = "SELECT ".$pers." ,carrera,a.c_uni_ca,sexo,nac_e,pensum ";
		$SQL.= "FROM  ".$tabla." a, tblaca010 b ";
		$SQL.= "WHERE exp_e='".$exp_e."' AND a.c_uni_ca=b.c_uni_ca ";
		//echo "<br>$SQL<br>";
		$programa = "PREGRADO";
		$rango = "UNO (1) AL NUEVE (9)";
		$minimo = "SEIS (6)";
		$maximo = "nueve (9) puntos y mínima de un (1) punto";
		$minimo_a = "CINCO (5)";
		
		break;
	case 1: // POSTGRADO
		
		
		//$c_uni_ca= substr($exp_e,0,2);
		
		//echo $estatus;

		//if(($estatus == '0') || ($estatus == '1')){
		if($estatus == '1' || $estatus == '0'){
			$pers = "ci_e,apellidos||' '||apellidos2,nombres||' '||nombres2";
			$tabla = "dace002";
		}else{
			$pers = "ci_e,apellidos,nombres";
			$tabla = "dace002_grad";
		}

		//echo "e".$estatus;

		$cSQL = "SELECT c_uni_ca FROM ".$tabla." WHERE exp_e='".$exp_e."' ";
		$conex2->ExecSQL($cSQL);
		$c_uni_ca = $conex2->result[0][0];

		$SQL = "SELECT ".$pers.",carrera,a.c_uni_ca,sexo,nac_e,pensum ";
		$SQL.= "FROM  ".$tabla." a, tblaca010 b ";
		$SQL.= "WHERE exp_e='".$exp_e."' AND a.c_uni_ca='".$c_uni_ca."' ";
		$SQL.= "AND a.c_uni_ca=b.c_uni_ca ";
		$programa = "POSTGRADO";
		$rango = "UNO (1) a VEINTE (20)";
		$minimo = "CATORCE (14)";
		$maximo = "veinte (20) puntos y mínima de un (1) punto";
		$minimo_a = "DIEZ (10)";
		//echo $SQL;
		break;
	case 2: // ARTICULACION
		//echo "estatus: ".$estatus;
		//if($estatus == '1' || $estatus == '0'){
		if($estatus == '1' || $estatus == '0'){
			$tabla = "dace002";
		}else{
			$tabla = "dace002_grad";
		}

		$SQL = "SELECT ci_e,apellidos||' '||apellidos2,nombres||' '||nombres2,carrera,a.c_uni_ca,sexo,nac_e,pensum ";
		$SQL.= "FROM  ".$tabla." a, tblaca010 b ";
		$SQL.= "WHERE exp_e='".$exp_e."' AND a.c_uni_ca=b.c_uni_ca ";
//		echo $SQL;
		$programa = "ART. Y PROSEC.";
		$rango = "UNO (1) a NUEVE (9)";
		$minimo = "SEIS (6)";
		$maximo = "nueve (9) puntos y mínima de un (1) punto";
		$minimo_a = "CINCO (5)";
		break;
	case 3: // TSU
		if($estatus == '1' || $estatus == '0'){
			$tabla = "dace002";
		}else{
			$tabla = "dace002_egre";
		}

		$SQL = "SELECT ci_e,apellidos,nombres,carrera2,a.c_uni_ca,sexo,nac_e,pensum ";
		$SQL.= "FROM  ".$tabla." a, tblaca010 b ";
		$SQL.= "WHERE exp_e='".$exp_e."' AND a.c_uni_ca=b.c_uni_ca ";
		$programa = "TEC. SUP. UNIVERSITARIO";
		$rango = "UNO (1) a NUEVE (9)";
		$minimo = "SEIS (6)";
		$maximo = "nueve (9) puntos y mínima de un (1) punto";
		$minimo_a = "CINCO (5)";
		break;
	case 4: // DIPLOMADO
		if($estatus == '1' || $estatus == '0'){
			$tabla = "dace002";
		}else{
			$tabla = "dace002_grad";
		}

		$SQL = "SELECT ci_e,apellidos,nombres,carrera,a.c_uni_ca,sexo,nac_e,pensum ";
		$SQL.= "FROM  ".$tabla." a, tblaca010 b ";
		$SQL.= "WHERE exp_e='".$exp_e."' AND a.c_uni_ca=b.c_uni_ca ";
		$programa = "DIPLOMADO";
		$rango = "UNO (1) a NUEVE (9)";
		$minimo = "SEIS (6)";
		$maximo = "nueve (9) puntos y mínima de un (1) punto";
		$minimo_a = "CINCO (5)";
		break;
}//end switch

//$conex2 = new ODBC_Conn($ODBC_academia[$i],$user,$pass);
//echo "$SQL<br>";
$conex2->ExecSQL($SQL);

$ci_e			= $conex2->result[0][0];
$apellidos		= $conex2->result[0][1];
$nombres		= $conex2->result[0][2];

($i == 3) ? $carrera = "TECNICO SUPERIOR EN ".$conex2->result[0][3]:$carrera = $conex2->result[0][3];

$c_uni_ca		= $conex2->result[0][4];
$sexo			= $conex2->result[0][5];
$nac			= $conex2->result[0][6];
$pensum			= $conex2->result[0][7];
//echo "$ci_e<br>$apellidos<br>$nombres<br>$carrera<br>$c_uni_ca<br>$sexo<br>$nac<br>";

switch ($i){
	case 0: // PREGRADO
		switch ($c_uni_ca){
			case '2':
				$especialidad = "MECÁNICO";
				break;
			case '3':
				$especialidad = "ELECTRICISTA";
				break;
			case '4':
				$especialidad = "METALÚRGICO";
				break;
			case '5':
				$especialidad = "ELETRÓNICO";
				break;
			case '6':
				$especialidad = "INDUSTRIAL";
				break;			
		}
		$titulo = "INGENIERO ".$especialidad;
		break;
	case 1: // POSTGRADO
		$cSQL = "SELECT carrera2 FROM tblaca010 WHERE c_uni_ca='".$c_uni_ca."' ";
		$conex2->ExecSQL($cSQL,__LINE__,true);
		$titulo = $conex2->result[0][0];
		break;
	case 2: // ARTICULACION
		switch ($c_uni_ca){
			case '2':
				$especialidad = "MECÁNICO";
				break;
			case '3':
				$especialidad = "ELECTRICISTA";
				break;
			case '4':
				$especialidad = "METALÚRGICO";
				break;
			case '5':
				$especialidad = "ELETRÓNICO";
				break;
			case '6':
				$especialidad = "INDUSTRIAL";
				break;			
		}
		$titulo = "INGENIERO ".$especialidad;
		break;
	case 3: // TSU
		$cSQL = "SELECT carrera2 FROM tblaca010 WHERE c_uni_ca='".$c_uni_ca."' ";
		$conex2->ExecSQL($cSQL,__LINE__,true);
		$titulo = "TECNICO SUPERIOR EN ".$conex2->result[0][0];
		//$titulo = "INGENIERO ".
		break;
	case 4: // DIPLOMADO
		$cSQL = "SELECT carrera2 FROM tblaca010 WHERE c_uni_ca='".$c_uni_ca."' ";
		$conex2->ExecSQL($cSQL,__LINE__,true);
		$titulo = $conex2->result[0][0];
		break;
}//end switch


if ($i == 1){
	$semestre = "DE ".$programa;
	$lapsoA = lapsoPost(substr($lapso[$i],0,2))." - ".lapsoPost(substr($lapso[$i],4,2))." 20".substr($lapso[$i],-2);
}else{
	$lapsoA = $lapso[$i];
	$semSQL = "SELECT semestre FROM dace002 WHERE exp_e='".$exp_e."' AND c_uni_ca='".$c_uni_ca."' ";
	$conex2->ExecSQL($semSQL,__LINE__,true);
	
	switch($conex2->result[0][0]){
		case 1:
			$semestre = "DEL PRIMER SEMESTRE";
			$nro_romano = "I";//para carta presentación y postulación_2012
			break;
		case 2:
			$semestre = "DEL SEGUNDO SEMESTRE";
			$nro_romano = "II";
			break;
		case 3:
			$semestre = "DEL TERCER SEMESTRE";
			$nro_romano = "III";
			break;
		case 4:
			$semestre = "DEL CUARTO SEMESTRE";
			$nro_romano = "IV";
			break;
		case 5:
			$semestre = "DEL QUINTO SEMESTRE";
			$nro_romano = "V";
			break;
		case 6:
			$semestre = "DEL SEXTO SEMESTRE";
			$nro_romano = "VI";
			break;
		case 7:
			$semestre = "DEL SEPTIMO SEMESTRE";
			$nro_romano = "VII";
			break;
		case 8:
			$semestre = "DEL OCTAVO SEMESTRE";
			$nro_romano = "VIII";
			break;
		case 9:
			$semestre = "DEL NOVENO SEMESTRE";
			$nro_romano = "IX";
			break;
		case 10:
			$semestre = "DEL DECIMO SEMESTRE";
			$nro_romano = "X";
			break;
	}
}

switch($sexo){
	case 0:// Femenino
		$pre = "LA CIUDADANA";
		(!empty($estatus)) ? $prec = "la ciudadana" : $prec = "a la ciudadana";		
		$mencionado = "la mencionada";
		$citado = "la citada";
		$senal = "la ingeniero arriba señalada";
		$menc_rec = "LA MENCIONADA CIUDADANA";
		$al_la = "a la";//para la carta de presntación y postulación
		break;
	case 1:// Masculino
		$pre = "EL CIUDADANO";
		(!empty($estatus)) ? $prec = "el ciudadano" : $prec = "al ciudadano";		
		$mencionado = "el mencionado";
		$citado = "el citado";
		$senal = "el ingeniero arriba señalado";
		$menc_rec = "EL MENCIONADO CIUDADANO";
		$al_la = "al";//para la carta de presntación y postulación
		break;
	default:
		$pre = "EL(LA) CIUDADANO(A)";
		(!empty($estatus)) ? $prec = "el(la) ciudadano(a)" : $prec = "al(a la) ciudadano(a)";
		$mencionado = "el(la) mencionado(a)";
		$citado = "el(la) citado(a)";
		$senal = "el(la) ingeniero arriba señalado(a)";
		$menc_rec = "EL(LA) MENCIONADO(A) CIUDADANO(A)";
		$al_la = "al (la)";//para la carta de presntación y postulación
		break;
}

//$conex2 = new ODBC_Conn($ODBC_academia[0],$user,$pass);
$nSQL = "SELECT f_descrp_d, f_descrp_m, f_descrp_a ";
$nSQL.= "FROM fecha1, fecha2, fecha3 ";
$nSQL.= "WHERE fecha_num = '".date('d')."' AND fecha_num1 = '".date('m')."' AND fecha_num2 = '".date('Y')."'";
$conex2->ExecSQL($nSQL,__LINE__,true);
$dia = $conex2->result[0][0];
$mes = $conex2->result[0][1];
$anio = $conex2->result[0][2];


(date('d') == '01') ? $dias = "día" : $dias = "días";
$emision = $dia." ".$dias." del mes de ".$mes." de ".$anio;
//echo $emision;
//echo $cod;
//echo "$cod.$i.$lap.$id<br>";
switch($cod){// Segun el tipo de documeto
	case 01:// RECORD

		/// Seleccionar tipo de reporte con encabezado o sin encabezado.
		if (isset($_GET['sm'])){
			$pdf = new MYPDF2('P', PDF_UNIT, 'LETTER', true, 'UTF-8', false);
		}else{
			$pdf = new MYPDF('P', PDF_UNIT, 'LETTER', true, 'UTF-8', false);
		}

		
		$pdf->SetCreator('Universidad Nacional Experimental Politecnica "ANTONIO JOSE DE SUCRE"');
		$pdf->SetAuthor('Universidad Nacional Experimental Politecnica "ANTONIO JOSE DE SUCRE"');
		$pdf->SetKeywords('Universidad Nacional Experimental Politecnica "ANTONIO JOSE DE SUCRE"');
		$pdf->SetProtection($permissions=array('print'), $user_pass='', $owner_pass=null, $mode=0, $pubkeys=null);

		$y = 255;

		$pdf->AddPage();
		$titulo_rep = "INFORME DE RENDIMIENTO ACADEMICO";
		$codval = $cod.$i.$lap.$id;
		
		//include('barcode2.php');		
		include('record.php');
		break;
	case 02: case 03: case 19: case 20:
		$pdf = new MYPDF('P', PDF_UNIT, 'LETTER', true, 'UTF-8', false);
		$pdf->SetCreator('Universidad Nacional Experimental Politecnica "ANTONIO JOSE DE SUCRE"');
		$pdf->SetAuthor('Universidad Nacional Experimental Politecnica "ANTONIO JOSE DE SUCRE"');
		$pdf->SetKeywords('Universidad Nacional Experimental Politecnica "ANTONIO JOSE DE SUCRE"');
		$pdf->SetProtection($permissions=array('print'), $user_pass='', $owner_pass=null, $mode=0, $pubkeys=null);

		$y = 255;

		$pdf->AddPage();
		$titulo_rep = "CONSTANCIA";
		$codval = $cod.$i.$lap.$id;
		include('barcode2.php');
		include('constancia.php');
		break;
	case 15: case 18: 
		$pdf = new MYPDF('P', PDF_UNIT, 'LETTER', true, 'UTF-8', false);
		$pdf->SetCreator('Universidad Nacional Experimental Politecnica "ANTONIO JOSE DE SUCRE"');
		$pdf->SetAuthor('Universidad Nacional Experimental Politecnica "ANTONIO JOSE DE SUCRE"');
		$pdf->SetKeywords('Universidad Nacional Experimental Politecnica "ANTONIO JOSE DE SUCRE"');
		$pdf->SetProtection($permissions=array('print'), $user_pass='', $owner_pass=null, $mode=0, $pubkeys=null);

		$y = 255;

		$pdf->AddPage();
		$titulo_rep = "INFORME DE RENDIMIENTO ACADEMICO";
		$codval = $cod.$i.$lap.$id;
		//echo $codval;
		//include('barcode2.php');		
		include('record_sign.php');
	break;
	///////////////////////////////////////////
	case 21:
		$pdf = new MYPDF('P', PDF_UNIT, 'LETTER', true, 'UTF-8', false);
		$pdf->SetCreator('Universidad Nacional Experimental Politecnica "ANTONIO JOSE DE SUCRE"');
		$pdf->SetAuthor('Universidad Nacional Experimental Politecnica "ANTONIO JOSE DE SUCRE"');
		$pdf->SetKeywords('Universidad Nacional Experimental Politecnica "ANTONIO JOSE DE SUCRE"');
		$pdf->SetProtection($permissions=array('print'), $user_pass='', $owner_pass=null, $mode=0, $pubkeys=null);

		$y = 255;

		$pdf->AddPage();
		$titulo_rep = "CONSTANCIA";
		$codval = $cod.$i.$lap.$id;
		//include('barcode2.php');
		include('c_culminacion.php');
		break;

	case 22: case 23: case 24: case 25://carta de presentación para practica profesional
		$pdf = new MYPDF('P', PDF_UNIT, 'LETTER', true, 'UTF-8', false);
		$pdf->SetCreator('Universidad Nacional Experimental Politecnica "ANTONIO JOSE DE SUCRE"');
		$pdf->SetAuthor('Universidad Nacional Experimental Politecnica "ANTONIO JOSE DE SUCRE"');
		$pdf->SetKeywords('Universidad Nacional Experimental Politecnica "ANTONIO JOSE DE SUCRE"');
		$pdf->SetProtection($permissions=array('print'), $user_pass='', $owner_pass=null, $mode=0, $pubkeys=null);
		/*
		$carta = 0;
		$carta2 = date("Y");
		$sqlCARTA = "SELECT MAX(CARTA)+1 FROM SOL_SOLICITUDES WHERE CARTA2 = '".$carta2."'";
		$conex2->ExecSQL($sqlCARTA,__LINE__,true);
		($conex2->result[0][0]) ? $carta = $conex2->result[0][0] : $carta = $carta++;

		/*
		//conecto a dacepoz
		//$conex2 = new ODBC_Conn($ODBC_academia[$i],$user,$pass,true,$bitacora);
		$insertar = "carta";
		$valores_s = "'".$max_id_solicitud."','".$F_Exp_E."',0,'".$documento."', ";
		$valores_s.= "'".$lapsoProceso."','".$fecha_solicitud."','".$estatus."','".$id_codigo."'";

		$sSQL = "INSERT INTO solicitudes (".$insertar.")";
		$sSQL.= " VALUES ";
		$sSQL.= "(".$valores_s.") ";
	
		$conex_solicita->ExecSQL($sSQL,__LINE__,true);
		*/

		$y = 255;

		$pdf->AddPage();
		$titulo_rep = "";
		$codval = $cod.$i.$lap.$id;
		include('carta_pres_post.php');
		break;
/*
	case 23://carta de postulación para practica profesional
		$pdf = new MYPDF('P', PDF_UNIT, 'LETTER', true, 'UTF-8', false);
		$pdf->SetCreator('Universidad Nacional Experimental Politecnica "ANTONIO JOSE DE SUCRE"');
		$pdf->SetAuthor('Universidad Nacional Experimental Politecnica "ANTONIO JOSE DE SUCRE"');
		$pdf->SetKeywords('Universidad Nacional Experimental Politecnica "ANTONIO JOSE DE SUCRE"');
		$pdf->SetProtection($permissions=array('print'), $user_pass='', $owner_pass=null, $mode=0, $pubkeys=null);

		$y = 255;

		$pdf->AddPage();
		$titulo_rep = "";
		$codval = $cod.$i.$lap.$id;
		//include('barcode2.php');
		include('carta_pres_post.php');
		break;

	case 24://carta de presentación para trabajo de grado
		$pdf = new MYPDF('P', PDF_UNIT, 'LETTER', true, 'UTF-8', false);
		$pdf->SetCreator('Universidad Nacional Experimental Politecnica "ANTONIO JOSE DE SUCRE"');
		$pdf->SetAuthor('Universidad Nacional Experimental Politecnica "ANTONIO JOSE DE SUCRE"');
		$pdf->SetKeywords('Universidad Nacional Experimental Politecnica "ANTONIO JOSE DE SUCRE"');
		$pdf->SetProtection($permissions=array('print'), $user_pass='', $owner_pass=null, $mode=0, $pubkeys=null);

		$y = 255;

		$pdf->AddPage();
		$titulo_rep = "";
		$codval = $cod.$i.$lap.$id;
		//include('barcode2.php');
		include('carta_pres_post.php');
		break;

	case 25://carta de postulación para trabajo de grado
		$pdf = new MYPDF('P', PDF_UNIT, 'LETTER', true, 'UTF-8', false);
		$pdf->SetCreator('Universidad Nacional Experimental Politecnica "ANTONIO JOSE DE SUCRE"');
		$pdf->SetAuthor('Universidad Nacional Experimental Politecnica "ANTONIO JOSE DE SUCRE"');
		$pdf->SetKeywords('Universidad Nacional Experimental Politecnica "ANTONIO JOSE DE SUCRE"');
		$pdf->SetProtection($permissions=array('print'), $user_pass='', $owner_pass=null, $mode=0, $pubkeys=null);

		$y = 255;

		$pdf->AddPage();
		$titulo_rep = "";
		$codval = $cod.$i.$lap.$id;
		//include('barcode2.php');
		include('carta_pres_post.php');
		break;
*/
	///////////////////////////////////////////
	default:
		$pdf = new MYPDF2('P', PDF_UNIT, 'LETTER', true, 'UTF-8', false);
		$pdf->SetCreator('Universidad Nacional Experimental Politecnica "ANTONIO JOSE DE SUCRE"');
		$pdf->SetAuthor('Universidad Nacional Experimental Politecnica "ANTONIO JOSE DE SUCRE"');
		$pdf->SetKeywords('Universidad Nacional Experimental Politecnica "ANTONIO JOSE DE SUCRE"');
		$pdf->SetProtection($permissions=array('print'), $user_pass='', $owner_pass=null, $mode=0, $pubkeys=null);

		$y = 255;

		$pdf->AddPage();
		$titulo_rep = utf8_encode("CERTIFICACIÓN");
		include('certificacion.php');
		$codval = null;
}

/*$pdf->SetX('130');
$pdf->SetY($y-103);

$html = "<IMG SRC=\"barcode.php?barcode=$codval&width=350&height=25&text=0\" align=\"center\">";

$html = "te<br>st";

$pdf->writeHTML($html, true, false, true, false, '');*/

$pdf->SetFont('courier', '', 8);
//$pdf->Cellxy(0, $y-1, 207, 3, $codval,0,0,'R');
//$pdf->Image('barcode.php?barcode='.$codval.'&width=350&height=25&text=0', 20, 190, 120/4, 114/4);

$image_file = "barcode.php?barcode=".$codval;


//$pdf->Image($image_file, 10, 10, 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);

/*
$footer_y =  $pdf->footer_margin + 265;

switch ($codval){
	case null:
		$pdf->SetFont('helvetica', '', 7);
		$pdf->Cellxy(10,$footer_y, 200, 3, utf8_encode("CUALQUIER ENMIENDA ANULA EL PRESENTE DOCUMENTO"),0,0,'L');
		break;
	default:
		$pdf->SetFont('helvetica', 'B', 7);
		$pdf->Cellxy(10, $footer_y, 200, 3, "Nota:",0,0,'L');
		$pdf->SetFont('helvetica', '', 7);
		$pdf->Cellxy(17, $footer_y, 200, 3, utf8_encode("Este documento no es válido sin sello y  firma de la  ".$nombreDependencia." de la UNEXPO Vicerrectorado ".$vicerrectorado),0,0,'L');
		break;
}
*/
$pdf->Output("reporte.pdf", 'I');
?>