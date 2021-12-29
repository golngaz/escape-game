<?php

use App\Util;

require_once "vendor/autoload.php";

$iniData = parse_ini_file('game.ini');

echo "Welcome to the IEM Bomb Defusal";
echo PHP_EOL;

$mdp = readline("> ");

if ($mdp === "patate") {
    $iniData["active"] = false;
    Util::putIni('game.ini', $iniData);
}
