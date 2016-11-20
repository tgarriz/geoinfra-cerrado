<!doctype html>
<html lang="es">
<head>
<meta charset="ISO-8859-1">
<meta name="viewport" content="width=device-width; initial-scale=1.0"> 
<title>Planos</title>
    <link rel="shortcut icon" type="image/png" href="./app/img/favicon.png" />
    <link href="css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
<?php
require_once('nusoap-0.9.5/lib/nusoap.php');
$id = $POST_['id'];
$client = new nusoap_client('http://www.mosp.gba.gov.ar/sistemas/webservices/sigos/server_sws.php?wsdl', 'wsdl');
$err = $client->getError();
if ($err) {
        echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
}
else {
        $login = md5('geodesia');
        $pass = md5('geo123');

        $result = $client->call('Obrafim_GetDatos', array('inp01' => $login,'inp02' => $pass));
        if ($client->fault) {
                echo '<h2>Fault</h2><pre>'; print_r($result); echo '</pre>';
        } else {
                $err = $client->getError();
                if ($err) {
                        echo '<h2>Error</h2><pre>' . $err . '</pre>';
                } else {
                        echo '<div class="container">';
                        echo '<div class="page-header">';
                        echo '<h5 align="center">Obra: ' . $result[$id]['Obra'] .'</h5>';
                        echo '<h5 align="left">Municipio: ' . $result[$id]['Municipio'] .'</h5>';
                        echo '<h5 align="left">Fecha Licitacion: ' . $result[$id]['Fecha_licitacion'] .'</h5>';
                        echo '<h5 align="left">Observaciones Licitacion: ' . $result[$id]['Obs_fecha_licitacion'] .'</h5>';
                        echo '<h5 align="left">Fecha Inicio: ' . $result[$id]['Fecha_inicio_obra'] .'</h5>';
                        echo '<h5 align="left">Obs. Fecha Inicio: ' . $result[$id]['Obs_fecha_inicio_obra'] .'</h5>';
                        echo '<h5 align="left">Fecha Estimativa de finalizacion: ' . $result[$id]['Fecha_est_finaliz'] .'</h5>';
                        echo '<h5 align="left">Barrio: ' . $result[$id]['Barrio'] .'</h5>';
                        echo '<h5 align="left">Direccion: ' . $result[$id]['Direccion'] .'</h5>';
                        echo '<h5 align="left">Entre: ' . $result[$id]['Entre_calles'] .'</h5>';
                        echo '<h5 align="left">Longitud: ' . $result[$id]['Longitud'] .'</h5>';
                        echo '<h5 align="left">Superficie: ' . $result[$id]['Superficie'] .'</h5>';
                        echo '<h5 align="left">Poblacion del Municipio: ' . $result[$id]['Poblacion_municipio'] .'</h5>';
                        echo '<h5 align="left">Poblacion beneficiada: ' . $result[$id]['Poblacion_benef_obra'] .'</h5>';
                        echo '<h5 align="left">Generacion de puestos laborales: ' . $result[$id]['Gen_puestos_trab_obra'] .'</h5>';
                        echo '<h5 align="left">Archivos: ' . $result[$id]['cant_ofi_arch'] .'</h5>';
                        echo '</div>';
                        echo '<div class="list-group">';
?>

<!-- Js vinculados -->
    <script src="http://code.jquery.com/jquery-latest.min.js"></script>
    <script src="js/responsive.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>
</html>


