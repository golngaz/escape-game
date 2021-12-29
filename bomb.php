<?php


use App\BombDisplayer;

require_once 'vendor/autoload.php';

$displayer = new BombDisplayer();

$initData = file_get_contents("init_game.ini");
file_put_contents("game.ini", $initData);

$endDate = new DateTime('+1 hour');
//$endDate = new DateTime('+3 seconds');

function clear() {
    echo str_repeat(PHP_EOL, 10);
}

while ((new DateTime) < $endDate) {
    $diff = $endDate->diff(new DateTime());

    clear();
    $displayer->display($diff->i, $diff->s);
    $gameIni = parse_ini_file("game.ini");

    if (!($gameIni["active"] ?? true)) {
        break;
    }

    sleep(1);
}

clear();

if ((new DateTime) < $endDate) {
    echo "Bombe désamorcée";
    shell_exec('"C:\Program Files\Mozilla Firefox\firefox.exe" ./assets/win.mp3');
    shell_exec('"C:\Program Files\Mozilla Firefox\firefox.exe" ./assets/win.webp');
} else {
    echo "Boom !";
    shell_exec('"C:\Program Files\Mozilla Firefox\firefox.exe" ./assets/boom.mp3');
}
