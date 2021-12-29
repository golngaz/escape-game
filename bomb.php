<?php


use App\BombDisplayer;
use App\IniManager;

require_once 'vendor/autoload.php';

$displayer = new BombDisplayer();

$endDate = new DateTime('+10 minutes');
//$endDate = new DateTime('+3 seconds');

$config = new IniManager();

$config->load('init_game.ini');

$config->set('endDate', $endDate->format(DateTime::ATOM));
$config->save('game.ini');

function clear() {
    echo str_repeat(PHP_EOL, 10);
}

while (new DateTime < $endDate) {
    $diff = $endDate->diff(new DateTime());

    clear();
    $displayer->display($diff->i, $diff->s);

    $config->load('game.ini');

    if (!$config->get('active', true)) {
        break;
    }

    if ($config->get('endDate')) {
        $endDate = new DateTime($config->get('endDate'));
    }

    sleep(1);
}

clear();

if ((new DateTime) < $endDate) {
    echo "Bombe désamorcée";
    shell_exec('"'.$config->get('dir-firefox').'" "'.$config->get('dir-assets').'\win.mp3"');
    shell_exec('"'.$config->get('dir-firefox').'" "'.$config->get('dir-assets').'\win.webp"');
} else {
    echo "Boom !";
    shell_exec('"'.$config->get('dir-firefox').'" "'.$config->get('dir-assets').'\boom.mp3"');
}
