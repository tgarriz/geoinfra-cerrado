<?php

session_start();

include_once '../include/functions.php';
require_once '../classes/Connection.php';
include '../classes/Validate.php';
include '../classes/Login.php';
include '../classes/Access.php';

new Login('consulta');
?>
