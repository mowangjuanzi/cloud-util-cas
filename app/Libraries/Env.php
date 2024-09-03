<?php

namespace App\Libraries;

use PhpOption\Option;
use PhpOption\Some;

class Env
{
    public static function get($key, $default = null)
    {
        return self::getOption($key)->getOrCall(fn() => $default);
    }

    protected static function getOption($key): Some|Option
    {
        return Option::fromValue($_ENV[$key] ?? null)
            ->map(function ($value) {
                switch (strtolower($value)) {
                    case 'true':
                    case '(true)':
                        return true;
                    case 'false':
                    case '(false)':
                        return false;
                    case 'empty':
                    case '(empty)':
                        return '';
                    case 'null':
                    case '(null)':
                        return;
                }

                if (preg_match('/\A([\'"])(.*)\1\z/', $value, $matches)) {
                    return $matches[2];
                }

                return $value;
            });
    }
}
