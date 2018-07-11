<?PHP

use core\paperworks\BasicOffice;

/**
* Description
*/
class Admin extends BasicOffice {
	
    public $class_name = 'Admin';
    public $slug       = 'admin'; /* corresponds to file name */
    public $human_name = 'Admin';
	
	/**
	 * The menu allows the administrator to set what each office can see
	 * the index of the array is the $family_slug defined in the papers
	 * 
	 */
    public $active_chapters = array(
        'expense' => 'Expense',
        'report'  => 'Report',
    );
	
}
