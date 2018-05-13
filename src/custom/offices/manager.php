<?PHP

use core\paperworks\BasicOffice;
use custom\chapters\expense\ExpenseChapter;

/**
* Description
*/
class Manager extends BasicOffice {
	
    public $class_name = 'Manager';
    public $slug       = 'manager'; /* corresponds to file name */
    public $human_name = 'Manager';

    /**
     * Manager constructor.
     * The menu allows the administrator to set what chapter each office can see
     * the index is an array, the term on the left is the chapter slug and folder name
     * the term on the right
     */
    public function __construct() {
        $this->active_chapters[] = new ExpenseChapter();
    }


}
