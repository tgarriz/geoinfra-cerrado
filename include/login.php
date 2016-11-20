<?php
include_once '../include/functions.php';
include_once '../include/security_variables.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="user-scalable=0, width=device-width, initial-scale=1.0, maximum-scale=1.0">
        <meta name="keywords" content="GIS, Buenos Aires, Geodesia, Geobasig, Sistema de Información Geográfico">
        <meta name="description" content="Sistema de Información Geográfico de la Dirección de Geodesia e Imágenes Satelitáles, Ministerio de Infraestructura Provincia de Buenos Aires">
        <meta name="author" content="David Orellano">
        <link rel="shortcut icon" type="image/png" href="./images/favicon.png">
        <title>GEOINFRA</title>
        <link type="text/css" rel="stylesheet" href="./styles/main.css" media="all" />
	<link type="text/css" rel="stylesheet" href="./styles/ingreso.css" media="all" />
        <script type="text/javascript" src="./javascripts/main.js"></script>
        <!--[if lt IE 9]>
            <link type="text/css" rel="stylesheet" href="./styles/ie.css" />
        <![endif]-->
    </head>
    <body>
        <div id="content">
            <div id="login" class="login">
                <img src="./images/logo.png" />
                <div id="form">
                    <form id="form-login" novalidate="novalidate" spellcheck="false" action="login.php?id=<?php echo $id; ?>&naid=<?php echo $naid; ?>" method="post" autocomplete="off" >
                        <input type="hidden" name="x-user" value="" autocomplete="off" />
                        <input type="hidden" name="y-user" value="" autocomplete="off" />
                        <input type="hidden" name="x-value" value="<?php echo $token; ?>" />
                        <input type="text" name="x-name"placeholder="Usuario" autocomplete="off" />					
                        <input type="password" placeholder="Contraseña" value="" autocomplete="off" />						
                        <input type="submit" value="Ingresar">
                    </form>
                </div>
		<a href="http://geolibre.geobasig.com.ar">Ingreso Libre</a>
                    <p>
			<iframe width="90%" height="240" src="https://www.youtube.com/embed/N0YFCzR6zOA?rel=0" frameborder="0" allowfullscreen></iframe>
                    </p>
                    <div id="contact">
                        <p>Tel: (0221) 451-4976/8146</p>
                        <p>geobasig@gmail.com</p>
                    </div>
                </div>
            </div>
            <div id="footer">
                <img src="./images/barra_inferior.svg">
            </div>
        </div>
    </body>
</html>
