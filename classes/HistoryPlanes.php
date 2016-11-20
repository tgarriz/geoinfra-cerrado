<?php

class HistoryPlanes {

    public $json;
    public $error;

    public function __construct($db, $x, $y) {
        $xmin = -16697923;
        $xmax = 3338999;
        
        if ($x > $xmin and $x < $xmax) {
            
            $sql = "SELECT busca_planos(?, ?) AS result;";

            $stmt = $db->prepare($sql);
            
            try {
                $stmt->bindParam(1, $x);
                $stmt->bindParam(2, $y);
                $stmt->execute();

				$json = $stmt->fetch(PDO::FETCH_ASSOC)['result'];
                $json = str_replace("\\", "", trim($json, '\"'));
				$this->json = $json ? $json : "\"\"";
                $db = null;
                return;
            } catch (PDOException $e) {
                $this->error = $e->getMessage();
            }
        }
    }

}

?>
