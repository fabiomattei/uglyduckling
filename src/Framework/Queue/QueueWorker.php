<?php

namespace Fabiom\UglyDuckling\Framework\Queue;

/**
 * Reserves jobs from a QueueRepository and runs them, releasing failed jobs
 * for retry with backoff until their attempts are exhausted.
 */
class QueueWorker {

    private QueueRepository $repository;

    public function __construct( QueueRepository $repository ) {
        $this->repository = $repository;
    }

    /**
     * Reserves and runs a single job from the given queue.
     *
     * @return bool true if a job was reserved and run (whether it succeeded,
     *              was released for retry, or was marked failed), false if
     *              the queue had nothing available.
     */
    public function processNextJob( string $queue = 'default' ): bool {
        $reserved = $this->repository->reserveNext( $queue );
        if ( $reserved === null ) {
            return false;
        }

        $jobClass = $reserved->job_class;
        $payload = json_decode( $reserved->payload, true );

        try {
            /** @var QueueJob $job */
            $job = $jobClass::fromPayload( $payload );
            $job->handle();

            $this->repository->markCompleted( $reserved->id );
        } catch ( \Throwable $e ) {
            $attemptsAfterThisFailure = (int) $reserved->attempts + 1;

            if ( $attemptsAfterThisFailure >= (int) $reserved->max_attempts ) {
                $this->repository->markFailed( $reserved->id, $e->getMessage() );
            } else {
                // Exponential backoff between retries, capped at 60 seconds.
                $delaySeconds = min( 60, 2 ** $attemptsAfterThisFailure );
                $this->repository->release( $reserved->id, $delaySeconds );
            }
        }

        return true;
    }

    /**
     * Runs jobs from the given queue until $maxJobs have been processed, or
     * indefinitely (polling every $sleepSeconds while the queue is empty) if
     * $maxJobs is null.
     */
    public function work( string $queue = 'default', int $sleepSeconds = 3, ?int $maxJobs = null ): void {
        $processed = 0;

        while ( $maxJobs === null || $processed < $maxJobs ) {
            $worked = $this->processNextJob( $queue );

            if ( $worked ) {
                $processed++;
                continue;
            }

            if ( $maxJobs !== null ) {
                break;
            }

            sleep( $sleepSeconds );
        }
    }

}
