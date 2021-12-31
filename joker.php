<?php

use App\Defusal\Defusal;
use App\IniManager;

require_once 'vendor/autoload.php';

$config = new IniManager();
$config->load('game.ini');

$defusal = new Defusal($config);

$changeTime = function (?string $phrase) use ($config)
{
    if (!$phrase) {
        return;
    }

    $timeAdd = new DateTimeImmutable($config->get('endDate'));

    // On ne fait rien à moins de 10 min de la fin (sauf les ajouts)
    if ((new DateTime) > $timeAdd->modify('-10 minutes') && $timeAdd->modify($phrase) < $timeAdd) {
        return;
    }

    $config->set('endDate', $timeAdd->modify($phrase)->format(DateTime::ATOM));

    $config->save('game.ini');
};

if (!empty($argv[1]) && $argv[1] === 'goo') {
    $changeTime('+5 minutes');
}
