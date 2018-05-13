<?PHP

use core\paperworks\BasicOffice;

/**
* Description
*/
class Operator extends BasicOffice {
	
    public $class_name = 'Operator';
    public $slug       = 'operator'; /* corresponds to file name */
    public $human_name = 'Operator';
	
	/**
	 * This is a list of chpater this office has access to
	 */
    public $active_chapters = array(
        'expense' => 'Expense',
        'report'  => 'Report',
    );
	
}
