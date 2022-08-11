<?php if (!defined("SYSTEM")) die('Error 404');

function show404() {
  header("HTTP/1.0 404 Not Found");
  echo "<h1>404 Not Found</h1>";
  echo "The page that you have requested could not be found.";
  exit();
}
