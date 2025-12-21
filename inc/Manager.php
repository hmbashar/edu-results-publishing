<?php
/**
 * Manager.php
 *
 * This file contains the Manager class, which is responsible for handling
 * the initialization of the required configurations and functionalities
 * for the EDU Results Publishing plugin. It ensures the proper setup of
 * the Admin and Frontend managers.
 *
 * @package CBEDU
 * @since 1.2.0
 */
namespace CBEDU\Inc;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

use CBEDU\Admin\AdminManager;
use CBEDU\Frontend\Frontend;

/**
 * The manager class for EDU Results Publishing.
 *
 * This class handles the initialization of the required configurations and functionalities
 * for the EDU Results Publishing plugin. The class is responsible for initializing
 * the Admin Manager and Frontend components.
 *
 * @package CBEDU
 * @since 1.2.0
 */
class Manager
{
    /**
     * Admin Manager instance
     *
     * @var AdminManager
     */
    protected $Admin_Manager;

    /**
     * Frontend instance
     *
     * @var Frontend
     */
    protected $Frontend;

    /**
     * Constructor for the Manager class.
     *
     * This method initializes the EDU Results Manager by calling the init method.
     *
     * @since 1.2.0
     */
    public function __construct()
    {
        $this->init();
    }

    /**
     * Initiate the EDU Results Manager
     *
     * This method initializes the Admin Manager and Frontend components.
     *
     * @since 1.2.0
     */
    public function init()
    {
        $this->Admin_Manager = new AdminManager();
        $this->Frontend = new Frontend();
    }
}
