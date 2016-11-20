<?php

require_once '../../config/Categories.php';
require_once '../../config/Urls.php';

class Layer {

    //protected $path;
    protected $xml;
    public $msg;

    public function __construct($path) {
        //$this->path = $path;
        $this->xml = simplexml_load_file($path, NULL, LIBXML_NOENT);
    }

    public function get() {
        $items = $this->xml->xpath("//layers/layer");
        $json = '';
        foreach ($items as $li) {

            $load = true;
            //Required
            $categ = (string) $li['c'] ? (string) $li['c'] : false;
            if ($categ) {
                $category = Categories::get((string) $li['c']);
                if (!$category) {
                    $category = $categ;
                }
            }else{
                $load = false;
            }

            $title = (string) $li['t'] ? addslashes((string) $li['t']) : $load = false;
            $name = (string) $li['n'] ? (string) $li['n'] : $load = false;
            $repository = (string) $li['u'];
            $url = Urls::get($repository);
            if (!$url) {
                $load = false;
            }
            //Opcional
            $visibility = (int) $li['v'] ? ", \"visibility\": " . (int) $li['v'] : "";
            $tiled = (int) $li['d'] ? ", \"tiled\": " . (int) $li['d'] : "";
            $isBaseLayer = (int) $li['b'] ? ", \"isBaseLayer\": " . (int) $li['b'] : "";
            $styles = (string) $li['s'] ? ", \"styles\": \"".(string) $li['s']."\"" : "";
            $opacity = (float) $li['o'] ? ", \"opacity\": " . (float) $li['o'] : "";

            if ($load) {
                $json .= ", { \"source\": \"WMS\", \"category\": \"$category\", \"title\": \"$title\", \"name\": \"$name\", \"url\": \"$url\", \"repository\": \"$repository\" $visibility $tiled $isBaseLayer $styles $opacity}";
            }
        }
        return "[" . substr($json, 1) . "]";
    }

}

?>
