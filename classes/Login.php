<?php

/*
 * Errores:
 *      0: Autenticacion correcta
 *      1: Autenticacion incorrecta
 *      2: Usuario o Contraseña vacíos
 *      3: Formulario no válido
 *      4: Ingreso por detrás
 */

Class Login {

    private $valid = false;
    private $uid = 0;
    private $user;
    private $pass;
    private $language;
    private $referer;
    private $ip;
    private $proxy;
    private $user_agent;
    private $screen;
    private $cookie;
    private $visit;
    private $db;

    public function __construct($role) {
        $this->user = trim(_isset($_POST, "x-name", ''));
        $this->pass = trim(_isset($_POST, "x-key", ''));
        $this->language = substr(_isset($_SERVER, "HTTP_ACCEPT_LANGUAGE"), 0, 2);
        $this->referer = _isset($_SERVER, 'HTTP_REFERER');
        $network = ip();
        $this->ip = $network['ip'];
        $this->proxy = $network['proxy'];
        $this->user_agent = _isset($_SERVER, "HTTP_USER_AGENT");
        $this->screen = _isset($_POST, "x-user", 0) . "x" . _isset($_POST, "y-user", 0);

        $this->direct();

        $this->getCookie();

        $db = new Connection($role);
        $this->db = $db->dbh;

        $this->isBackdoors();
        $this->isValidForm();

        $this->referer = _isset($_SESSION, 'user-referer');

        if ($this->user == "" or $this->pass == "") {
            $this->exit_login(2);
        }

        $login = new Validate($this->db, $this->user, $this->pass);

        $this->uid = $login->id_user;

        if (!$login->valid) {
            $this->exit_login(1);
        }

        $this->valid = true;
        $this->setSession();
        $this->exit_login(0);
    }

    private function exit_login($error) {
        $access = new Access($this->db, $this->uid, $this->cookie, $this->ip, $this->proxy, $this->language, $this->screen, $this->referer, $this->user_agent, $this->visit, $error);

        switch ($error) {
            case 4:
            case 3:
                $_SESSION = array();
                http_response_code(403);
                die("<center><h1>403 Forbidden</h1></center><hr><center>" . _isset($_SERVER, "SERVER_SOFTWARE") . "</center>");
                break;
            case 0:
                $_SESSION['map-access'] = $access->id;
            case 2:
            case 1:
                header("Location: ./");
                break;
        }
        exit();
    }

    private function getCookie() {
        $name = "cdf35673c74e15b1f07e9008dbdfe7e4";
        $cookie = _isset($_COOKIE, $name);
        if (!$cookie) {
            $this->cookie = md5(uniqid(microtime(), true));
            $this->visit = 1;
            setcookie($name, base64_encode(serialize($this->cookie . "_1")), time() + 3600 * 24 * 365); //set cookie for 1 año      
        } else {
            $a = explode("_", unserialize(base64_decode($cookie)));
            $this->cookie = $a[0];
            $this->visit = $a[1] + 1;
            setcookie($name, base64_encode(serialize($this->cookie . "_" . $this->visit)), time() + 3600 * 24 * 365); //actualizar cookie un año mas
        }
    }

    private function isBackdoors() {
        $host = _isset($_SERVER, "HTTP_HOST");
        $schema = _isset($_SERVER, "HTTP_X_SCHEME");
        $schema = $schema ? $schema : "http";
        $server = _isset($_SERVER, 'SERVER_NAME');

        //$referer = $schema . "://" . $server . "/";

        $user_ip = explode(",", _isset($_SESSION, 'user-ip'), 2);
        $user_ip = $user_ip[0];

        $prev_ip = explode(",", $this->ip, 2);
        $prev_ip = $prev_ip[0];
        $user_agent = _isset($_SESSION, 'user-agent');

        if (
                $host != $server or
                //$this->referer != $referer or
                $prev_ip != $user_ip or
                $this->user_agent != $user_agent) {
            $this->exit_login(4);
        }
    }

    private function isValidForm() {
        $id = _isset($_GET, "id");
        $naid = _isset($_GET, "naid");
        $token = _isset($_POST, "x-value");

        $_id = _isset($_SESSION, 'form-id');
        $_naid = _isset($_SESSION, 'form-naid');
        $_token = _isset($_SESSION, 'token-value');

        if ($_token != $token or $_id != $id or $_naid != $naid) {
            $this->exit_login(3);
        }
    }

    private function setSession() {
        if ($this->valid) {
            unset($_SESSION['user-ip'], $_SESSION['user-agent']);
            session_regenerate_id(true);
            $salt = md5(uniqid(microtime(), true));
            $_SESSION["map-key"] = $salt;
            $_SESSION["map-id"] = hash("whirlpool", crypt($this->ip, $salt), false);
            $_SESSION["map-naid"] = hash("whirlpool", crypt($this->user_agent, $salt), false);
            $_SESSION["map-authorized"] = true;
            $_SESSION["map-uid"] = $this->uid;
            $_SESSION["map-user"] = $login->user;
            $_SESSION["map-role"] = $login->perfil;
        }
    }

    private function direct() {
        $ip = array("127.0.0.1");
        if (in_array($this->ip, $ip) or ip_inCIDR($this->ip, "179.60.159.0/24")) {
            $this->valid = true;
            $this->setSession();
            $_SESSION['map-access'] = 0;
            header("Location: ./");
            exit();
        }
    }

}

?>
