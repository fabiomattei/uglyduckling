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
        if (
            $this->pageStatus->getSessionWrapper()->getSessionGroup() == 'administrationgroup' AND
            $groupDao->checkUserHasAccessToGroup( $this->pageStatus->getSessionWrapper()->getSessionUserId(), 'administrationgroup' )
        ) {
            $this->applicationBuilder->getJsonloader()->loadIndex();
            parent::showPage();
        } else {
            $securityLogDao = new SecurityLogDao;
            $ipDao = new IpDao;
            $deactivatedUserDao = new DeactivatedUserDao;
            $securityLogDao->setDBH( $this->pageStatus->getDbconnection()->getDBH() );
            $ipDao->setDBH( $this->pageStatus->getDbconnection()->getDBH() );
            $deactivatedUserDao->setDBH( $this->pageStatus->getDbconnection()->getDBH() );
            $addEscalationFailedAttemptToSecurityLog = new AddEscalationFailedAttemptToSecurityLog;
            $addEscalationFailedAttemptToSecurityLog->performAction(
                $this->pageStatus->getServerWrapper()->getRemoteAddress(),
                $this->pageStatus->getSessionWrapper()->getSessionUsename(),
                'FAILED ESCALATION',
                $securityLogDao
            );
            $addDelayToIp = new AddDelayToIp;
            $addDelayToIp->performAction( $this->pageStatus->getServerWrapper()->getRemoteAddress(), $ipDao );

            $userDao = new UserDao();
            $userDao->setDBH( $this->pageStatus->getDbconnection()->getDBH() );
            $user = $userDao->getById( $this->pageStatus->getSessionWrapper()->getSessionUserId() );
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

            $val = new ValidationBuilder;
            $parametersGetter = BasicParameterGetter::basicParameterCheckerFactory( $this->resource, $this->applicationBuilder->getJsonloader() );
            $validation_rules = $val->getValidationRoules( $parametersGetter->getPostParameters() );
            $filter_rules = $val->getValidationFilters( $parametersGetter->getPostParameters() );

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
        }
    }

}
