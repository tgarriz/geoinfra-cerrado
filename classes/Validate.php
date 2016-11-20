<?php

Class Validate {

    public $valid = false;
    public $error;
    public $id_user;
    public $user;
    public $perfil;

    public function __construct($db, $user, $pass) {
        $sql = "SELECT validar_usuario(?, ?) AS result;";
        $stmt = $db->prepare($sql);
        try {
            $stmt->bindParam(1, $user);
            $stmt->bindParam(2, $pass);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $json = json_decode($row['result']);

            $this->valid = $json->success;
            $this->id_user = $json->id;
            $this->user = $json->nombre;
            $this->perfil = $json->perfil;

            $db = null;
            return;
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
        }
    }

}

?>
