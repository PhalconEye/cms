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

namespace Core\Model;

class Widget extends \Phalcon\Mvc\Model
{

    /**
     * @var integer
     *
     */
    protected $id;

    /**
     * @var string
     *
     */
    protected $module;

    /**
     * @var string
     *
     */
    protected $name;

    /**
     * @var string
     *
     */
    protected $title;

    /**
     * @var string
     *
     */
    protected $description;

    /**
     * @var integer
     *
     */
    protected $is_paginated;


    /**
     * @var integer
     *
     */
    protected $is_acl_controlled;

    /**
     * @var string
     *
     */
    protected $admin_form;

    /**
     * Method to set the value of field id
     *
     * @param integer $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Method to set the value of field module
     *
     * @param string $module
     */
    public function setModule($module)
    {
        $this->module = $module;
    }

    /**
     * Method to set the value of field name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Method to set the value of field title
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Method to set the value of field description
     *
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Method to set the value of field is_paginated
     *
     * @param integer $is_paginated
     */
    public function setIsPaginated($is_paginated)
    {
        $this->is_paginated = $is_paginated;
    }

    /**
     * Method to set the value of field is_acl_controlled
     *
     * @param integer $is_acl_controlled
     */
    public function setIsAclControlled($is_acl_controlled)
    {
        $this->is_acl_controlled = $is_acl_controlled;
    }
    /**
     * Method to set the value of field admin_form
     *
     * @param string $admin_form
     */
    public function setAdminForm($admin_form)
    {
        $this->admin_form = $admin_form;
    }


    /**
     * Returns the value of field id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the value of field module
     *
     * @return string
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     * Returns the value of field name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns the value of field title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Returns the value of field description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Returns the value of field is_paginated
     *
     * @return integer
     */
    public function getIsPaginated()
    {
        return $this->is_paginated;
    }

    /**
     * Returns the value of field is_acl_controlled
     *
     * @return integer
     */
    public function getIsAclControlled()
    {
        return $this->is_acl_controlled;
    }

    /**
     * Returns the value of field admin_form
     *
     * @return string
     */
    public function getAdminForm()
    {
        return $this->admin_form;
    }

    public function getSource()
    {
        return "widgets";
    }

}
