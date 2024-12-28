<?php

namespace Fabiom\UglyDuckling\Framework\BusinessLogic\Ip\UseCases;

class IpIsNotBlocked {
	
	public function performAction( $remote_address, $ipDao ) {
		return !$ipDao->checkIfIpIsBlocked( $remote_address );
	}
	
}
