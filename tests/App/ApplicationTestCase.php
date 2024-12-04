<?php

namespace App;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class ApplicationTestCase extends TestCase
{
    public function initializeLoggerMock(): MockObject|LoggerInterface
    {
        return $this->createMock(LoggerInterface::class);
    }
}