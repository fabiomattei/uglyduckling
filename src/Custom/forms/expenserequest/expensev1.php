<?php

namespace custom\forms\expenserequest;

use core\paperworks\BasicPaper;

/**
 * Created by Fabio Mattei <burattino@gmail.com>
 * Date: 30/03/2017
 * Time: 23:10
 */
class ExpenseV1 extends BasicPaper {

    public $slug        = 'expensev1';
    public $base_slug   = 'expense';
    public $family_slug = 'expense';
    public $human_name  = 'Expense V1';

    public $fields = array(
        'description' => array (
            'type'       => 'string',
            'validation' => 'max_len,2500',
            'index'      => 'fi_tind1',
            'title'      => true,
            'width'      => 'col-sm-2' // TODO take this away and put here just a number
        ),
        'amount' => array (
            'type'       => 'currency',
            'validation' => 'required|numeric',
            'index'      => 'fi_dind1',
            'title'      => true,
            'width'      => 'col-sm-2'
        ),
        'duedate' => array (
            'type'       => 'date',
            'validation' => 'max_len,10',
            'index'      => '',
            'title'      => false,
            'width'      => 'col-sm-2'
        ),
    );

    public $form = array(
        1 => array(
            'description' => array(
                'type'  => 'textarea',
                'label' => 'Description:',
                'width' => 'col-sm-12'
            )
        ),
        2 => array(
            'amount' => array(
                'type'  => 'currency',
                'label' => 'Amount (&euro;):',
                'width' => 'col-sm-6'
            ),
            'duedate' => array(
                'type'  => 'date',
                'label' => 'Due date:',
                'width' => 'col-sm-6'
            )
        )
    );
	
	public $info = array(
        1 => array(
            'description' => array(
                'type'  => 'textarea',
                'label' => 'Description:',
                'width' => 'col-sm-12'
            )
        ),
        2 => array(
            'amount' => array(
                'type'  => 'currency',
                'label' => 'Amount (&euro;):',
                'width' => 'col-sm-6'
            ),
            'duedate' => array(
                'type'  => 'date',
                'label' => 'Due date:',
                'width' => 'col-sm-6'
            )
        )
	);
	
	public $summary = array(
		1 => array(
			'description' => array(
                'type'  => 'textarea',
                'label' => 'Description:',
			)
		),
		2 => array(
			'amount' => array(
                'type'  => 'currency',
                'label' => 'Amount (&euro;):',
			)
		),
		3 => array(
			'duedate' => array(
                'type'  => 'date',
                'label' => 'Due date:',
			)
		)
	);

}
