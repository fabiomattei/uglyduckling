<?php

use Fabiom\UglyDuckling\Framework\Queue\QueueRepository;
use Fabiom\UglyDuckling\Framework\Queue\QueueWorker;

require_once __DIR__ . '/fixtures/QueueTestJobs.php';

/**
 *  Testing the QueueWorker class
 */
class QueueWorkerTest extends PHPUnit\Framework\TestCase {

    private PDO $pdo;
    private QueueRepository $repository;
    private QueueWorker $worker;

    protected function setUp(): void {
        $this->pdo = new PDO( 'sqlite::memory:' );
        $this->pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

        $this->repository = new QueueRepository( $this->pdo );
        $this->repository->ensureTableExists();
        $this->worker = new QueueWorker( $this->repository );

        SucceedingQueueTestJob::$handled = [];
        FailingQueueTestJob::$handleCalls = 0;
    }

    public function testProcessNextJobReturnsFalseWhenQueueIsEmpty() {
        $this->assertFalse( $this->worker->processNextJob( 'default' ) );
    }

    public function testProcessNextJobRunsAndCompletesASucceedingJob() {
        $this->repository->push( 'default', SucceedingQueueTestJob::class, [ 'message' => 'hello' ] );

        $this->assertTrue( $this->worker->processNextJob( 'default' ) );

        $this->assertEquals( [ 'hello' ], SucceedingQueueTestJob::$handled );
        $this->assertEquals( 0, $this->repository->countPending( 'default' ) );
    }

    public function testProcessNextJobReleasesAFailingJobForRetryWhenAttemptsRemain() {
        $id = $this->repository->push( 'default', FailingQueueTestJob::class, [ 'message' => 'boom' ], maxAttempts: 3 );

        $this->assertTrue( $this->worker->processNextJob( 'default' ) );

        $this->assertEquals( 1, FailingQueueTestJob::$handleCalls );
        $this->assertEquals( 1, $this->repository->countPending( 'default' ) );
        $this->assertEquals( 0, $this->repository->countFailed( 'default' ) );

        $row = $this->fetchRow( $id );
        $this->assertEquals( 1, $row->attempts );
        $this->assertNull( $row->reserved_at );
    }

    public function testProcessNextJobMarksAJobFailedOnceAttemptsAreExhausted() {
        $id = $this->repository->push( 'default', FailingQueueTestJob::class, [ 'message' => 'boom' ], maxAttempts: 1 );

        $this->worker->processNextJob( 'default' );

        $this->assertEquals( 0, $this->repository->countPending( 'default' ) );
        $this->assertEquals( 1, $this->repository->countFailed( 'default' ) );

        $row = $this->fetchRow( $id );
        $this->assertEquals( 'boom', $row->error );
    }

    public function testWorkStopsAfterMaxJobsWhenSet() {
        $this->repository->push( 'default', SucceedingQueueTestJob::class, [ 'message' => 'a' ] );
        $this->repository->push( 'default', SucceedingQueueTestJob::class, [ 'message' => 'b' ] );
        $this->repository->push( 'default', SucceedingQueueTestJob::class, [ 'message' => 'c' ] );

        $this->worker->work( 'default', 0, 2 );

        $this->assertEquals( [ 'a', 'b' ], SucceedingQueueTestJob::$handled );
        $this->assertEquals( 1, $this->repository->countPending( 'default' ) );
    }

    public function testWorkStopsWhenQueueIsEmptyEvenIfMaxJobsNotReached() {
        $this->repository->push( 'default', SucceedingQueueTestJob::class, [ 'message' => 'a' ] );

        $this->worker->work( 'default', 0, 5 );

        $this->assertEquals( [ 'a' ], SucceedingQueueTestJob::$handled );
    }

    private function fetchRow( int $id ): ?stdClass {
        $statement = $this->pdo->prepare( 'SELECT * FROM queue_jobs WHERE id = :id' );
        $statement->bindParam( ':id', $id, PDO::PARAM_INT );
        $statement->execute();
        $statement->setFetchMode( PDO::FETCH_OBJ );

        $row = $statement->fetch();
        return $row === false ? null : $row;
    }

}
