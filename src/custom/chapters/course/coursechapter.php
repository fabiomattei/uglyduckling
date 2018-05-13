<?PHP

require_once( 'core/paperworks/basicchapter.php' );

/**
* Description
*/
class CourseChapter extends BasicChapter {
	
    public $class_name = 'Course';
    public $slug       = 'course'; /* corresponds to file name */
    public $human_name = 'Course';
	
	const REQUEST_BASE_SLUG = 'request';
	const ACCEPTANCE_BASE_SLUG = 'acceptance';
	const DELIVERY_BASE_SLUG = 'delivery';

	function __construct() {
    }

    public function get_active_processes_by_office( $process_name='', $office_id = 0 ) {
		return array();
	}
	
	/**
	 * The menu allows the administrator to set what each office can see
	 * the index of the array is the $family_slug defined in the papers
	 * 
	 */

	/*
    public $active_papers = array(
        Course::REQUEST_BASE_SLUG => 'request',
        Course::ACCEPTANCE_BASE_SLUG => 'acceptance',
		Course::DELIVERY_BASE_SLUG => 'delivery',
    );
	
	public function get_papers_slugs_for_office( $office_id ) {
		if ( $office_id == Organization::ADM_OFFICE_ID ) {
			return array( Course::REQUEST_BASE_SLUG, Course::ACCEPTANCE_BASE_SLUG, Course::DELIVERY_BASE_SLUG );
		}
		if ( $office_id == Organization::OPERATOR_OFFICE_ID ) {
			return array( Course::REQUEST_BASE_SLUG, Course::ACCEPTANCE_BASE_SLUG, Course::DELIVERY_BASE_SLUG );
		}
		if ( $office_id == Organization::MANAGER_OFFICE_ID ) {
			return array( Course::REQUEST_BASE_SLUG, Course::ACCEPTANCE_BASE_SLUG, Course::DELIVERY_BASE_SLUG );
		}
		if ( $office_id == Organization::ADMINISTRATION_OFFICE_ID ) {
			return array( Course::REQUEST_BASE_SLUG, Course::ACCEPTANCE_BASE_SLUG, Course::DELIVERY_BASE_SLUG );
		}
		if ( $office_id == Organization::ADMIN_ID ) {
			return array( Course::REQUEST_BASE_SLUG, Course::ACCEPTANCE_BASE_SLUG, Course::DELIVERY_BASE_SLUG );
		}
		return array();
	}
	
	function get_papers_families( $office_id ) {
		return array( Course::REQUEST_BASE_SLUG => 'Request', Course::ACCEPTANCE_BASE_SLUG => 'Acceptance', Course::DELIVERY_BASE_SLUG => 'Delivery' );
	}

	function get_create_paper_buttons( $office_id ) {
		require_once( 'custom/papers/request/requestv1.php' );
		require_once( 'custom/papers/acceptance/acceptancev1.php' );
		require_once( 'custom/papers/delivery/deliveryv1.php' );
		return array( RequestV1::SLUG => 'new '.RequestV1::HUMAN_NAME,
			AcceptanceV1::SLUG => 'new '.AcceptanceV1::HUMAN_NAME, 
			DeliveryV1::SLUG => 'new '.DeliveryV1::HUMAN_NAME,
		);
	}
	
	public function load_all_base_slug( $base_slug ) {
		if ( $base_slug == Course::REQUEST_BASE_SLUG ) {
			require_once( 'custom/papers/request/requestv1.php' );
			return array( RequestV1::SLUG => new RequestV1() );
		} else if ( $base_slug == Course::ACCEPTANCE_BASE_SLUG ) {
			require_once( 'custom/papers/acceptance/acceptancev1.php' );
			return array( AcceptanceV1::SLUG => new AcceptanceV1() );
		} else if ( $base_slug == Course::DELIVERY_BASE_SLUG ) {
			require_once( 'custom/papers/delivery/deliveryv1.php' );
			return array( DeliveryV1::SLUG => new DeliveryV1() );
		} else {
			return array();
		}
	}

	*/
	
}
