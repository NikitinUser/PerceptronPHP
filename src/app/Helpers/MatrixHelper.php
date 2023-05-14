<?php

namespace NikitinUser\perceptronPHP\app\Helpers;

class MatrixHelper
{
    public static function arrayValuesDifferent(array $a, array $b): array
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

    public static function numberMinusMatrix(int $num, array $matrix): array
    {
        $output = [];
        for ($i = 0; $i < count($matrix); $i++) {
            for ($j = 0; $j < count($matrix[$i]); $j++) {
                $diff = $num - $matrix[$i][$j];
                $output[] = [$diff];
            }
        }

        return $output;
    }

    public static function multiplyMatrix(array $a, array $b): array
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

    public static function transposeMatrix(array $matrix): array
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

    public static function sumMatrix(array $a, array $b): array
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

    /**
     * this method is analog for numpy.dot
     * made by chat GPT
     */
    public static function dotProduct(array $a, array $b)
    {
        $aShape = self::shape($a);
        $bShape = self::shape($b);
      
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
    private static function shape(array $arr): array
    {
        $shape = array();
        $shape[] = count($arr);

        if (is_array($arr[0])) {
            $subShape = self::shape($arr[0]);
            for ($i = 0; $i < count($subShape); $i++) {
                $shape[] = $subShape[$i];
            }
        } else {
            $shape[] = 1;
        }
        
        return $shape;
    }
}
