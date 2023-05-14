<?php

namespace NikitinUser\perceptronPHP\app\Services;

class Perceptron
{
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

    public function getOutput()
    {
        $synapticWeights = $this->backPropagation();

        $newInputs = [[1,1,0,1], [1,0,0,1], [0,0,0,1], [0,1,0,1]];
        $resultMatrix = $this->dotProduct($newInputs, $synapticWeights);
        return $this->sigmoid($resultMatrix);
    }

    // метод обратного распространения
    public function backPropagation()
    {
        $outputs = [];

        $synapticWeights = $this->getRandomInputs();

        for ($i = 0; $i < 20000; $i++) {
            $resultMatrix = $this->dotProduct($this->trainingInputs, $synapticWeights);
            $outputs = $this->sigmoid($resultMatrix);

            $err = $this->arrayValuesDifferent($this->trainingOutputs, $outputs);

            $delta = $this->numberMinusMatrix(1, $outputs);
            $multiplyOutputAndDelta = $this->multiplyMatrix($outputs, $delta);
            $multiplyErrAndMOAD = $this->multiplyMatrix($err, $multiplyOutputAndDelta);

            $transposedInputs = $this->transposeMatrix($this->trainingInputs);

            $adjustment = $this->dotProduct($transposedInputs, $multiplyErrAndMOAD);

            $synapticWeights = $this->sumMatrix($synapticWeights, $adjustment);
        }
        echo json_encode($outputs, JSON_PRETTY_PRINT);echo "<br><br>";
        
        return $synapticWeights;
    }

    private function getRandomInputs(): array
    {
        $randomInputs = [];
        for ($i = 0; $i < 3; $i++) {
            $randomInputs[$i][0] = $this->randomFloat();
        }
        $randomInputs[3][0] = 1; //Нейрон смещения
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

    /**
     * this method is analog for numpy.dot
     * made by chat GPT
     */
    private function dotProduct(array $a, array $b)
    {
        $aShape = $this->shape($a);
        $bShape = $this->shape($b);
      
        if ($aShape[0] == 0 || $bShape[0] == 0) {
            return $a * $b;
        } elseif ($aShape[0] == 1 && $bShape[0] == 1) {
            $sum = 0;
            for ($i = 0; $i < count($a); $i++) {
                $sum += $a[$i] * $b[$i];
            }
            return $sum;
        } elseif ($aShape[1] == $bShape[0]) {
            $output = array();
            for ($i = 0; $i < $aShape[0]; $i++) {
                $row = array();
                for ($j = 0; $j < $bShape[1]; $j++) {
                    $sum = 0;
                    for ($k = 0; $k < $aShape[1]; $k++) {
                        $sum += $a[$i][$k] * $b[$k][$j];
                    }
                    $row[] = $sum;
                }
                $output[] = $row;
            }
            if ($aShape[1] == 1 && $bShape[1] == 1) {
                return $output[0][0];
            } else {
                return $output;
            }
        } else {
            throw new \Exception("Invalid shapes for dot product");
        }
    }

    /**
     * made by chat GPT
     */
    private function shape(array $arr): array
    {
        $shape = array();
        $shape[] = count($arr);

        if (is_array($arr[0])) {
            $subShape = $this->shape($arr[0]);
            for ($i = 0; $i < count($subShape); $i++) {
                $shape[] = $subShape[$i];
            }
        } else {
            $shape[] = 1;
        }
        
        return $shape;
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

    private function arrayValuesDifferent(array $a, array $b): array
    {
        $differents = [];
        for ($i = 0; $i < count($a); $i++) {
            for ($j = 0; $j < count($a[$i]); $j++) {
                $diff = $a[$i][$j] - $b[$i][$j];
                $differents[] = [$diff];
            }
        }

        return $differents;
    }

    private function numberMinusMatrix(int $num, array $matrix): array
    {
        $output = [];
        for ($i = 0; $i < count($matrix); $i++) {
            for ($j = 0; $j < count($matrix[$i]); $j++) {
                $diff = 1 - $matrix[$i][$j];
                $output[] = [$diff];
            }
        }

        return $output;
    }

    private function multiplyMatrix(array $a, array $b): array
    {
        $multiply = [];
        for ($i = 0; $i < count($a); $i++) {
            for ($j = 0; $j < count($a[$i]); $j++) {
                $m = $a[$i][$j] * $b[$i][$j];
                $multiply[] = [$m];
            }
        }

        return $multiply;
    }

    private function transposeMatrix(array $matrix): array
    {
        $transposed = [];

        for ($i = 0; $i < count($matrix); $i++) {
            for ($j = 0; $j < count($matrix[$i]); $j++) {
                if (!isset($transposed[$j])) {
                    $transposed[$j] = [];
                }
                $transposed[$j][$i] = $matrix[$i][$j];
            }
        }

        return $transposed;
    }

    private function sumMatrix($a, $b): array
    {
        $sum = [];
        for ($i = 0; $i < count($a); $i++) {
            for ($j = 0; $j < count($a[$i]); $j++) {
                $s = $a[$i][$j] + $b[$i][$j];
                $sum[] = [$s];
            }
        }

        return $sum;
    }
}
