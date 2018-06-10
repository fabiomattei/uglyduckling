<?php

require_once( 'core/paperworks/basicprocess.php' );

use custom\organization\Organization;
	
/**
* This class shows how an expense process works
*/
class FerieProcessV1 extends BasicProcess {
	
	const HUMAN_NAME  = 'Processo di richiesta ferie';
	const SLUG        = 'ferie';
    const BASE_SLUG   = 'ferie';
    const FAMILY_SLUG = 'ferie';
	const VALID_FROM  = "2017-01-01";
	const VALID_TO    = "2017-01-01";
	
	const STEPS = array(
		1 => array(
				'status' => 'Make request',
				'access' => array(
					array( 
						'site' => 'all',
						'office' => Organization::ADMINISTRATION_OFFICE_ID,
						'user' => 'all'
					),
					array( 
						'site' => 'all',
						'office' => Organization::OPERATOR_OFFICE_ID,
						'user' => 'all'
					)
				),
				'next' => array ( array( 'nextstep' => 2, 'label' => 'Make a request', 'form' => 'ferierequestv1') ),
				'required' => array(),
				'appendices' => array(),
				'time_limit' => ''
			),
		2 => array(
				'status' => 'Request received',
				'access' => array(
					array( 
						'site' => 'step:1',
						'office' => Organization::MANAGER_OFFICE_ID,
						'user' => 'all'
					)
				),
				'next' => array ( 
					array( 'nextstep' => 3, 'label' => 'Approve', 'form' => 'notev1' ),
					array( 'nextstep' => 4, 'label' => 'Do not approve', 'form' => 'notev1' ),
				),
				'required' => array(),
				'appendices' => array(),
				'time_limit' => '2 weeks',
			),
		3 => array( 
				'status' => 'Request approved',
				'access' => array(
					array( 
						'site' => 'step:1',
						'office' => Organization::MANAGER_OFFICE_ID,
						'user' => 'all'
					),
					array( 
						'site' => 'step:1',
						'office' => Organization::ADMINISTRATION_OFFICE_ID,
						'user' => 'step:1'
					),
					array( 
						'site' => 'step:1',
						'office' => Organization::OPERATOR_OFFICE_ID,
						'user' => 'step:1'
					)
				),
				'next' => array (),
				'required' => array(),
				'appendices' => array(),
				'time_limit' => '',
			),
		4 => array( 
				'status' => 'Request NOT approved',
				'access' => array(
					array( 
						'site' => 'step:1',
						'office' => Organization::MANAGER_OFFICE_ID,
						'user' => 'all'
					),
					array( 
						'site' => 'step:1',
						'office' => Organization::ADMINISTRATION_OFFICE_ID,
						'user' => 'step:1'
					),
					array( 
						'site' => 'step:1',
						'office' => Organization::OPERATOR_OFFICE_ID,
						'user' => 'step:1'
					)
				),
				'next' => array (),
				'required' => array(),
				'appendices' => array(),
				'time_limit' => '',
			)
	);
	
}
