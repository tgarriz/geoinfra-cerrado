<?php

require_once '../../classes/Search.php';

$error = 0;
$searchs = new Search('../../config/search.xml');
$obj = $searchs->get();

echo "{\"error\": $error, \"search\": $obj}";

?>
