<?php

declare(strict_types=1);

namespace Tests\Unit\Exception;

use Ghostwriter\Result\Exception\ResultException;
use Ghostwriter\Result\Failure;
use Ghostwriter\Result\Result;
use Ghostwriter\Result\Success;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\Unit\AbstractTestCase;
use Throwable;

#[CoversClass(Failure::class)]
#[CoversClass(Result::class)]
#[CoversClass(ResultException::class)]
#[CoversClass(Success::class)]
final class ResultExceptionTest extends AbstractTestCase
{
    /**
     * @throws Throwable
     */
    public function testFailureGet(): void
    {
        $this->expectException(ResultException::class);

        $this->failure->get();
    }

    /**
     * @throws Throwable
     */
    public function testSuccessGetError(): void
    {
        $this->expectException(ResultException::class);

        $this->success->getError();
    }
}
