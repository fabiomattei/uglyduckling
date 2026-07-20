<?php

namespace Fabiom\UglyDuckling\Framework\Queue;

/**
 * Base class for a single background job.
 *
 * A job holds its own data as properties (set via its constructor) and
 * knows how to turn that data into a JSON-safe array and back, so the
 * queue can persist it to the database and reconstruct it later.
 */
abstract class QueueJob {

    /**
     * Executes the job.
     */
    abstract public function handle(): void;

    /**
     * Returns the job's data as a JSON-encodable array.
     */
    abstract public function toPayload(): array;

    /**
     * Rebuilds the job from the array previously returned by toPayload().
     */
    abstract public static function fromPayload( array $payload ): static;

}
