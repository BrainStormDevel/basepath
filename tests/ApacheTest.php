<?php

namespace BrainStormDevel\BasePath\Test;

use PHPUnit\Framework\TestCase;
use BrainStormDevel\BasePath\BasePathDetector;

/**
 * Test.
 */
class ApacheTest extends TestCase
{
    /**
     * @var array<mixed> The server data array contains multiple data types
     */
    private $server;

    /**
     * Set Up.
     */
    protected function setUp(): void
    {
        $this->server = [
            'REQUEST_METHOD' => 'GET',
            'REQUEST_SCHEME' => 'http',
            'HTTP_HOST' => 'localhost',
            'SERVER_PORT' => '80',
            'REQUEST_URI' => '/',
            'SERVER_PROTOCOL' => 'HTTP/1.1',
            'SCRIPT_NAME' => '',
            'REQUEST_TIME_FLOAT' => microtime(true),
            'REQUEST_TIME' => microtime(),
        ];
    }

    /**
     * Create instance.
     *
     * @return BasePathDetector The detector
     */
    private function createInstance(): BasePathDetector
    {
        return new BasePathDetector($this->server, 'apache2handler');
    }

    /**
     * Test.
     */
    public function testDefault(): void
    {
        $detector = $this->createInstance();
        $basePath = $detector->getBasePath();

        static::assertSame('', $basePath);
    }

    /**
     * Test.
     */
    public function testUnknownServer(): void
    {
        $detector = new BasePathDetector($this->server, '');
        $basePath = $detector->getBasePath();

        static::assertSame('', $basePath);
    }

    /**
     * Test.
     */
    public function testSubdirectory(): void
    {
        $this->server['REQUEST_URI'] = '/public';

        $detector = $this->createInstance();
        $basePath = $detector->getBasePath();

        static::assertSame('/public', $basePath);
    }

    /**
     * Test.
     */
    public function testWithoutRequestUri(): void
    {
        unset($this->server['REQUEST_URI']);

        $detector = $this->createInstance();
        $basePath = $detector->getBasePath();

        static::assertSame('', $basePath);
    }
}
