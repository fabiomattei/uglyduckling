<?php

use Fabiom\UglyDuckling\Framework\Queue\QueueRepository;

require_once __DIR__ . '/fixtures/QueueTestJobs.php';

/**
 *  Testing the QueueRepository class
 */
class QueueRepositoryTest extends PHPUnit\Framework\TestCase {

    private PDO $pdo;
    private QueueRepository $repository;

    protected function setUp(): void {
        $this->pdo = new PDO( 'sqlite::memory:' );
        $this->pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

        $this->repository = new QueueRepository( $this->pdo );
        $this->repository->ensureTableExists();
    }

    public function testPushRejectsAJobClassThatDoesNotExtendQueueJob() {
        $this->expectException( InvalidArgumentException::class );

        $this->repository->push( 'default', stdClass::class, [] );
    }

    public function testPushedJobIsImmediatelyReservable() {
        $this->repository->push( 'default', SucceedingQueueTestJob::class, [ 'message' => 'hi' ] );

        $reserved = $this->repository->reserveNext( 'default' );

        $this->assertNotNull( $reserved );
        $this->assertEquals( SucceedingQueueTestJob::class, $reserved->job_class );
        $this->assertEquals( [ 'message' => 'hi' ], json_decode( $reserved->payload, true ) );
    }

    public function testReserveNextOnlyReturnsJobsFromTheRequestedQueue() {
        $this->repository->push( 'emails', SucceedingQueueTestJob::class, [ 'message' => 'hi' ] );

        $this->assertNull( $this->repository->reserveNext( 'default' ) );
        $this->assertNotNull( $this->repository->reserveNext( 'emails' ) );
    }

    public function testReserveNextDoesNotReturnAJobAgainOnceReserved() {
        $this->repository->push( 'default', SucceedingQueueTestJob::class, [ 'message' => 'hi' ] );

        $this->repository->reserveNext( 'default' );

        $this->assertNull( $this->repository->reserveNext( 'default' ) );
    }

    public function testReserveNextDoesNotReturnADelayedJobBeforeItsAvailableTime() {
        $this->repository->push( 'default', SucceedingQueueTestJob::class, [ 'message' => 'hi' ], delaySeconds: 3600 );

        $this->assertNull( $this->repository->reserveNext( 'default' ) );
    }

    public function testMarkCompletedRemovesTheJob() {
        $this->repository->push( 'default', SucceedingQueueTestJob::class, [ 'message' => 'hi' ] );
        $reserved = $this->repository->reserveNext( 'default' );

        $this->repository->markCompleted( $reserved->id );

        $this->assertNull( $this->fetchRow( $reserved->id ) );
    }

    public function testReleaseIncrementsAttemptsAndClearsReservation() {
        $this->repository->push( 'default', SucceedingQueueTestJob::class, [ 'message' => 'hi' ] );
        $reserved = $this->repository->reserveNext( 'default' );

        $this->repository->release( $reserved->id, 3600 );

        $row = $this->fetchRow( $reserved->id );
        $this->assertEquals( 1, $row->attempts );
        $this->assertNull( $row->reserved_at );
        $this->assertGreaterThan( date( 'Y-m-d H:i:s' ), $row->available_at );
        $this->assertNull( $this->repository->reserveNext( 'default' ) );
    }

    public function testMarkFailedRecordsTheErrorAndClearsReservation() {
        $this->repository->push( 'default', SucceedingQueueTestJob::class, [ 'message' => 'hi' ] );
        $reserved = $this->repository->reserveNext( 'default' );

        $this->repository->markFailed( $reserved->id, 'boom' );

        $row = $this->fetchRow( $reserved->id );
        $this->assertEquals( 'boom', $row->error );
        $this->assertNotNull( $row->failed_at );
        $this->assertNull( $row->reserved_at );
    }

    public function testCountPendingAndCountFailed() {
        $this->repository->push( 'default', SucceedingQueueTestJob::class, [ 'message' => 'a' ] );
        $this->repository->push( 'default', SucceedingQueueTestJob::class, [ 'message' => 'b' ] );
        $failing = $this->repository->push( 'default', SucceedingQueueTestJob::class, [ 'message' => 'c' ] );
        $this->repository->reserveNext( 'default' );
        $this->repository->reserveNext( 'default' );
        $reservedForFailure = $this->repository->reserveNext( 'default' );
        $this->repository->markFailed( $reservedForFailure->id, 'boom' );

        $this->assertEquals( 2, $this->repository->countPending( 'default' ) );
        $this->assertEquals( 1, $this->repository->countFailed( 'default' ) );
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
