<?php

/**
 * PhalconEye
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 *
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to phalconeye@gmail.com so we can send you a copy immediately.
 *
 */

namespace Core\Api;

/**
 * Provides small layer between session and services
 */
class Auth implements \Engine\Api\ApiInterface{

    private $_identity = 0;

    /**
     * @var \Phalcon\DiInterface
     */
    protected $_di;

    /**
     * @param $identity Current session identity
     */
    public function __construct(\Phalcon\DiInterface $di){
        $this->_di = $di;
        $this->_identity = $this->_di->get('session')->get('identity', 0);
    }

    /**
     * Authenticate user
     *
     * @param int
     *
     * @return bool
     */
    public function authenticate($identity){
        $this->_identity = $identity;
        $this->_di->get('session')->set('identity', $identity);
    }

    /**
     * Clear identity, logout
     */
    public function clearAuth(){
        $this->_identity = 0;
        $this->_di->get('session')->set('identity', 0);
    }

    /**
     * Get current identity
     *
     * @return int
     */
    public function getIdentity(){
        return $this->_identity;
    }

}