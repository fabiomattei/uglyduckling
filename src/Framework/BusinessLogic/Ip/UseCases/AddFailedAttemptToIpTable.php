<?php

namespace Fabiom\UglyDuckling\Framework\BusinessLogic\Ip\UseCases;

class AddFailedAttemptToIpTable {

    const MAX_FAILED_ATTEMPTS = 20;

    private function isInternalAddress( string $ip ): bool {
        return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false;
    }

    public function performAction( $remote_address, $username, $ipDao, $deactivatedUserDao ) {
        if ( $this->isInternalAddress( $remote_address ) ) {
            return;
        }
        $ip = $ipDao->getByIpAddress( $remote_address );
        if ( $ip->ip_id == 0 ) {
            $ipDao->insertip( $remote_address );
        } else {
            if ( $ip->ip_failed_attepts <= static::MAX_FAILED_ATTEMPTS ) {
                $ipDao->incrementIpCounting( $ip->ip_id );
            } else {
                $ipDao->delayIp( $ip->ip_id );
                $deactivatedUserDao->insertUser( $username );
            }
        }
    }

}
