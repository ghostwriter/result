<?php

declare(strict_types=1);

namespace Tests\Unit;

use Ghostwriter\Result\Interface\FailureInterface;
use Ghostwriter\Result\Interface\SuccessInterface;
use Ghostwriter\Result\Result;
use Override;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Throwable;

abstract class AbstractTestCase extends TestCase
{
    protected const string MESSAGE = '#BlackLivesMatter';

    protected FailureInterface $failure;

    protected RuntimeException $runtimeException;

    protected SuccessInterface $success;

    /**
     * @throws Throwable
     */
    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->runtimeException = new RuntimeException(self::MESSAGE);

        $this->failure = Result::failure($this->runtimeException);

        $this->success = Result::success(self::MESSAGE);
    }
}
