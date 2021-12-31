<?php

use App\Defusal\Defusal;
use App\IniManager;

require_once 'vendor/autoload.php';

$config = new IniManager();
$config->load('game.ini');

$defusal = new Defusal($config);

echo 'Welcome to the IEM Bomb Defusal';
echo PHP_EOL;

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

// chiant
//ini_set('output_buffering','on');
//ini_set('zlib.output_compression', 0);
//ob_implicit_flush();

while (true) {
    $line = trim(readline($defusal->key() ? $defusal->key().'> ' : '> '));
    $args = array_filter(explode(' ', strtolower($line)));


    if (empty($args[0])) {
        continue;
    }

    switch ($args[0]) {
        case 'select':
            if (!$defusal->key($args[1] ?? '')) {
                echo 'Vous devez sélectionner une clé valide';
                $changeTime('-1 minute');
            }
            break;
        case 'exit':
            $defusal->unkey();
            break;

        case 'ask':
            $changeTime($defusal->ask($args[1] ?? ''));
            break;

        case 'stopall':
            if ($line === 'stopall -p '.Defusal::SOLUTION) {
                $config->set('active', false);
                $config->save('game.ini');

                echo 'Opération effectuée';
                break;
            }

            $changeTime('-3 minute');
            echo 'BIIIP BOOOP ERRORRRR';

            break;

        default:
            echo 'Erreur, commande inconnue';
            $changeTime('-20 seconds');
            break;
    }

    // chiant bis
    echo PHP_EOL;
}
