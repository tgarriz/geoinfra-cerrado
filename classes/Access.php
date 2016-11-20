<?php

Class Access {

    public $error;
    public $id;

    public function __construct($db, $uid, $cookie, $ip, $proxy, $language, $screen, $referer, $user_agent, $visit, $error) {
        $sql = "SELECT registrar_acceso(?, ?, ?, ?, ?, ?, ?, ?, ?, ?) AS result;";
        $stmt = $db->prepare($sql);
        try {
            $stmt->bindParam(1, $uid, PDO::PARAM_INT);
            $stmt->bindParam(2, $cookie);
            $stmt->bindParam(3, $ip);
            $stmt->bindParam(4, $proxy);
            $stmt->bindParam(5, $language);
            $stmt->bindParam(6, $screen);
            $stmt->bindParam(7, $referer);
            $stmt->bindParam(8, $user_agent);
            $stmt->bindParam(9, $visit, PDO::PARAM_INT);
            $stmt->bindParam(10, $error, PDO::PARAM_INT);
            $stmt->execute();
            
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id = $row['result'];
            $db = null;
            return;
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
        }
    }

}

?>
