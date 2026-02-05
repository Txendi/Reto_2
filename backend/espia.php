<html lang="es">
  <head>
    <?php
    ini_set('display_errors','On');
    error_reporting(E_ALL);
    ?>
    <meta charset="UTF-8">
    <title>Sesiones (Espía)</title>
  </head>
  <?php session_start();?>
  <body>
<?php
    print "<p>Variables de sesión:</p>\n";
    print "<pre>\$_SESSION: ".print_r($_SESSION,true)."</pre>\n";
    print "<pre>\$_COOKIE: ".print_r($_COOKIE,true)."</pre>\n";
?>
  </body>
</html>