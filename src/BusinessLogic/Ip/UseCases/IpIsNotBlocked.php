<?php

namespace Fabiom\UglyDuckling\BusinessLogic\Ip\UseCases;

class IpIsNotBlocked {
	
	public function performAction( $remote_address, $ipDao ) {
		$ips = array('80.211.55.253','212.237.42.12','212.237.42.215','212.237.42.207','185.41.215.50','217.61.26.74','217.61.26.75','217.61.26.79','217.61.26.82','217.61.26.84');
		if (in_array( $remote_address, $ips )) {
		    return true;
		}
		return !$ipDao->checkIfIpIsBlocker( $remote_address );
	}
	
}



