<?php 
/**
 * Frontend.php
 *
 * This file contains the Frontend class, which is responsible for handling the
 * initialization and configuration of the EDU Results Publishing Frontend.
 * It ensures the proper setup of the required configurations and functionalities
 * for the EDU Results Publishing Frontend.
 *
 * @package CBEDU\Frontend
 * @since 1.2.0
 */
namespace CBEDU\Frontend;

if (!defined('ABSPATH')) exit; // Exit if accessed directly


use CBEDU\Frontend\Shortcodes\SearchForm;
use CBEDU\Frontend\Shortcodes\ResultDetails;

/**
 * Class Frontend
 *
 * Handles the initialization and configuration of the EDU Results Publishing Frontend.
 * It ensures the proper setup of the required configurations and functionalities
 * for the EDU Results Publishing Frontend.
 *
 * @package CBEDU\Frontend
 * @since 1.2.0
 */
class Frontend {

    protected $SearchForm;
    protected $ResultDetails;

    /**
     * Frontend constructor
     *
     * @since 1.2.0
     */
    public function __construct() {

        $this->setConstants();

        $this->initialize();
    }

    /**
     * Set Frontend constants
     *
     * @since 1.2.0
     */
    public function setConstants() {
        if (!defined('CBEDU_FRONTEND_ASSETS_URL')) {
            define('CBEDU_FRONTEND_ASSETS_URL', CBEDU_RESULT_URL . 'Frontend/Assets');
        }

        if (!defined('CBEDU_FRONTEND_PATH')) {
            define('CBEDU_FRONTEND_PATH', CBEDU_RESULT_PATH . 'Frontend/');
        }

    }




    /**
     * Initialize the EDU Results Publishing Frontend
     *
     * @since 1.2.0
     */
    public function initialize() {
        $this->SearchForm = new SearchForm();
        $this->ResultDetails = new ResultDetails();
    }
}
