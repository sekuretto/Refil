<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://codeboxr.com
 * @since      1.0.0
 *
 * @package    Cbxwpsimpleaccounting
 * @subpackage Cbxwpsimpleaccounting/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    Cbxwpsimpleaccounting
 * @subpackage Cbxwpsimpleaccounting/public
 * @author     Codeboxr <info@codeboxr.com>
 */
class Cbxwpsimpleaccounting_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $cbxwpsimpleaccounting    The ID of this plugin.
	 */
	private $cbxwpsimpleaccounting;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $cbxwpsimpleaccounting       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $cbxwpsimpleaccounting, $version ) {

		$this->cbxwpsimpleaccounting = $cbxwpsimpleaccounting;
		$this->version = $version;

	}

}
