<?php

namespace NikitinUser\perceptronPHP\app\Services;

class Perceptron
{
    private array $trainingInputs = [
        [0, 0, 1],
        [1, 1, 1],
        [1, 0, 1],
        [0, 1, 1]
    ];

    private array $trainingOutputs = [
        [0],
        [1],
        [1],
        [0]
    ];

    public function randomInputs()
    {
        return mt_rand(-1, 1);
    }

    public function sigmoid($x)
    {
        return 1 / (1 + exp(-$x));
    }
}
