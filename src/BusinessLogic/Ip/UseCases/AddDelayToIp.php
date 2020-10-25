<?php

namespace Fabiom\UglyDuckling\BusinessLogic\Ip\UseCases;

class AddDelayToIp {
	
	public function performAction( $remote_address, $ipDao ) {
		$ip = $ipDao->getByIpAddress( $remote_address );
		if ( $ip->ip_id == 0 ) { 
			// I need to insert the ip in the table
			$ipDao->insertip( $remote_address );
		} else {
			// I need to delay the ip in the table
            $ipDao->delayIp( $remote_address, $ip->ip_id );
		}
	}
	
}
