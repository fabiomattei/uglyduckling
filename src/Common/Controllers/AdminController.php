<?php

namespace Fabiom\UglyDuckling\Common\Controllers;

use Fabiom\UglyDuckling\BusinessLogic\Group\Daos\UserGroupDao;
use Fabiom\UglyDuckling\BusinessLogic\Ip\Daos\DeactivatedUserDao;
use Fabiom\UglyDuckling\BusinessLogic\Ip\Daos\IpDao;
use Fabiom\UglyDuckling\BusinessLogic\Ip\Daos\SecurityLogDao;
use Fabiom\UglyDuckling\BusinessLogic\Ip\UseCases\AddDelayToIp;
use Fabiom\UglyDuckling\BusinessLogic\Ip\UseCases\AddEscalationFailedAttemptToSecurityLog;
use Fabiom\UglyDuckling\BusinessLogic\User\Daos\UserDao;
use Fabiom\UglyDuckling\Common\Controllers\Controller;
use Fabiom\UglyDuckling\Common\Json\Parameters\BasicParameterGetter;
use GUMP;

/**
 *
 */
class AdminController extends Controller {

    /**
     * Overwrite parent showPage method in order to add the functionality of loading a json resource.
     */
    public function showPage() {
        $groupDao = new UserGroupDao;
        $groupDao->setDBH( $this->pageStatus->getDbconnection()->getDBH() );
        $groupDao->setLogger( $this->applicationBuilder->getLogger() );
		
        if (
            $_SESSION['group'] == 'administrationgroup' AND
            $groupDao->checkUserHasAccessToGroup( $_SESSION['user_id'], 'administrationgroup' )
        ) {
            $this->applicationBuilder->getJsonloader()->loadIndex();
            parent::showPage();
        } else {
            $securityLogDao = new SecurityLogDao;
            $securityLogDao->setDBH( $this->pageStatus->getDbconnection()->getDBH() );
			$securityLogDao->setLogger( $this->applicationBuilder->getLogger() );
            $ipDao = new IpDao;
            $ipDao->setDBH( $this->pageStatus->getDbconnection()->getDBH() );
			$ipDao->setLogger( $this->applicationBuilder->getLogger() );
            $deactivatedUserDao = new DeactivatedUserDao;
            $deactivatedUserDao->setDBH( $this->pageStatus->getDbconnection()->getDBH() );
			$deactivatedUserDao->setLogger( $this->applicationBuilder->getLogger() );
						
            $addEscalationFailedAttemptToSecurityLog = new AddEscalationFailedAttemptToSecurityLog;
            $addEscalationFailedAttemptToSecurityLog->performAction(
                $this->pageStatus->getServerWrapper()->getRemoteAddress(),
                $_SESSION['username'],
                'FAILED ESCALATION',
                $securityLogDao
            );
            $addDelayToIp = new AddDelayToIp;
            $addDelayToIp->performAction( $this->pageStatus->getServerWrapper()->getRemoteAddress(), $ipDao );

            $userDao = new UserDao();
            $userDao->setDBH( $this->pageStatus->getDbconnection()->getDBH() );
			$userDao->setLogger( $this->applicationBuilder->getLogger() );
            $user = $userDao->getById( $_SESSION['user_id'] );
            $deactivatedUserDao->insertUser( $user->usr_email );

            $this->redirectToPage( $this->applicationBuilder->getRouterContainer()->makeRelativeUrl( 'login' ) );
        }
    }
    
    /**
     * check the parameters sent through the url and check if they are ok from
     * the point of view of the validation rules
     */
    public function check_post_request() {
        if ( isset($this->postParameters['csrftoken']) AND $this->postParameters['csrftoken'] == $this->pageStatus->getSessionWrapper()->getCsrfToken() ) {
            $this->secondGump = new Gump;

            $parametersGetter = BasicParameterGetter::parameterGetterFactory( $this->resource, $this->applicationBuilder );
            $validation_rules = $parametersGetter->getValidationRoules();
            $filter_rules = $parametersGetter->getFiltersRoules();

            if ( count( $validation_rules ) == 0 ) {
                return true;
            } else {
                $parms = $this->secondGump->sanitize( array_merge(
                        is_null($this->postParameters) ? array() : $this->postParameters,
                        is_null($this->filesParameters) ? array() : $this->filesParameters
                    )
                );
                $this->secondGump->validation_rules( $validation_rules );
                $this->secondGump->filter_rules( $filter_rules );
                $this->postParameters = $this->secondGump->run( $parms );
                $this->pageStatus->setPostParameters( $this->postParameters );
                $this->unvalidated_parameters = $parms;
                if ( $this->postParameters === false ) {
                    $this->readableErrors = $this->secondGump->get_readable_errors(true);
                    return false;
                } else {
                    return true;
                }
            }
        } else {
            throw new \Exception('Illegal csrftoken Exception');
        }
    }

}
