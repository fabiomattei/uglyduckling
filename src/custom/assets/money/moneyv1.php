<?php

namespace custom\chapters\expense\forms\expenserequest;

use core\paperworks\BasicAsset;

/**
 * Created by Fabio Mattei <burattino@gmail.com>
 * Date: 30/03/2017
 * Time: 23:10
 */
class MoneyV1 extends BasicAsset {

    public $slug        = 'moneyv1';
    public $base_slug   = 'money';
    public $family_slug = 'money';
    public $human_name  = 'Money V1';

    public $fields = array(
        'description' => array (
            'type'       => 'string',
            'validation' => 'max_len,2500',
            'index'      => '',
            'title'      => true,
            'width'      => 'col-sm-2'
        ),
        'amount' => array (
            'type'       => 'currency',
            'validation' => 'required|numeric',
            'index'      => 'fi_dind1',
            'title'      => true,
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
                'width' => 'col-sm-12'
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
                'width' => 'col-sm-12'
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
		)
	);

}
