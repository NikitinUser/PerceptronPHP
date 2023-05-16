<?php

namespace NikitinUser\perceptronPHP\app\Services;

use NikitinUser\perceptronPHP\app\Helpers\MatrixHelper;

class Perceptron
{
    public const COUNT_ITERATION_PROPOGATION = 40000;

    private array $trainingInputs;

    private array $trainingOutputs;

    public function __construct()
    {
        $this->initialTrainingData();
    }

    public function getOutput(): array
    {
        $backPropagationResult = $this->backPropagation();

        $synapticWeights = $backPropagationResult["synapticWeights"];
        $propagationResult = $backPropagationResult["propagationResult"];

        $newInputs = json_decode(file_get_contents(NEW_INPUTS_FILE));
        $newInputs = $this->addDisplacementNeuron($newInputs);

        $scalarProducts = MatrixHelper::dotProduct($newInputs, $synapticWeights);
        $perceptronResult = $this->sigmoid($scalarProducts);

        return [
            "propagationResult" => $propagationResult,
            "perceptronResult" => $perceptronResult
        ];
    }

    private function backPropagation(): array
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
        
        return [
            "propagationResult" => $outputs,
            "synapticWeights" => $synapticWeights
        ];
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

    private function getRandomInputs(): array
    {
        $randomInputs = [];
        for ($i = 0; $i < (count($this->trainingInputs[0]) - 1); $i++) {
            $randomInputs[$i][0] = $this->randomFloat();
        }
        $randomInputs[count($randomInputs)][0] = DISPLACEMENT_NEURON;
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

    private function initialTrainingData()
    {
        $this->trainingInputs = json_decode(file_get_contents(TRAINING_INPUTS_FILE));
        $this->trainingOutputs = json_decode(file_get_contents(TRAINING_OUTPUTS_FILE));

        $this->trainingInputs = $this->addDisplacementNeuron($this->trainingInputs);
    }

    private function addDisplacementNeuron(array $trainingInputs): array
    {
        for ($i = 0; $i < count($trainingInputs); $i++) {
            $trainingInputs[$i][count($trainingInputs[$i])] = DISPLACEMENT_NEURON;
        }

        return $trainingInputs;
    }
}
