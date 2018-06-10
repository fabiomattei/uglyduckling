<?PHP

namespace custom\chapters\expense;

use custom\organization\Organization;
use core\paperworks\BasicChapter;
use custom\processes\expenseprocess\ExpenseProcessV1;
use custom\forms\expenserequest\ExpenseV1;
use custom\forms\note\NoteV1;
use core\paperworks\EmptyPaper;
use core\paperworks\BasicReport;
use custom\reports\expensereport\ExpenseReportV1;

/**
* Description
*/
class ExpenseChapter extends BasicChapter {
	
    public $class_name = 'Expense';
    public $slug       = 'expense'; /* corresponds to file name */
    public $human_name = 'Expense';
	
	const EXPENSE_BASE_SLUG = 'expense';
	const PAYMENT_BASE_SLUG = 'payment';
	
	/**
	 * Return the process object
	 */
	public function get_process( $process_slug='' ) {
		if ( $process_slug == 'expenseprocessv1' ) {
			return new ExpenseProcessV1();
		}
		return false;
	}

	/**
	 * Return the form object
	 */
	public function get_form( $form_slug = '' ) {
		if ( $form_slug == 'expensev1' ) {
			return new ExpenseV1();
		}
        if ( $form_slug == 'notev1' ) {
            return new NoteV1();
        }
		return new EmptyPaper();
	}

    /**
     * Return the report object
     */
    public function get_report( $report_slug='' ) {
        if ( $report_slug == 'expensereportv1' ) {
            return new ExpenseReportV1();
        }
        return new BasicReport();
    }

	public function get_active_processes_by_office( $office_id = 0 ) {
		if ( $office_id == Organization::MANAGER_OFFICE_ID ) {
			return array(new ExpenseProcessV1);
		}
		return array();
	}

    public function get_active_reports_by_office( $office_id = 0 ) {
        if ( $office_id == Organization::MANAGER_OFFICE_ID ) {
            return array(new ExpenseReportV1);
        }
        return array();
    }

}
