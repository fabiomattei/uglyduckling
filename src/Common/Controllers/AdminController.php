<?php

namespace Fabiom\UglyDuckling\Common\Controllers;

use Fabiom\UglyDuckling\BusinessLogic\Group\Daos\UserGroupDao;
use Fabiom\UglyDuckling\BusinessLogic\Ip\Daos\DeactivatedUserDao;
use Fabiom\UglyDuckling\BusinessLogic\Ip\Daos\IpDao;
use Fabiom\UglyDuckling\BusinessLogic\Ip\Daos\SecurityLogDao;
use Fabiom\UglyDuckling\BusinessLogic\Ip\UseCases\AddDelayToIp;
use Fabiom\UglyDuckling\BusinessLogic\Ip\UseCases\AddEscalationFailedAttemptToSecurityLog;
use Fabiom\UglyDuckling\Common\Controllers\Controller;

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
            $deactivatedUserDao->insertUser( $this->pageStatus->getSessionWrapper()->getSessionUsename() );

            $this->redirectToPage( $this->applicationBuilder->getRouterContainer()->makeRelativeUrl( 'login' ) );
        }
    }

}
