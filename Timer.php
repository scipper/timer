<?php

namespace Scipper\ApplicationServer\Tools\Performance;

/**
 * Class Timer
 *
 * @author Steffen Kowalski <scipper@myscipper.de>
 *
 * @namespace Scipper\ApplicationServer\Tools\Performance
 * @package Scipper\ApplicationServer\Tools\Performance
 */
class Timer {

    /**
     *
     * @var float
     */
    protected $currentTime;

    /**
     *
     * @var float
     */
    protected $lastTime;

    /**
     *
     * @var float
     */
    protected $elapsed;

    /**
     * @var array
     */
    protected $elapsedSum;


    /**
     *
     */
    public function __construct() {
        $this->currentTime = 0.0;
        $this->lastTime = $this->microtime();
        $this->elapsed = 0.0;
        $this->elapsedSum = array();
    }

    /**
     *
     * @return float
     */
    public function microtime() {
        return microtime(true);
    }

    /**
     *
     */
    public function update() {
        $this->currentTime = $this->microtime();
        $this->elapsed = $this->currentTime - $this->lastTime;
        $this->lastTime = $this->currentTime;

        array_push($this->elapsedSum, $this->elapsed);

        if(array_sum($this->elapsedSum) > .01) {
            array_shift($this->elapsedSum);
        }
        array_push($this->elapsedSum, $this->elapsed);
    }
    /**
     *
     * @return float
     */
    public function getElapsed() {
        return $this->elapsed;
    }

    /**
     * @return float
     */
    public function getAverageTimePerTick() {
        return number_format((array_sum($this->elapsedSum) / count($this->elapsedSum) * 1000), 4) . " ms      ";
    }

    /**
     *
     */
    public function adjust() {
        $frameTicks = $this->getElapsed();
        if($frameTicks < 1) {
            usleep(1000 - $frameTicks);
        }
    }

}
