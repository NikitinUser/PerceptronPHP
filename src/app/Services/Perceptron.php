<?php

namespace NikitinUser\perceptronPHP\app\Services;

use NikitinUser\perceptronPHP\app\Helpers\MatrixHelper;

class Perceptron
{
    public const DISPLACEMENT_NEURON = 1;
    public const COUNT_ITERATION_PROPOGATION = 40000;

    private array $trainingInputs = [
        [0, 0, 1, 1],
        [1, 1, 1, 1],
        [1, 0, 1, 1],
        [0, 1, 1, 1]
    ];

    private array $trainingOutputs = [
        [0],
        [1],
        [1],
        [0]
    ];

    public function getOutput(): array
    {
        $synapticWeights = $this->backPropagation();

        $newInputs = [[1,1,0,1], [1,0,0,1], [0,0,0,1], [0,1,0,1]];
        $scalarProducts = MatrixHelper::dotProduct($newInputs, $synapticWeights);
        return $this->sigmoid($scalarProducts);
    }

    public function backPropagation(): array
    {
        $outputs = [];

        $synapticWeights = $this->getRandomInputs();

        for ($i = 0; $i < self::COUNT_ITERATION_PROPOGATION; $i++) {
            $scalarProducts = MatrixHelper::dotProduct($this->trainingInputs, $synapticWeights);
            $outputs = $this->sigmoid($scalarProducts);

            $err = MatrixHelper::arrayValuesDifferent($this->trainingOutputs, $outputs);

            // 1 тк формула np.dot(input_layer.T, err * (outputs * (1 - outputs)))
            $delta = MatrixHelper::numberMinusMatrix(1, $outputs);
            $multiplyOutputAndDelta = MatrixHelper::multiplyMatrix($outputs, $delta);
            $multiplyErrAndMOAD = MatrixHelper::multiplyMatrix($err, $multiplyOutputAndDelta);

            $transposedInputs = MatrixHelper::transposeMatrix($this->trainingInputs);

            $adjustment = MatrixHelper::dotProduct($transposedInputs, $multiplyErrAndMOAD);

            $synapticWeights = MatrixHelper::sumMatrix($synapticWeights, $adjustment);
        }
        echo json_encode($outputs, JSON_PRETTY_PRINT);
        echo "<br><br>";
        
        return $synapticWeights;
    }

    private function getRandomInputs(): array
    {
        $randomInputs = [];
        for ($i = 0; $i < 3; $i++) {
            $randomInputs[$i][0] = $this->randomFloat();
        }
        $randomInputs[3][0] = self::DISPLACEMENT_NEURON;
        return $randomInputs;
    }

    private function randomFloat()
    {
        $max = -1.0;
        $min = 1.0;

        $range = $max - $min;
        $num = $min + $range * (mt_rand() / mt_getrandmax());    
        $num = round($num, 2);

        return (float)$num;
    }

    private function sigmoid(array $matrix): array
    {
        $output = [];

        foreach ($matrix as $arr) {
            foreach ($arr as $val) {
                $sigmoid = 1 / (1 + exp(-$val));
                $output[] = [$sigmoid];
            }
        }

        return $output;
    }
}
