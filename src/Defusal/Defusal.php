<?php

namespace App\Defusal;


use App\IniManager;

class Defusal
{
    const SOLUTION = 'MARIA';

    private $activeKey;

    private $keys = [
        '1' => 'tiramisu',
        '2' => 'toreador',
        '3' => 'profesor',
        '4' => 'tournesol',
        '5' => 'scooby',
    ];

    private $codes = [
        '1' => '01001101', // M
        '2' => '01000001', // A
        '3' => '01010010', // R
        '4' => '01001001', // I
        '5' => '01000001', // A
    ];

    /**
     * @var IniManager
     */
    private $config;

    public function __construct(IniManager $config)
    {
        $this->config = $config;
    }


    /**
     * Tente de se placer sur une clé, renvoie false, si cette derniere n existe pas
     */
    public function key(string $key = null)
    {
        if ($key === null) {
            return $this->activeKey;
        }

        // Oui j'aime les clés
        if (!array_key_exists($key, array_keys($this->keys))) {
            return false;
        }

        $this->activeKey = $key;

        return true;
    }

    public function ask(string $password): ?string
    {
        if (in_array($this->activeKey, $this->keysDone())) {
            echo 'Jeee.... BIP BIP DEJAAAA MOTTT DE pASSE dejaaaa PENALITE POUR TENTATIVE DE TRICHE';
            echo PHP_EOL;
            echo 'Ou si vous avez simplement oublié le code désolé au passage :'.PHP_EOL;
            echo 'key part is : '.$this->codes[$this->activeKey];
            return '-1 minute';
        }

        if ($this->keys[$this->activeKey] === strtolower($password)) {
            $this->addKeysDone($this->activeKey);

            echo 'key part is : '.$this->codes[$this->activeKey];

            if ($this->activeKey === '1') {
                return '+15 minutes';
            }

            return '+5 minutes';
        }

        echo 'Erreur, le mot de passe est invalide';

        return '-2 minutes';
    }

    /**
     * Déselectionne la clé
     */
    public function unkey()
    {
        $this->activeKey = null;
    }

    private function keysDone(): array
    {
        return array_filter(explode(',', $this->config->get('keysDone', '')));
    }

    private function addKeysDone($activeKey)
    {
        $this->config->set('keysDone', implode(',', array_unique(array_merge($this->keysDone(), [$activeKey]))));
        $this->config->save('game.ini');
    }
}
