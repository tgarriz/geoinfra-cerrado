<?php

Class Config {

    static function get($role) {
        $params = array(
            'consulta' => array(
                'dbname' => "geoinfra",
                'host' => "192.168.0.11",
                'user' => "david",
                'pass' => "dav123",
                'port' => "5432"
            ),
			'consulta2' => array(
                'dbname' => "geobasig",
                'host' => "192.168.0.11",
                'user' => "consulta",
                'pass' => "consulta",
                'port' => "5432"
            )
        );
        return $params[$role];
    }

}

?>
