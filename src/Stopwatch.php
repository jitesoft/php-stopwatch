<?php
namespace Jitesoft\Time;

/**
 * @property int $nanoseconds
 * @property int $microseconds
 * @property int $milliseconds
 * @property int $seconds
 * @property int $minutes
 * @property int $hours
 */
class Stopwatch {
    private int  $startTime;
    private ?int $stopTime = null;
    private ?int $pauseTime = null;

    public function __construct() {
        $this->start();
    }

    private function getStop(): int {
        return $this->pauseTime ?? $this->stopTime ?? hrtime(true);
    }

    /**
     * Stop the stopwatch.
     *
     * @return $this
     */
    public function stop(): self {
        $this->stopTime = hrtime(true);
        return $this;
    }

    /**
     * Create a copy of the stopwatch.
     *
     * @return $this
     */
    public function copy(): static {
        $sw = new static();
        $sw->startTime = $this->startTime;
        $sw->stopTime  = $this->stopTime;

        return $sw;
    }

    /**
     * Pause the stopwatch.
     *
     * @return $this
     */
    public function pause(): self {
        $this->pauseTime = hrtime(true);
        return $this;
    }

    /**
     * Resume the stopwatch.
     *
     * @return $this
     */
    public function resume(): self {
        $sub = hrtime(true) - $this->pauseTime;
        $this->pauseTime = null;
        $this->startTime += $sub;
        return $this;
    }

    /**
     * Start the stopwatch.
     *
     * @return $this
     */
    public function start(): self {
        $this->startTime = hrtime(true);
        return $this;
    }

    /**
     * Get the stopwatch time as nanoseconds.
     *
     * @return int
     */
    public function nanoseconds(): int {
        return $this->getStop() - $this->startTime;
    }

    /**
     * Get the stopwatch time as microseconds.
     * Observe: The time is floored to closest microsecond.
     *
     * @return int
     */
    public function microseconds(): int {
        return (int)($this->nanoseconds() / 1000);
    }

    /**
     * Get the stopwatch time as milliseconds.
     * Observe: The time is floored to closest millisecond.
     *
     * @return int
     */
    public function milliseconds(): int {
        return (int)($this->microseconds() / 1000);
    }

    /**
     * Get the stopwatch time as seconds.
     * Observe: The time is floored to closest second.
     *
     * @return int
     */
    public function seconds(): int {
        return (int)($this->milliseconds() / 1000);
    }

    /**
     * Get the stopwatch time as minutes.
     * Observe: The time is floored to closest minute.
     *
     * @return int
     */
    public function minutes(): int {
        return (int)($this->seconds() / 60);
    }

    /**
     * Get the stopwatch time as hours.
     * Observe: The time is floored to closest hour.
     *
     * @return int
     */
    public function hours(): int {
        return (int)($this->minutes() / 60);
    }

    /** @noinspection MagicMethodsValidityInspection */
    public function __get(string $name) {
        switch ($name) {
            case 'nanoseconds': return $this->nanoseconds();
            case 'microseconds': return $this->microseconds();
            case 'milliseconds': return $this->milliseconds();
            case 'seconds': return $this->seconds();
            case 'minutes': return $this->minutes();
            case 'hours': return $this->hours();
        }

        throw new \InvalidArgumentException();
    }

    public function __toString(): string {
        $ns = $this->nanoseconds();
        $us = $this->microseconds();
        $ms = $this->milliseconds();
        $s  = $this->seconds();
        $m  = $this->minutes();
        $h =  $this->hours();

        if ($h > 0) {
            return sprintf("%d.%d h", $h, $m / 60);
        }

        if ($m > 0) {
            return sprintf("%d.%d m", $m, $s % 60);
        }

        if ($s > 0) {
            return sprintf("%d.%d s", $s, $ms % 1000);
        }

        if ($ms > 0) {
            return sprintf("%d.%d ms", $ms, $us % 1000);
        }

        if ($us > 0) {
            return sprintf("%d.%d us", $us, $ns % 1000);
        }

        return sprintf("%d ns", $ns);
    }

}
