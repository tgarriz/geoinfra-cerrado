<?php
include_once '../include/protect.php';
?>
<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="user-scalable=0, width=device-width, initial-scale=1.0, maximum-scale=1.0">
        <meta name="keywords" content="GIS, Buenos Aires, Geodesia, Geobasig, Sistema de Información Geográfico">
        <meta name="description" content="Sistema de Información Geográfico de la Dirección de Geodesia e Imágenes Satelitáles, Ministerio de Infraestructura Provincia de Buenos Aires">
        <meta name="author" content="David Orellano">
        <link rel="shortcut icon" type="image/png" href="./images/favicon.png">
        <title>GeoInfra</title>
        <link type="text/css" rel="stylesheet" href="./styles/style.css" media="all" />
        <link type="text/css" rel="stylesheet" href="./styles/app.css" media="all" />        
        <script type="text/javascript" src="https://maps.google.com/maps/api/js?v=3.exp"></script>
        <script type="text/javascript" src="./javascripts/OpenLayers.js"></script>
        <script type="text/javascript" src="./javascripts/app.js"></script>
    </head>
    <body>
        <div id="geocoding_google_result"><ul></ul></div>
        <div id="north">
            <div id="loading"></div>
            <div id="logo">
                <div id="l_geo"><img src="images/geoinfra.png"></div>
                <div id="l_bsas"><img src="images/bsas.png"></div>
            </div>
            <div id="geocoding_google">
                <input id="geocoding_google_address" />
                <b id="geocoding_google_clean" class="icon-cancel"></b>
            </div>
        </div>
        <div id="west">
            <ul id="sidebar">
                <li><span data-href="#layers-content" title="Capas" class="active"><i class="icon-layers"></i></span></li>
                <li><span data-href="#tools-content" title="Herramientas" ><i class="icon-tools"></i></span></li>
                <li><span data-href="#location-content" title="Ubicación" ><i class="icon-location"></i></span></li>
                <li><span data-href="#legend-content" title="Leyenda" ><i class="icon-list"></i></span></li>
                <li><a href="logout.php"><span data-href="#" title="Salir" ><i class="icon-power"></i></span></a></li>
            </ul>
            <div id="toggle-content">
                <i class="icon-chevron-left"></i>
            </div>
        </div>
        <div id="content" class="nano">
            <div class="nano-content">
                <div id="layers-content" class="contentblock">
                    <div class="search">
                        <input type="text" class="search-query" placeholder="Buscar Capa">
                        <div class="lens"><i class="icon-search"></i></div>
                    </div>
                    <div>
                        <ul id="layers"></ul>
                    </div>
                </div>
                <div id="tools-content" class="contentblock hidden">
                    <div id="tools">
                        <div id="tools-options">
                            <div class='nav'>
                                <input type='text' value='Seleccionar Herramienta' disabled=''>
                                <i class='icon-chevron-small-down'></i>
                            </div>
                            <div class='drop'><ul></ul></div>
                        </div>
                        <div id="tools-items" class="container"></div>
                    </div>
                </div>
                <div id="location-content" class="contentblock hidden">
                    <div id="location">
                        <div id="location-options">
                            <div class='nav'>
                                <input type='text' value='Determinar Ubicación por...' disabled=''>
                                <i class='icon-chevron-small-down'></i>
                            </div>
                            <div class='drop'><ul></ul></div>
                        </div>
                        <div id="location-items" class="container"></div>
                    </div> 
                </div>
                <div id="legend-content" class="contentblock hidden">
                    <div>
                        <div id="legend" class="container"></div>
                    </div>
                </div>
            </div>
        </div>
        <div id="center">            
            <div id="map">
                <div id="north-center">
                    <ul id="mainmenu">
                        <li class="zmax" title="Zoom Máximo"><span><i class="icon-max"></i></span></li>
                        <li class="ctrl btnset" title="Paneo" data-control="pan"><span class="active"><i class="icon-hand"></i></span></li>
                        <li class="ctrl btnset" title="Acercar" data-control="zoomin"><span><i class="icon-zoom_in"></i></span></li>
                        <li class="ctrl btnset" title="Alejar" data-control="zoomout"><span><i class="icon-zoom_out"></i></span></li>
                        <li class="ctrl btnset" title="Información" data-control="info"><span><i class="icon-info"></i></span></li>
                        <li class="btnset ruler" title="Medir"><span><i class="icon-ruler"></i></span></li>
                        <li class="over" title="Mapa de Referencia"><span><i class="icon-over"></i></span></li>
                    </ul>
                </div>
                <div id="measure">
                    <ul>
                        <li class="ctrl btnset" title="Distancia" data-control="line"><span><i class="icon-ruler"></i></span></li>
                        <li class="ctrl btnset" title="Área" data-control="polygon"><span><i class="icon-ruler2"></i></span></li>
                    </ul>
                </div>
                <div id="out"></div>
                <div id="info"></div>
            </div>      
        </div>
    </body>
</html>