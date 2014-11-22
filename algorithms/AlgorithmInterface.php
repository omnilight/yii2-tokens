<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 21.11.2014
 * Time: 15:56
 */

namespace omnilight\tokens\algorithms;


/**
 * Interface AlgorithmInterface
 */
interface AlgorithmInterface
{
    /**
     * Generates token
     * @return string
     */
    public function generate();
} 