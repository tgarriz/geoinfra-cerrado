<?php

if (defined("PROCESS")) {
    require_once '../../config/Config.php';
} else {
    require_once '../config/Config.php';
}

Class Connection {

    private $params;
    public $dbh;
    public $error = '';

    public function __construct($role) {
        
        $this->params = Config::get($role);

        if (count($this->params)) {
            if ($this->connect()) {
                return $this->dbh;
            }
        }
    }

    private function connect() {
        try {
            $dbh = new PDO("pgsql:dbname=" . $this->params['dbname'] . ";host=" . $this->params['host'], $this->params['user'], $this->params['pass']);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            //$dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $this->dbh = $dbh;
            return true;
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }

}

?>
