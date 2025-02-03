<?php

use bng\System\Router;

require_once('../vendor/autoload.php');

echo '<pre>';
Router::dispatch();

// $nomes = ['João', 'Maria', 'José'];
$nome = "joão ribeiro";

printData($nome);

?>