<?php

namespace custom\processes\expenseprocess;

use custom\organization\Organization;
use core\paperworks\BasicProcess;

/**
* This class shows how an expense process works
*/
class ExpenseProcessV1 extends BasicProcess {
	
	/**
	 * L'accesso ad uno step può essere gestito in modo statico per [sito, ufficio, user]
	 * 
	 * ogni step del processo quando viene acceso salva tra i suoi dati [sito, ufficio, user] 
	 * in modo da poterci fare riferimento successivamente in modo dinamico
	 */

    public $chapter_slug = 'expense';
    public $base_slug    = 'expense';
    public $slug         = 'expenseprocessv1';
    public $human_name   = 'Processo di richiesta spese';
	public $valid_from   = "2017-01-01";
	public $valid_to     = "2017-01-01";
    public $trigger      = array ( 
                            array( 'nextstep' => 2, 'label' => 'Crea e invia una richiesta di spesa', 'form' => 'expensev1' ),
                            array( 'nextstep' => 1, 'label' => 'Crea una richiesta di spesa', 'form' => 'expensev1' ),
                           );
	
	public $STEPS = array(
        1 => array(
            'status' => 'My requests',
            'description' => 'Requests created',
            'access' => array(
                    Organization::ADMINISTRATION_OFFICE_ID => array( 'site' => 'mine', 'office' => 'mine', 'user' => 'myself' ),
                    Organization::MANAGER_OFFICE_ID => array( 'site' => 'mine', 'office' => 'mine', 'user' => 'myself' ),
                    Organization::OPERATOR_OFFICE_ID => array( 'site' => 'mine', 'office' => 'mine', 'user' => 'myself' )
                ),
            'next' => array ( 
                array( 'nextstep' => 2, 'label' => 'Spedisci', 'form' => 'expensev1', 
                        'presetformfields' => array(
                            array( 'step'  => 1, 'form' => 'expensev1', 'field' => 'description', 'tofield' => 'description'), 
                            array( 'step'  => 1, 'form' => 'expensev1', 'field' => 'amount', 'tofield' => 'amount'), 
                            array( 'step'  => 1, 'form' => 'expensev1', 'field' => 'duedate', 'tofield' => 'duedate'), 
                        ), 
                        'autosetfields' => array() 
                    )
                ),
            'tabletitles' => array()
        ),
		2 => array(
            'status' => 'New Requests just received',
			'description' => 'New Requests just received',
            'access' => array(
                    Organization::MANAGER_OFFICE_ID => array( 'site' => 'all', 'office' => 'all', 'user' => 'all' ),
                    Organization::ADMINISTRATION_OFFICE_ID => array( 'site' => 'all', 'office' => 'all', 'user' => 'all' )
                ),
            'next' => array (
                array( 'nextstep' => 3, 'label' => 'Approve', 'form' => 'notev1', 'type' => 'optional', 'presetformfields' => array(), 'autosetfields' => array() ),
                array( 'nextstep' => 4, 'label' => 'Do not approve', 'form' => 'notev1', 'type' => 'optional', 'presetformfields' => array(), 'autosetfields' => array() ),
            ),
            'required' => array(),
            'appendices' => array(),
            'time_limit' => '2 weeks',
            'tabletitles' => array(
                array( 'title' => 'Description', 'step'  => 2, 'index' => 'fi_tind1' ),
                array( 'title' => 'Amount (&euro;)', 'step'  => 2, 'index' => 'fi_dind1' )
            )
        ),
		3 => array(
            'status' => 'Request approved',
			'description' => 'Request approved',
            'access' => array(
                    Organization::MANAGER_OFFICE_ID => array( 'site' => 'all', 'office' => 'all', 'user' => 'all' ),
                    Organization::ADMINISTRATION_OFFICE_ID => array( 'site' => 'all', 'office' => 'all', 'user' => 'all' ),
                    Organization::OPERATOR_OFFICE_ID => array( 'site' => 'mine', 'office' => 'mine', 'user' => 'myself' ),
                ),
            'next' => array (),
            'required' => array(),
            'appendices' => array(),
            'time_limit' => '',
            'tabletitles' => array(
                array( 'title' => 'Description', 'step'  => 2, 'index' => 'fi_tind1' ),
                array( 'title' => 'Amount (&euro;)', 'step'  => 2, 'index' => 'fi_dind1' )
            ),
            'movements' => array(
                array(
                    'source' => array( 'step' => 1, 'forminstance' => 'expensev1', 'field' => 'amount' ),
                    'destination' => array( 'operator' => 'sum', 'asset' => 'money', 'field' => 'amount' )
                )
            )
        ),
		4 => array(
            'status' => 'Request NOT approved',
            'description' => 'Request NOT approved',
            'access' => array(
                    Organization::MANAGER_OFFICE_ID => array( 'site' => 'all', 'office' => 'all', 'user' => 'all' ),
                    Organization::ADMINISTRATION_OFFICE_ID => array( 'site' => 'all', 'office' => 'all', 'user' => 'all' ),
                    Organization::OPERATOR_OFFICE_ID => array( 'site' => 'mine', 'office' => 'mine', 'user' => 'myself' ),
                ),
            'next' => array (),
            'required' => array(),
            'appendices' => array(),
            'time_limit' => '',
            'tabletitles' => array(
                array( 'title' => 'Description', 'step'  => 2, 'index' => 'fi_tind1' ),
                array( 'title' => 'Amount (&euro;)', 'step'  => 2, 'index' => 'fi_dind1' )
            )
        )
	);
	
}

