<?php

require_once '../../config/Urls.php';

class Search {

    //protected $xmlPath;
    protected $xml;
    public $msg;

    public function __construct($xmlPath) {
        //$this->xmlPath = $xmlPath;
        $this->xml = simplexml_load_file($xmlPath, NULL, LIBXML_NOENT);
    }

    public function get() {
        $items = $this->xml->xpath("//searchs/item");
        $json = '';
        foreach ($items as $si) {
            //Requerido
            $title = addslashes((string) $si['title']);
            $id = (int) $si['id'];
            $layer = (string) $si['layer'];
            //Opcional
            $menu = (string) $si['menu'] ? ", \"menu\": \"" .(string) $si['menu'] ."\"" : 'search';
            $url = (string) $si['url'] ? (string) $si['url'] : '';
            $isUrl = filter_var($url, FILTER_VALIDATE_URL);
            if(!$isUrl and $url != ''){
                $url = Urls::get($url);
            }
            $url = $url ? ", \"url\": \"$url\"" : '';
            
            $call = (string) $si['call'] ? ", \"call\": \"" . (string) $si['call'] . "\"" : '';
            $show = (string) $si['show'] ? ", \"show\": \"" .(string) $si['show'] . "\"" : '';
            
            $json .= ", { \"id\": $id, \"title\": \"$title\", \"layer\": \"$layer\" $url $menu $call $show, ";
            
            $fields = $si->field;
            $json .= "\"fields\": [";
            $fc = 0;
            foreach ($fields as $field) {
                $fjson = "";
                //Requerido
                $ftitle = addslashes((string) $field['title']);                
                $fldname = (string) $field['name'];
                //Opcional
                $integer = (string)$field['type']=='n' ? ", \"int\": 1" : '';                
                $maxlength = (int) $field['maxlength'] ? ", \"maxlength\": " . (int) $field['maxlength'] : '';
                $width = (int) $field['width'] ? ", \"width\": " . (int) $field['width'] : '';
                $required = (int) $field['width'] ? ", \"required\": " . (int) $field['required'] : '';

                $sep = $fc < 1 ? "" : ",";
                $fjson .= "$sep{\"title\": \"$ftitle\", \"name\": \"$fldname\" $integer $maxlength $width $required";
                //Definition field
                if ($field->definition['type'] == true) {
                    //Requerido
                    $type = (string) $field->definition['type'];
                    $fjson .= ", \"definition\": {\"type\": \"$type\"}";
                }
                $fjson .= "}";
                $fc++;
                $json .= $fjson;
                unset($fjson);
            }
            $json .= "]}";
        }
        return "[" . substr($json, 1) . "]";
    }   
}

?>
