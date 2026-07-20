<?php

use Fabiom\UglyDuckling\Framework\Queue\QueueJob;

/**
 * Fixture job used by QueueWorkerTest: records every message it handles in a
 * static property so tests can assert it actually ran.
 */
class SucceedingQueueTestJob extends QueueJob {

    public static array $handled = [];

    public function __construct( public string $message ) {
    }

    public function handle(): void {
        self::$handled[] = $this->message;
    }

    public function toPayload(): array {
        return [ 'message' => $this->message ];
    }

    public static function fromPayload( array $payload ): static {
        return new static( $payload['message'] );
    }

}

/**
 * Fixture job used by QueueWorkerTest: always throws, so the worker's
 * retry/backoff/fail-permanently behavior can be exercised.
 */
class FailingQueueTestJob extends QueueJob {

    public static int $handleCalls = 0;

    public function __construct( public string $message ) {
    }

    public function handle(): void {
        self::$handleCalls++;
        throw new RuntimeException( $this->message );
    }

    public function toPayload(): array {
        return [ 'message' => $this->message ];
    }

    public static function fromPayload( array $payload ): static {
        return new static( $payload['message'] );
    }

}
