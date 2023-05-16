<?php

namespace NikitinUser\perceptronPHP\app\Controllers;

use NikitinUser\perceptronPHP\app\Services\Perceptron;

class PerceptronController
{
    private Perceptron $perceptronService;

    public function __construct()
    {
        $this->perceptronService = new Perceptron();
    }

    public function getOutputByPerceptron()
    {
        $data = $this->perceptronService->getOutput();
        return json_encode([$data]);
    }

    public function startTraining()
    {
        $data = $this->perceptronService->startTraining();
        return json_encode([$data]);
    }
}
