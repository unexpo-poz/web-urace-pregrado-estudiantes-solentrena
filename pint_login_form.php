<?php
include_once('inc/vImage.php'); 
include_once('inc/config.php'); 

imprima_enc();
if ($inscHabilitada){
	imprima_form();
}
else {
	print <<<x001
		<div style="font-family:arial; font-size:16px; color:red;text-align:center;">
		Disculpe, el sistema est&aacute; en mantenimiento.<br><br>
		</div><br>
x001
;
}
imprima_final();

function imprima_enc(){
	global $tProceso, $lapsoProceso, $tLapso, $enProduccion;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<?php
	global $noCache;
	print $noCache;
?>
<title><?php echo $tProceso . $lapsoProceso; ?></title>

<script languaje="Javascript">
<!--
	/*if ((navigator.appName == "Microsoft Internet Explorer" )){
		alert("Disculpe, su cliente http no esta soportado en este sistema. Use Mozilla Firefox para acceder al sistema."); 
		location.replace("no-soportado.php");*/	//	return; 
	}
// -->
</script>

  <script language="Javascript" src="md5.js">
   <!--
    alert('Error con el fichero js');
    // -->
  </script>
  <script languaje="Javascript">
<!--

  function validar(f) {
	if ((f.cedula_v.value == "")||(f.contra_v.value == "")) {
		alert("Por favor, escriba su c�dula y clave antes de pulsar el bot�n Entrar");
		return false;
	} 
	else {
		f.contra.value = hex_md5(f.contra_v.value);
		f.contra_v.value = "";
		f.cedula.value = f.cedula_v.value;
		f.cedula_v.value = "";
		f.vImageCodP.value = f.vImageCodC.value;
		f.vImageCodC.value = "";
		window.open("","planillab","left=0,top=0,width=790,height=500,scrollbars=1,resizable=1,status=1");
		<?php if ($enProduccion){ ?>
		setTimeout("location.reload()",90000);
		<?php } ?>
		return true;
	}

}
//-->
  </script>          
<style type="text/css">
<!--
#prueba {
  overflow:hidden;
  color:#00FFFF;
  background:#F7F7F7;
}

.instruc {
  font-family:Arial; 
  font-size: 13px; 
  font-weight: normal;
  background-color: #254B72;
  color: #FFFFFF
}
.normal {
  font-family:Arial; 
  font-size: 14px; 
  font-weight: normal;
  color: #242744;
}
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
.vinculo {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	font-weight:bold;
	color: #FFFF99;
	text-align:right;
	text-decoration: none;
	
}
a.vinculo:hover {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	color: #FFFF66;
	text-decoration: underline;
}

-->
</style>  

</head>


<body <?php global $botonDerecho; echo $botonDerecho; ?>>

<table id="table1" style="border-collapse: collapse;background: url(imagenes/fondo_index.png) no-repeat" border="0" cellpadding="0" cellspacing="1" width="100%" align="center"><tbody>
   
	
	<td width="750" colspan="3">
          <p align="center" style="font-family:arial; font-weight:bold; font-size:20px;color:#FFFFFF;" class="instruc">
<?php			echo $tProceso .' '. $tLapso; 
?>		  </p>
    </td>
  </tr>

  
<?php
}
function imprima_form(){
?>		
  <tr>
      <td width="777" align="center" colspan="3">
      <form method="post" name="chequeo" onSubmit="return validar(this)" action="planilla_r.php" target="planillab">
<table style="border-collapse:collapse;padding-left:350px;" border="0" cellpadding="0" cellspacing="1" width="85%" ><tr><td>
			<br><table style="padding-left:280px;"class="normal">
				<tr>
					<td>
						C&eacute;dula:
					</td>
					<td>
						<input name="cedula_v" size="15" tabindex="1" type="text">
					</td>
				<tr>
				<tr>
					<td>
						Clave:
					</td>
					<td>
						<input name="contra_v" size="15" tabindex="2" type="password">
					</td>
				<tr>
			</table>
<br>
			<table style="padding-left:280px;" class="normal" width="100%" border="0">
				<tr>
					<td width="100%">
						C&oacute;digo de Seguridad:&nbsp;&nbsp;<img src="inc/img.php?size=4" height="30px" style="vertical-align: middle;">&nbsp;&nbsp;<input name="vImageCodC" size="5" tabindex="3" type="text"> 
					</td>
				<tr>
				<tr>
					<td>
						<input value="Entrar" name="b_enviar" tabindex="3" type="submit">
					</td>
				</tr>
			</table>
<br>
</td></tr></table>
  
			   
			  <input value="x" name="cedula" type="hidden"> 
			  <input value="x" name="contra" type="hidden">
			  <input value="" name="vImageCodP" type="hidden"> 
  </form>

<?php //imprima_form
}

function imprima_final(){
?>
	  </td>
    </tr>
	
    <tr>
		<td class ="instruc" style="padding-left:10px;padding-top:10px;" width="50%">
			<b>NOTAS:</b>
			<ul style="padding-right:25px;text-align:justify;">
				<li>Si no posee la clave o la olvid&oacute;, puedes solicitarla en la Unidad Regional de Admisi&oacute;n y Control Estudios -URACE- (antes DACE) en horario de oficina. Requisito indispensable: C&eacute;dula de identidad ORIGINAL o carnet ORIGINAL. No se aceptan fotocopias.</li>
			</ul>
		</td>
	   <td class ="instruc" style="padding-left:10px;padding-top:10px;">
			<b>ANEXOS:</b>
			<ul style="padding-right:25px;text-align:justify;">
				<li><a href="/web/descargas/pdf/lineamientos_informe.pdf" target="_blank" class="vinculo">Lineamientos para la realizaci�n
del Informe T&eacute;cnico e Informe Acad&eacute;mico en la UNEXPO (PP/TG/PPG)</a>.</li>
				<li><a href="/web/descargas/pdf/planilla_fundei.pdf" target="_blank" class="vinculo">Planilla de Registro del Pasante FUNDEI</a>.</li>
			</ul>
		</td>
  </td></tr></tbody></table>
</body>
<?php
//Evitar que la pagina se guarde en cache del cliente
global $noCacheFin;
print $noCacheFin;
?>
</html>
<?php
} //imprima_final	 
?>
