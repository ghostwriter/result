<?php

declare(strict_types=1);

namespace Tests\Unit;

use Ghostwriter\Result\Exception\ResultException;
use Ghostwriter\Result\Failure;
use Ghostwriter\Result\Interface\FailureInterface;
use Ghostwriter\Result\Result;
use Ghostwriter\Result\Success;
use PHPUnit\Framework\Attributes\CoversClass;
use RuntimeException;
use Throwable;

#[CoversClass(Failure::class)]
#[CoversClass(Result::class)]
#[CoversClass(Success::class)]
final class SuccessTest extends AbstractTestCase
{
    /**
     * @throws Throwable
     */
    public function testAnd(): void
    {
        $success = Success::new(true);

        self::assertSame($success, $this->success->and($success));
        self::assertTrue($success->isSuccess());
        self::assertTrue($success->get());
    }

    /**
     * @throws Throwable
     */
    public function testAndThen(): void
    {
        $result = Success::new('foo')
            ->andThen(static fn (string $word): string => $word . 'bar');

        self::assertTrue($result->isSuccess());
        self::assertSame('foobar', $result->get());
    }

    /**
     * @throws Throwable
     */
    public function testAndThenThrow(): void
    {
        $result = Success::new('foo')
            ->andThen(static function (): mixed {
                throw new RuntimeException(__METHOD__);
            });

        self::assertTrue($result->isFailure());
        self::assertInstanceOf(FailureInterface::class, $result);
    }

    /**
     * @throws Throwable
     */
    public function testConstruct(): void
    {
        $success = Success::new('foo');
        self::assertTrue($success->isSuccess());
    }

    /**
     * @throws Throwable
     */
    public function testExpect(): void
    {
        $runtimeException = new RuntimeException('oops!');
        self::assertSame(self::MESSAGE, $this->success->expect($runtimeException));
    }

    /**
     * @throws Throwable
     */
    public function testExpectError(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('oops!');

        $this->success->expectError(new RuntimeException('oops!'));
    }

    /**
     * @throws Throwable
     */
    public function testGet(): void
    {
        self::assertSame(self::MESSAGE, $this->success->get());
    }

    /**
     * @throws Throwable
     */
    public function testGetError(): void
    {
        $this->expectException(ResultException::class);
        $this->expectExceptionMessage('getError');

        $this->success->getError();
    }

    /**
     * @throws Throwable
     */
    public function testGetOr(): void
    {
        self::assertSame(self::MESSAGE, $this->success->getOr(false));
    }

    /**
     * @throws Throwable
     */
    public function testGetOrElse(): void
    {
        $fn = static fn (): bool => false;
        self::assertSame(self::MESSAGE, $this->success->getOrElse($fn));
    }

    /**
     * @throws Throwable
     */
    public function testIsError(): void
    {
        self::assertFalse($this->success->isFailure());
    }

    /**
     * @throws Throwable
     */
    public function testIsFailure(): void
    {
        self::assertFalse($this->success->isFailure());
    }

    /**
     * @throws Throwable
     */
    public function testIsSuccess(): void
    {
        self::assertTrue($this->success->isSuccess());
    }

    /**
     * @throws Throwable
     */
    public function testMap(): void
    {
        $result = $this->success->map(static fn (mixed $x): mixed => $x);

        self::assertTrue($result->isSuccess());
        self::assertNotSame($this->success, $result);

        $mapped = $this->success->map(static fn (mixed $x): string => $x . self::MESSAGE);

        self::assertTrue($mapped->isSuccess());
        self::assertNotSame($this->success, $mapped);
        self::assertSame(self::MESSAGE . self::MESSAGE, $mapped->get());
    }

    /**
     * @throws Throwable
     */
    public function testMapError(): void
    {
        $result = $this->success->mapError(static fn (mixed $x): mixed => $x);

        self::assertTrue($result->isSuccess());
        self::assertSame($this->success, $result);
    }

    /**
     * @throws Throwable
     */
    public function testOr(): void
    {
        $success = Success::new('foobar');
        $result = $this->success->or($success);

        self::assertTrue($result->isSuccess());
        self::assertSame(self::MESSAGE, $result->get());
    }

    /**
     * @throws Throwable
     */
    public function testOrElse(): void
    {
        $result = $this->success->orElse(static function (): never {
            self::fail('Should not be called!');
        });

        self::assertTrue($result->isSuccess());
        self::assertSame(self::MESSAGE, $result->get());
    }
}
