<?php

namespace App;

class IniManager
{
    /**
     * @var array|false
     */
    private $data = [];

    public function load(string $filename)
    {
        $this->data = parse_ini_file($filename);
    }

    public function get(string $key, $default = null): string
    {
        if (!array_key_exists($key, $this->data)) {
            return $default;
        }

        return $this->data[$key];
    }

    public function set(string $key, string $value): self
    {
        $this->data[$key] = $value;

        return $this;
    }

    public function save(string $filename): self
    {
        $res = [];

        foreach($this->data as $key => $datum)
        {
            if(is_array($datum))
            {
                $res[] = "[$key]";
                foreach($datum as $skey => $sval) $res[] = "$skey = ".(is_numeric($sval) ? $sval : '"'.$sval.'"');
            }
            else $res[] = "$key = ".(is_numeric($datum) ? $datum : '"'.$datum.'"');
        }

        file_put_contents($filename, implode("\r\n", $res));

        return $this;
    }
}
