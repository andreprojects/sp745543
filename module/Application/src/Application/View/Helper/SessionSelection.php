<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\Session\Container;

class SessionSelection extends AbstractHelper {

    public function __invoke($namespace = null) {
       	return $session = new Container($namespace);
    }

}
