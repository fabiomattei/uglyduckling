<?php

namespace Fabiom\UglyDuckling\Framework\BusinessLogic\Ip\UseCases;

use Fabiom\UglyDuckling\Framework\BusinessLogic\Ip\Daos\IpDao;

class AddDelayToIp {
	
	public function performAction( $remote_address, IpDao $ipDao ) {
		$ip = $ipDao->getByIpAddress( $remote_address );
		if ( $ip->ip_id == 0 ) { 
			// I need to insert the ip in the table
			$ipDao->insertip( $remote_address, 5 );
		} else {
			// I need to delay the ip in the table
            $ipDao->delayIp( $ip->ip_id );
		}
	}
	
}
