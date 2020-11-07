<?php

namespace Fabiom\UglyDuckling\BusinessLogic\Ip\UseCases;

class IpIsNotBlocked {
	
	public function performAction( $remote_address, $ipDao ) {
		return !$ipDao->checkIfIpIsBlocked( $remote_address );
	}
	
}
