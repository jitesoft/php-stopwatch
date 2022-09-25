<?php
namespace Jitesoft\Time\Test;

use Jitesoft\Time\Stopwatch;
use phpmock\Mock;
use phpmock\MockBuilder;
use PHPUnit\Framework\TestCase;

class StopwatchTest extends TestCase {
    protected array $values;

    protected function setUp(): void {
        parent::setUp();
        Mock::disableAll();

        $this->values = [
            10_000_000_000_000,
            20_000_000_000_000,
        ];

        $mockBuilder = new MockBuilder();
        $mockBuilder->setNamespace('Jitesoft\\Time')
                    ->setName('hrtime')
                    ->setFunction(function () {
                        return array_shift($this->values);
                    })
                    ->build()
                    ->enable();
    }

    public function testNanoSeconds(): void {
        $stopWatch = new Stopwatch();
        self::assertEquals(10_000_000_000_000, $stopWatch->stop()->nanoseconds());
    }

    public function testMicroSeconds(): void {
        $stopWatch = new Stopwatch();
        self::assertEquals(10_000_000_000, $stopWatch->stop()->microseconds());
    }

    public function testMilliSeconds(): void {
        $stopWatch = new Stopwatch();
        self::assertEquals(10_000_000, $stopWatch->stop()->milliseconds());
    }

    public function testSeconds(): void {
        $stopWatch = new Stopwatch();
        self::assertEquals(10_000, $stopWatch->stop()->seconds());
    }

    public function testMinutes(): void {
        $stopWatch = new Stopwatch();
        self::assertEquals(166, $stopWatch->stop()->minutes());
    }

    public function testHours(): void {
        $stopWatch = new Stopwatch();
        self::assertEquals(2, $stopWatch->stop()->hours());
    }

    public function testToStringHour(): void {
        $stopWatch = new Stopwatch();
        self::assertEquals('2.2 h', (string)$stopWatch->stop());
    }

    public function testToStringMinute(): void {
        $this->values = [
            100_000_000_000,
            250_000_000_000,
        ];

        $stopWatch = new Stopwatch();
        self::assertEquals('2.30 m', (string)$stopWatch->stop());
    }

    public function testToStringSecond(): void {
        $this->values = [
            1_000_000_000,
            3_100_000_000,
        ];

        $stopWatch = new Stopwatch();
        self::assertEquals('2.100 s', (string)$stopWatch->stop());
    }

    public function testToStringMillisecond(): void {
        $this->values = [
            1_000_000,
            3_100_000,
        ];

        $stopWatch = new Stopwatch();
        self::assertEquals('2.100 ms', (string)$stopWatch->stop());
    }

    public function testToStringMicrosecond(): void {
        $this->values = [
            1_000,
            3_100,
        ];

        $stopWatch = new Stopwatch();
        self::assertEquals('2.100 us', (string)$stopWatch->stop());
    }

    public function testToStringNanosecond(): void {
        $this->values = [
            1,
            31,
        ];

        $stopWatch = new Stopwatch();
        self::assertEquals('30 ns', (string)$stopWatch->stop());
    }

    public function testPause(): void {
        $this->values = [
          10, // start
          20, // pause (now always start (10) and pause (20) = 10).
          30,
          40,
          50
        ];

        $sw = new Stopwatch();
        $sw->pause();
        $this->assertEquals(10, $sw->nanoseconds);
        usleep(100);
        $this->assertEquals(10, $sw->nanoseconds);
    }

    public function testResume(): void {
        $this->values = [
            10, // start
            20, // paus
            30, // resume (+10 to start)
            40, // Check (start 40 - 20)
        ];

        $sw = new Stopwatch();
        $sw->pause();
        $this->assertEquals(10, $sw->nanoseconds);
        usleep(100);
        $this->assertEquals(10, $sw->nanoseconds);
        $sw->resume();
        $this->assertEquals(20, $sw->nanoseconds);
    }
}
