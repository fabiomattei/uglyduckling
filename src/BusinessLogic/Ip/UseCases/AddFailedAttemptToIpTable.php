<?php

namespace Fabiom\UglyDuckling\BusinessLogic\Ip\UseCases;

class AddFailedAttemptToIpTable {

    public function performAction( $remote_address, $username, $ipDao, $deactivatedUserDao ) {
        $ip = $ipDao->getByIpAddress( $remote_address );
        if ( $ip->ip_id == 0 ) {
            // I need to insert the ip in the table
            $ipDao->insertip( $remote_address );
        } else {
            if ( $ip->ip_failed_attepts <= 5 ) {
                $ipDao->incrementIpCounting( $ip->ip_id );
            } else {
                // I need to delay the ip in the table
                $ipDao->delayIp( $ip->ip_id );
                $deactivatedUserDao->insertUser( $username );
            }
        }
    }

}
