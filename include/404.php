<?php

http_response_code(404);
die("<center><h1>404 Not Found</h1></center><hr><center>" . _isset($_SERVER, "SERVER_SOFTWARE") . "</center>");
?>
