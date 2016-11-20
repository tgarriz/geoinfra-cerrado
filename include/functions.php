<?php

function _isset($arr, $path, $default = NULL) {
    if (!is_array($arr))
        return $default;

    $cursor = $arr;
    $keys = explode('.', $path);

    foreach ($keys as $key) {
        if (isset($cursor[$key])) {
            $cursor = $cursor[$key];
        } else {
            return $default;
        }
    }
    return $cursor;
}

function ip() {
    $ip = null;
    $proxy = null;
    
    $HTTP_X_FORWARDED_FOR = _isset($_SERVER, "HTTP_X_FORWARDED_FOR");
    $HTTP_CLIENT_IP = _isset($_SERVER, "HTTP_CLIENT_IP");
    $REMOTE_ADDR = _isset($_SERVER, "REMOTE_ADDR");

    if ($HTTP_X_FORWARDED_FOR) {
        if ($HTTP_CLIENT_IP) {
            $proxy = $HTTP_CLIENT_IP;
            if ($REMOTE_ADDR)
                $proxy = $proxy . "," . $REMOTE_ADDR;
        } else {
            $proxy = $REMOTE_ADDR;
        }
        $ip = $HTTP_X_FORWARDED_FOR;
    } else {
        if ($HTTP_CLIENT_IP) {
            $ip = $HTTP_CLIENT_IP;
            if ($REMOTE_ADDR)
                $ip = $ip1 . "," . $REMOTE_ADDR;
        } else {
            $ip = $REMOTE_ADDR;
        }
    }
    return array('ip'=> $ip, 'proxy' => $proxy);
}

function isValid(){
    $network = ip();
    $ip = $network['ip'];
    $ua = _isset($_SERVER, "HTTP_USER_AGENT");
    
    $key = _isset($_SESSION, "map-key");
    $id = _isset($_SESSION, "map-id");
    $naid = _isset($_SESSION, "map-naid");
    
    if($id == hash("whirlpool", crypt($ip, $key), false ) AND 
       $naid == hash("whirlpool", crypt($ua, $key), false )){
        return true;
    }
    
    return false;
}

function ip_inCIDR( $addr, $cidr) {
	list($ip, $mask) = explode('/', $cidr);
	$mask = 0xffffffff << (32 - $mask);
	return ((ip2long($addr) & $mask) == (ip2long($ip) & $mask));
}

?>
