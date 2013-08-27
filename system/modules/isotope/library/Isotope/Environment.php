<?php

/**
 * Isotope eCommerce for Contao Open Source CMS
 *
 * Copyright (C) 2009-2012 Isotope eCommerce Workgroup
 *
 * @package    Isotope
 * @link       http://www.isotopeecommerce.com
 * @license    http://opensource.org/licenses/lgpl-3.0.html LGPL
 */

namespace Isotope;

/**
 * Class Isotope\Environment
 *
 * Provide information about the current working environment
 * @copyright  Isotope eCommerce Workgroup 2009-2013
 * @author     Andreas Schempp <andreas.schempp@terminal42.ch>
 */
class Environment extends \System
{

    /**
     * True if frontend preview is active
     * @var bool
     */
    protected $blnIsFrontendPreview = false;

    /**
     * True if user can see unpublished elements
     * @var bool
     */
    protected $blnCanSeeUnpublished = false;

    /**
     * Logged in frontend member
     * @var \MemberModel
     */
    protected $objMember;

    /**
     * Logged in backend user
     * @var \UserModel
     */
    protected $objUser;


    /**
     * Construct object and set defaults from Contao environment
     */
    public function __construct()
    {
        parent::__construct();

        $this->reset();
    }

    /**
     * (Re)set environment from Contao defaults
     * @return  Environment
     */
    public function reset()
    {
        // Check if frontend user is logged in
        if (FE_USER_LOGGED_IN === true) {
            $objUser = FrontendUser::getInstance();
            $this->objMember = \MemberModel::findByPk($objUser->id);
        }

        // Check if a backend user is logged in
        $objUser = BackendUser::getInstance();
        if ($objUser->id > 0) {
            $this->objUser = \UserModel::findByPk($objUser->id);
        }

        $this->blnCanSeeUnpublished = (BE_USER_LOGGED_IN === true);
        $this->blnIsFrontendPreview = (FE_PREVIEW === true);

        return $this;
    }

    /**
     * Return true if a backend user is available
     * @return  bool
     */
    public function hasUser()
    {
        return (null !== $this->objUser);
    }

    /**
     * Return true if a frontend member is available
     * @return  bool
     */
    public function hasMember()
    {
        return (null !== $this->objMember);
    }

    /**
     * Return true if frontend preview is active
     * @return  bool
     */
    public function isFrontendPreview()
    {
        return $this->blnIsFrontendPreview;
    }

    /**
     * Return true if unpublished should be shown in frontend preview
     * @return  bool
     */
    public function canSeeUnpublished()
    {
        return $this->blnCanSeeUnpublished;
    }

    /**
     * Return true if we're in the install script
     * @return  bool
     */
    public function isInstallScript()
    {
        return (strpos(\Environment::get('script'), 'install.php') !== false);
    }

    /**
     * Return true if we're in postsale script
     * @return  bool
     */
    public function isPostsaleScript()
    {
        return (strpos(\Environment::get('script'), 'postsale.php') !== false);
    }

    /**
     * Return true if we're in cron script
     * @return  bool
     */
    public function isCronScript()
    {
        return (strpos(\Environment::get('script'), 'cron.php') !== false);
    }

    /**
     * Get current frontend member
     * @return  \MemberModel
     */
    public function getMember()
    {
        return $this->objMember;
    }

    /**
     * Return groups of current frontend member
     * @return  array
     */
    public function getMemberGroups()
    {
        if (!$this->hasMember()) {
            return array();
        }

        $arrGroups = deserialize($this->getMember()->groups);

        if (!is_array($arrGroups)) {
            $arrGroups = array();
        }

        return $arrGroups;
    }

    /**
     * Get current backend user
     * @return  \UserModel
     */
    public function getUser()
    {
        return $this->objUser;
    }

    /**
     * Return groups of current backend user
     * @return  array
     */
    public function getUserGroups()
    {
        if (!$this->hasUser()) {
            return array();
        }

        $arrGroups = deserialize($this->getUser()->groups);

        if (!is_array($arrGroups)) {
            $arrGroups = array();
        }

        return $arrGroups;
    }

    /**
     * Set if frontend preview is active
     * @param   bool
     * @return  Environment
     */
    public function setIsFrontendPreview($blnStatus)
    {
        $this->blnIsFrontendView = (bool) $blnStatus;

        return $this;
    }

    /**
     * Set if visitor can see unpublished elements
     * @param   bool
     * @return  Environment
     */
    public function setCanSeeUnpublished($blnStatus)
    {
        $this->blnCanSeeUnpublished = (bool) $blnStatus;

        return $this;
    }

    /**
     * Set current frontend member
     * @param   \MemberModel
     * @return  Environment
     */
    public function setMember(\MemberModel $objMember)
    {
        $this->objMember = $objMember;

        return $this;
    }

    /**
     * Set current backend user
     * @param   \UserModel
     * @return  Environment
     */
    public function setUser(\UserModel $objUser)
    {
        $this->objUser = $objUser;

        return $this;
    }
}
