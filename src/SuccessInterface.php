<?php

declare(strict_types=1);

namespace Ghostwriter\Result;

/**
 * @template TValue
 *
 * @extends ResultInterface<TValue>
 */
interface SuccessInterface extends ResultInterface
{
    /**
     * Create a new success value.
     *
     * @template TSuccess
     *
     * @param TSuccess $value
     *
     * @return self<TSuccess>
     */
    public static function create(mixed $value): self;
}
