<?php

namespace omnilight\tokens\algorithms;

use yii\base\Object;


/**
 * Class HumanRandomString
 */
class HumanRandomString extends Object implements AlgorithmInterface
{
    const ALPHABET = '0123456789abcdefghijklmnopqrstuvwxyz';

    public $length = 32;

    /**
     * Generates token with given length
     * @return string
     */
    public function generate()
    {
        $alphabet = static::ALPHABET;
        $token = '';
        for ($i = 0; $i < $this->length; $i++) {
            $token .= $alphabet[mt_rand(0, strlen($alphabet) - 1)];
        }
        return $token;
    }
}