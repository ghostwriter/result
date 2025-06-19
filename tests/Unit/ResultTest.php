<?php

declare(strict_types=1);

namespace Tests\Unit;

use Ghostwriter\Result\Failure;
use Ghostwriter\Result\Interface\SuccessInterface;
use Ghostwriter\Result\Result;
use Ghostwriter\Result\Success;
use PHPUnit\Framework\Attributes\CoversClass;
use Throwable;

use function str_repeat;

#[CoversClass(Failure::class)]
#[CoversClass(Result::class)]
#[CoversClass(Success::class)]
final class ResultTest extends AbstractTestCase
{
    /**
     * @throws Throwable
     */
    public function testFailureMapError(): void
    {
        $result = $this->failure->mapError(static fn (mixed $x): mixed => $x);

        self::assertNotSame($this->failure, $result);

        self::assertTrue($result->isFailure());

        self::assertSame($this->runtimeException, $result->getError());
    }

    /**
     * @throws Throwable
     */
    public function testFailureOrElse(): void
    {
        $result = $this->failure->orElse(fn (): SuccessInterface => $this->success);

        self::assertTrue($result->isSuccess());

        self::assertSame($this->success, $result);

        self::assertSame($this->success->get(), $result->get());
    }

    /**
     * @throws Throwable
     */
    public function testResult(): void
    {
        $result = Result::new(self::MESSAGE);

        self::assertTrue($result->isSuccess());
        self::assertSame(self::MESSAGE, $result->get());

        $result1 = Result::new($result);

        self::assertTrue($result1->isSuccess());
        self::assertSame($result, $result1);
        self::assertSame(self::MESSAGE, $result1->get());

        $result = Result::new($this->runtimeException);
        self::assertTrue($result->isFailure());
    }

    /**
     * @throws Throwable
     */
    public function testSuccessAndThen(): void
    {
        $result = $this->success->andThen(static fn (string $value): string => $value . self::MESSAGE);

        self::assertTrue($result->isSuccess());

        self::assertSame(self::MESSAGE . self::MESSAGE, $result->get());
    }

    /**
     * @throws Throwable
     */
    public function testSuccessMap(): void
    {
        $result = $this->success->map(static fn (mixed $x): mixed => $x);

        self::assertTrue($result->isSuccess());
        self::assertNotSame($this->success, $result);

        $mapped = $this->success->map(static fn (mixed $x): string => str_repeat($x, 2));

        self::assertTrue($mapped->isSuccess());
        self::assertNotSame($this->success, $mapped);
        self::assertSame(self::MESSAGE . self::MESSAGE, $mapped->get());
    }
}
