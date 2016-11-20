<?php

define("PROCESS", "");

include_once '../../include/functions.php';
require_once '../../classes/Connection.php';
require_once '../../classes/HistoryPlanes.php';

$x = (float)_isset($_POST, "lon", 0);

$y = (float)_isset($_POST, "lat", 0);
$db = new Connection('consulta2');

$planes = new HistoryPlanes($db->dbh, $x, $y);

$result = $planes->json;

$error = 0;
if($planes->error){
	$error = 1;
	$result = "\"".$planes->error."\"";
}
echo "{\"error\":$error, \"result\": $result}";

?>
