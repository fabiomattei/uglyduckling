<?php

namespace Fabiom\UglyDuckling\Framework\Controllers;

class ControllerNoCSRFTokenRenew extends Controller {

    public function showPage() {
        $time_start = microtime(true);

        if ($this->pageStatus->getServerWrapper()->isGetRequest()) {
            if ( $this->check_authorization_get_request() ) {
                if ( $this->check_get_request() ) {
                    $this->getRequest();
                } else {
                    $this->show_get_error_page();
                }
            } else {
                $this->check_authorization_get_request();
            }
        } else {
            if ( $this->check_authorization_post_request() ) {
                if ( $this->check_post_request() ) {
                    $this->postRequest();
                } else {
                    $this->show_post_error_page();
                }
            } else {
                $this->check_authorization_post_request();
            }
        }

        $this->loadTemplate();

        $time_end = microtime(true);
        if (($time_end - $time_start) > 5) {
            $this->logger->write('WARNING TIME :: ' . $this->request->getInfo() . ' - TIME: ' . ($time_end - $time_start) . ' sec', __FILE__, __LINE__);
        }
    }

}
