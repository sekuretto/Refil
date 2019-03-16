<?php

    /**
     * Fired during plugin activation
     *
     * @link       http://codeboxr.com
     * @since      1.0.0
     *
     * @package    CBXWPSimpleaccounting
     * @subpackage CBXWPSimpleaccounting/includes
     * @author     Codeboxr <info@codeboxr.com>
     */
    class CBXWPSimpleaccounting_Activator {

        /**
         * Short Description. (use period)
         *
         * Long Description.
         *
         * @since    1.0.0
         */
        public static function activate() {

            //check if the current user can activate plugin
            if (!current_user_can('activate_plugins'))
                return;


	        if (is_plugin_active('cbxwpsimpleaccountinglog/cbxwpsimpleaccountinglog.php') )
	        {
		        deactivate_plugins('cbxwpsimpleaccountinglog/cbxwpsimpleaccountinglog.php');

		        $message = esc_html__('CBX Accounting Note: Log manager addon plugin(CBX Accounting Log) is merged with core and force deactivated, please delete the log manager plugin.', 'cbxwpsimpleaccounting');

		        update_option('cbxwpsimpleaccounting_admin_notices', $message);
	        }

            $plugin = isset($_REQUEST['plugin']) ? $_REQUEST['plugin'] : '';
            check_admin_referer("activate-plugin_{$plugin}");

            CBXWpsimpleaccountingHelper::dbTableCreation();
            CBXWpsimpleaccountingHelper::defaultCategoryCreation();

        }
    }
