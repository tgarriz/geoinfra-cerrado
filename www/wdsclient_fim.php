<!doctype html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width; initial-scale=1.0"> 
<title>Auditorias</title>
    <link rel="shortcut icon" type="image/png" href="./app/img/favicon.png" />
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
<style>
hr { 
    display: block;
    margin-top: 0.5em;
    margin-bottom: 0.5em;
    margin-left: auto;
    margin-right: auto;
    border-style: inset;
    border-width: 3px;
} 
</style>
</head>

<body>


<?php
  session_name('gis');
  session_start();
  require_once('../nusoap-0.9.5/lib/nusoap.php');
  //parse_str(implode('&', array_slice($argv, 1)), $_GET); 
  $host='192.168.0.11';
  $port='5432';
  $user='consulta';
  $clave='consulta';
  $dbname='obras2016';

//if ( $_SESSION['usuarioRegstrado'] == true ){
if (true){
	$client = new nusoap_client('http://www.mosp.gba.gov.ar/sistemas/webservices/sigos/server_sws.php?wsdl', 'wsdl');
	$err = $client->getError();
	if ($err) {
        	echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
	}
	else {
        	$login = md5('geodesia');
        	$pass = md5('geo123');

	        $result = $client->call('Obrafim_GetDatosID', array('inp01' => $login,'inp02' => $pass,'id' => $_GET["ido"]));
        	if ($client->fault) {
                	echo '<h2>Fault</h2><pre>'; print_r($result); echo '</pre>';
        	} else {
                	$err = $client->getError();
                	if ($err) {
                	        echo '<h2>Error</h2><pre>' . $err . '</pre>';
                	} else {
               		        echo '<div class="container">';
                	        echo '<div class="page-header">';
                        	echo '<h4 align="center"><b>' . $result[0]['Obra'] .'</b></h4>';
	                        echo '<h5 align="left"><b>Avance Financiero:</b> ' . $result[0]['avFciero'] .'</h5>';
        	                echo '<h5 align="left"><b>Certificacion Acumulada:</b> ' . $result[0]['CertAcum'] .'</h5>';
                	        echo '<h5 align="left"><b>Municipio:</b> ' . $result[0]['Municipio'] .'</h5>';
                        	echo '<h5 align="left"><b>Fecha Licitacion:</b> ' . $result[0]['Fecha_licitacion'] .'</h5>';
	                        echo '<h5 align="left"><b>Observaciones Licitacion:</b> ' . $result[0]['Obs_fecha_licitacion'] .'</h5>';
	                        echo '<h5 align="left"><b>Barrio:</b> ' . $result[0]['Barrio'] .'</h5>';
        	                echo '<h5 align="left"><b>Direccion:</b> ' . $result[0]['Direccion'] .'</h5>';
                	        echo '<h5 align="left"><b>Entre:</b> ' . $result[0]['Entre_calles'] .'</h5>';
        	                echo '</div>';
                	        echo '<div class="list-group">';
				
				$connect = pg_connect("host=$host dbname=$dbname user=$user password=$clave") or die('No se ha podido conectar a la BD: ' . pg_last_error());
				$pr = $result[0]['ID_PROYECTO'];
				$cons_auditorias = "select * from auditorias where id_proyecto = '".$pr."';";
				$res_auditorias = pg_query($connect,$cons_auditorias) or die('La consulta fallo: ' . pg_last_error());
				if( $res_auditorias ) {
        			   $aud=1;
				   while ($row_a = pg_fetch_row($res_auditorias)) {
	                		echo '<h4 align="left">Auditoria: '.$aud.'</h4>';
					echo '<dl class="dl-horizontal">';
			                echo '<dt>Avance Fisico: </dt><dd>'.$row_a[14].'%</dd>';
                			echo '<dt>Universidad: </dt><dd>'.$row_a[5].'</dd>';
		        	        echo '<dt>Fecha de Auditoria: </dt><dd>'.$row_a[10].'</dd>';
                			echo '<dt>Obs. del Auditor: </dt><dd>'.$row_a[44].'</dd>';
			                echo '</dl>';
					echo '</br>';
			                $aud++;
				   }
     				}
				$consulta_img = "select imagen from auditoria_img where id_proyecto = '".$pr."';";
				$res_img = pg_query($connect,$consulta_img) or die('La consulta fallo: ' . pg_last_error());
				if( $res_img ) {
      				  while ($row_i = pg_fetch_row($res_img)) {
			                echo '<div align="center"><p><img src="https://geoinfra.geobasig.com.ar/images/auditorias/'.$pr.'/auditoria/'.$row_i[0].'" height="400px" width="650px"></p></div>';
				  }
     				}
                                echo '<img src="http://abierto.geobasig.com.ar/theme/barra_inferior.svg" width="100%"/>';
			}	
                }
        }
}
	
    pg_free_result($res_auditorias);
    pg_free_result($res_img);
    pg_close($connect);

?>
    <script src="https://code.jquery.com/jquery-latest.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/respond.js/1.4.2/respond.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    </body>

</html>
