<?php

namespace App;

/**
 *
 */
class BombDisplayer
{
    public function display(string $minutes, string $seconds): void
    {
        $arrayMinutes = str_split($minutes);
        if (count($arrayMinutes) === 1) {
            $arrayMinutes = ['0', $arrayMinutes[0]];
        }

        $arraySeconds = str_split($seconds);
        if (count($arraySeconds) === 1) {
            $arraySeconds = ['0', $arraySeconds[0]];
        }

        echo $this->implode([
            $this->getAscii($arrayMinutes[0]),
            $this->getAscii($arrayMinutes[1]),
            $this->getAscii('\''),
            $this->getAscii($arraySeconds[0]),
            $this->getAscii($arraySeconds[1]),
            $this->getAscii('"'),
        ]);
        echo PHP_EOL, PHP_EOL, PHP_EOL;
    }

    private function getAscii($charName): string
    {

        if ($charName === '"') {
            return $this->implode([
                $this->getAscii('\''),
                $this->getAscii('\''),
            ]);
        }

        if ($charName === '\'') {
            $charName = 'guillemet';
        }

        $char = file_get_contents(__DIR__ . '/Ascii/'.$charName.'.ascii');
        if (false === $char) {
            throw new \Exception('La lettre '.$char.' n\'existe pas');
        }

        return $char;
    }

    private function implode(array $chars)
    {
        $res = $chars[0];
        foreach ($chars as $charIndex => $char) {
            if ($charIndex === 0) {
                continue;
            }

            $nexts = array_map(function ($next) {
                return '  '.$next.PHP_EOL;
            }, explode(PHP_EOL, $char));

            $exploded = array_filter(explode(PHP_EOL, $res));
            $res = '';
            foreach ($exploded as $i => $part) {
                $res .= $part . $nexts[$i];
            }
        }

        return $res.PHP_EOL;
    }
}
