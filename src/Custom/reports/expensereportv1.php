<?php

namespace custom\reports\expensereport;

use core\paperworks\BasicReport;

/**
* This class shows how an expense process works
*/
class ExpenseReportV1 extends BasicReport {
	
	/**
	 * L'accesso ad uno step può essere gestito in modo statico per [sito, ufficio, user]
	 * 
	 * ogni step del processo quando viene acceso salva tra i suoi dati [sito, ufficio, user] 
	 * in modo da poterci fare riferimento successivamente in modo dinamico
	 */

    public $chapter_slug = 'expense';
    public $base_slug    = 'expensereport';
    public $slug         = 'expensereportv1';
    public $human_name   = 'Spese report';

    public $queryfields = array(
        'startdate' => array(
                'type'       => 'date',
                'validation' => 'required|max_len,10',
                'step'       => 1,
                'onfield'    => 'fi_created',
                'operator'   => '>='
            ),
        'enddate' => array(
                'type'       => 'date',
                'validation' => 'required|max_len,10',
                'step'       => 1,
                'onfield'    => 'fi_created',
                'operator'   => '<='
            )
    );

	public $queryform = array(
        1 => array(
            'startdate' => array(
                'type'  => 'date',
                'label' => 'Start date:',
                'width' => 'col-sm-6'
            ),
            'enddate' => array(
                'type'  => 'date',
                'label' => 'End date:',
                'width' => 'col-sm-6'
            )
        )
	);
	
	public $conditions = array(
	    array(
	        'step' => 3,
            'onfield' => 'fi_id',
            'operator' => '<>',
            'value' => 0
        )
	);
	
	public $fieldsresult = array(
		1 => array(
			'step' => 1,
			'forminstance' => 'expensev1',
			'field' => 'amount'
		),
		2 => array(
			'step' => 1,
			'forminstance' => 'expensev1',
			'field' => 'amount'
		),
	);
	
	public $aggregation = array(
		1 => array(
			'lable' => 'Ammontare totale',
			'operator' => 'sum',
			'step' => 1,
			'forminstance' => 'expensev1',
			'field' => 'amount'
		)
	);

}
