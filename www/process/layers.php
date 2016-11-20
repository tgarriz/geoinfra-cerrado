<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once '../../classes/Layer.php';

$layers = new Layer('../../config/layer.xml');
echo $layers->get();

?>
