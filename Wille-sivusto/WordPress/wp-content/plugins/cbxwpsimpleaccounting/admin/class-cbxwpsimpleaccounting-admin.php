<?php

    /**
     * The dashboard-specific functionality of the plugin.
     *
     * Defines the plugin name, version, and two examples hooks for how to
     * enqueue the dashboard-specific stylesheet and JavaScript.
     *
     * @link       http://codeboxr.com
     * @since      1.0.0
     * @package    Cbxwpsimpleaccounting
     * @subpackage Cbxwpsimpleaccounting/admin
     * @author     Codeboxr <info@codeboxr.com>
     */
    class CBXWPSimpleAccounting_Admin
    {
        /**
         * The ID of this plugin.
         *
         * @since    1.0.0
         * @access   private
         * @var      string $cbxwpsimpleaccounting The ID of this plugin.
         */
        private $cbxwpsimpleaccounting;

        /**
         * The version of this plugin.
         *
         * @since    1.0.0
         * @access   private
         * @var      string $version The current version of this plugin.
         */
        private $version;

        private $settings_api;

        /**
         * The plugin basename of the plugin.
         *
         * @since    1.0.0
         * @access   protected
         * @var      string $plugin_basename The plugin basename of the plugin.
         */
        protected $plugin_basename;

        /**
         * Initialize the class and set its properties.
         *
         * @since    1.0.0
         *
         * @param      string $cbxwpsimpleaccounting The name of this plugin.
         * @param      string $version               The version of this plugin.
         */
        public function __construct($cbxwpsimpleaccounting, $version)
        {

            $this->cbxwpsimpleaccounting = $cbxwpsimpleaccounting;

            $this->version = $version;
            $this->plugin_basename = plugin_basename(plugin_dir_path(__DIR__) . $this->cbxwpsimpleaccounting . '.php');
            $this->settings_api = new CBXWPSimpleaccounting_Settings_API(CBXWPSIMPLEACCOUNTING_PLUGIN_NAME, CBXWPSIMPLEACCOUNTING_PLUGIN_VERSION);
        }

        public function admin_notices(){
				$admin_notices = get_option('cbxwpsimpleaccounting_admin_notices');
				if($admin_notices !== false){

					?>
					<div class="notice notice-success is-dismissible">
						<p><strong><?php echo esc_html($admin_notices); ?></strong></p>
					</div>
					<?php

					delete_option('cbxwpsimpleaccounting_admin_notices');
				}



        }

        public function start_session(){
	        if(!session_id()) {
		        session_start();
	        }
        }


	    public function session_logout() {
		    session_destroy();
	    }
        /**
         * Settings init
         */
        public function setting_init()
        {

            //set the settings
            $this->settings_api->set_sections($this->get_settings_sections());
            $this->settings_api->set_fields($this->get_settings_fields());

            //initialize settings
            $this->settings_api->admin_init();



            $role = get_role('administrator');

            //who can manage or manage accounting capability
            // Set 'manage_cbxaccounting','edit_cbxaccounting','delete_accounting' Capabilities To Administrator
            if (!$role->has_cap('manage_cbxaccounting')) {
                $role->add_cap('manage_cbxaccounting');
            }

            //who can add or edit accounting log capability
            if (!$role->has_cap('edit_cbxaccounting')) {
                $role->add_cap('edit_cbxaccounting');
            }

            //who can delete or delete accounting log capability
            if (!$role->has_cap('delete_cbxaccounting')) {
                $role->add_cap('delete_cbxaccounting');
            }

	        $role = get_role('administrator');

	        // Set 'manage_cbxaccounting' Capabilities To Administrator
	        if (!$role->has_cap('log_cbxaccounting')) {
		        $role->add_cap('log_cbxaccounting');
	        }
        }

	    /**
	     * Register the administration menu for this plugin into the WordPress Dashboard menu.
	     *
	     * @since    1.0.0
	     */
	    public function admin_menus()
	    {
		    //overview
		    $main_menu_hook = add_menu_page(esc_html__('CBX Accounting', 'cbxwpsimpleaccounting'), esc_html__('CBX Accounting', 'cbxwpsimpleaccounting'), 'manage_cbxaccounting', 'cbxwpsimpleaccounting', array($this, 'admin_menu_display_overview'), 'dashicons-chart-line');




		    $sub_menu_log_hook = add_submenu_page('cbxwpsimpleaccounting', esc_html__('CBX Accounting Log', 'cbxwpsimpleaccounting'), esc_html__('Log Manager', 'cbxwpsimpleaccounting'), 'log_cbxaccounting', 'cbxwpsimpleaccountinglog', array($this, 'admin_menu_display_logs'));

		    //add income expense menu
		    $sub_menu_expinc_hook = add_submenu_page('cbxwpsimpleaccounting', esc_html__('CBX Accounting: Manage Income/Expence', 'cbxwpsimpleaccounting'), esc_html__('Add Log', 'cbxwpsimpleaccounting'), 'edit_cbxaccounting', 'cbxwpsimpleaccounting_addexpinc', array($this, 'admin_menu_display_adddexpinc'));

		    //category menu
		    $sub_menu_cat_hook = add_submenu_page('cbxwpsimpleaccounting', esc_html__('CBX Accounting: Manage Category', 'cbxwpsimpleaccounting'), esc_html__('Manage Category', 'cbxwpsimpleaccounting'), 'manage_options', 'cbxwpsimpleaccounting_cat', array($this, 'admin_menu_display_cats'));

		    //account manager menu
		    $sub_menu_account_hook = add_submenu_page('cbxwpsimpleaccounting', esc_html__('CBX Accounting: Manage Accounts', 'cbxwpsimpleaccounting'), esc_html__('Manage Accounts', 'cbxwpsimpleaccounting'), 'manage_options','cbxwpsimpleaccounting_accmanager', array($this,  'admin_menu_display_accounts') );

		    //setting menu
		    $sub_menu_setting_hook = add_submenu_page('cbxwpsimpleaccounting', esc_html__('CBX Accounting:', 'cbxwpsimpleaccounting'), esc_html__('Setting', 'cbxwpsimpleaccounting'), 'manage_options', 'cbxwpsimpleaccounting_settings', array($this, 'admin_menu_display_settings'));

		    //addons menu
		    $sub_menu_addon_hook = add_submenu_page('cbxwpsimpleaccounting', esc_html__('CBX Accounting:', 'cbxwpsimpleaccounting'), esc_html__('Add-ons', 'cbxwpsimpleaccounting'), 'manage_options', 'cbxwpsimpleaccounting_addons', array($this, 'admin_menu_display_addons'));

		    //add screen option for log listing
		    add_action("load-$sub_menu_log_hook", array($this, 'add_screen_option_logs'));

		    //add screen option for category listing
		    add_action("load-$sub_menu_cat_hook", array($this, 'add_screen_option_cats'));

		    //add screen option for account listing
		    add_action("load-$sub_menu_account_hook", array($this, 'add_screen_option_accounts'));

	    }


	    /**
	     * Add screen option for Category listing
	     */
	    public function add_screen_option_cats()
	    {
		    $option = 'per_page';
		    $args = array(
			    'label'   => esc_html__('Number of items per page:', 'cbxwpsimpleaccounting'),
			    'default' => 20,
			    'option'  => 'cbxwpsimpleaccounting_catlisting_per_page'
		    );
		    add_screen_option($option, $args);
	    }

	    /**
	     * Set screen options for category listing
	     *
	     * @param $status
	     * @param $option
	     * @param $value
	     *
	     * @return mixed
	     */
	    public function set_screen_option_cats($status, $option, $value)
	    {
	    	if ('cbxwpsimpleaccounting_catlisting_per_page' == $option) {
			    return $value;
		    }

		    return $status;
	    }


	    /**
	     * Add screen option for Account listing listing
	     */
	    public function add_screen_option_accounts()
	    {
		    $option = 'per_page';
		    $args = array(
			    'label'   => esc_html__('Number of items per page:', 'cbxwpsimpleaccounting'),
			    'default' => 20,
			    'option'  => 'cbxwpsimpleaccounting_acclisting_per_page'
		    );
		    add_screen_option($option, $args);
	    }

	    /**
	     * Set screen options for account listing
	     *
	     * @param $status
	     * @param $option
	     * @param $value
	     *
	     * @return mixed
	     */
	    public function set_screen_option_accounts($status, $option, $value)
	    {

		    if ('cbxwpsimpleaccounting_acclisting_per_page' == $option) {
			    return $value;
		    }

		    return $status;
	    }


	    /**
	     * Add screen option for log listing
	     */
	    public static function add_screen_option_logs()
	    {
		    $option = 'per_page';
		    $args = array(
			    'label'   => esc_html__('Number of items per page:', 'cbxwpsimpleaccounting'),
			    'default' => 50,
			    'option'  => 'cbxwpsimpleaccountinglog_results_per_page'
		    );
		    add_screen_option($option, $args);
	    }


	    /**
	     * Set screen options for log listing
	     *
	     * @param $status
	     * @param $option
	     * @param $value
	     *
	     * @return mixed
	     */
	    public static function set_screen_option_logs($status, $option, $value)
	    {
		    if ('cbxwpsimpleaccountinglog_results_per_page' == $option) {
			    return $value;
		    }

		    return $status;
	    }


	    /**
	     * Show Log page New
	     *
	     */
	    public function admin_menu_display_logs()
	    {

		    if (!class_exists('CBXWpsimpleaccountinglogListTable')) {
			    require_once(plugin_dir_path(__FILE__) . '../includes/class-cbxwpsimpleaccounting-loglist.php');



			    include('partials/admin-managelogs-listing.php');
		    }

	    }

        /**
         * accounts export
         */
        public function cbxwpsimpleaccounting_accounts_export()
        {
            //proceed after checking nonce...
            if (isset( $_POST['_wpnonce'] ) && ! empty( $_POST['_wpnonce'] )  && isset($_REQUEST['cbxwpsimpleaccounting_accounts_export']) && isset($_REQUEST['format']) && $_REQUEST['format'] !== null) {




	            $nonce  = filter_input( INPUT_POST, '_wpnonce', FILTER_SANITIZE_STRING );
	            $action = 'bulk-cbxaccountingaccs';

	            if ( ! wp_verify_nonce( $nonce, $action ) ){
		            wp_die( 'Nope! Security check failed!' );
	            }

                $export_format = sanitize_text_field($_REQUEST['format']);

                $phpexcel_loaded = false;
                if (class_exists('PHPExcel')) {
                    $phpexcel_loaded = true;
                } else {
                    if (file_exists(plugin_dir_path(__FILE__) . '../includes/PHPExcel.php')) {
                        //Include PHPExcel
                        require_once(plugin_dir_path(__FILE__) . '../includes/PHPExcel.php');

                        $phpexcel_loaded = true;
                    }
                }

                if($phpexcel_loaded == false){
	                echo esc_html__('Sorry PHPExcel library not loaded properly.', 'cbxwpsimpleaccounting').sprintf(__(' Back to <a href="%s">Accounts Manager</a>.', 'cbxwpsimpleaccounting'), admin_url('admin.php?page=cbxwpsimpleaccounting_accmanager'));
	                exit();
                }

                if ($phpexcel_loaded) {



	                error_reporting(0);

	                global $wpdb;

                    $cbxacc_table_name = $wpdb->prefix . 'cbaccounting_account_manager';

                    $search = (isset($_REQUEST['s']) && $_REQUEST['s'] != '') ? sanitize_text_field($_REQUEST['s']) : '';
                    $order = (isset($_GET['order']) && $_GET['order'] != '') ? $_GET['order'] : 'desc';
                    $order_by = (isset($_GET['orderby']) && $_GET['orderby'] != '') ? $_GET['orderby'] : 'a.id';

                    //more filters
                    $type = isset($_GET['cbxactacc_type']) ? stripslashes($_GET['cbxactacc_type']) : ''; //type

                    $datas = CBXWpsimpleaccountingHelper::getAccountsData($search, $type, $order_by, $order, 20, 1);

                    $excell_cell_char = '';

                    $objPHPExcel = new PHPExcel();
                    $objPHPExcel->setActiveSheetIndex(0);
                    $objPHPExcel->getActiveSheet()->setCellValue('A1', esc_html__('Title', 'cbxwpsimpleaccounting'));
                    $objPHPExcel->getActiveSheet()->setCellValue('B1', esc_html__('Type', 'cbxwpsimpleaccounting'));
                    $objPHPExcel->getActiveSheet()->setCellValue('C1', esc_html__('Acc. No.', 'cbxwpsimpleaccounting'));
                    $objPHPExcel->getActiveSheet()->setCellValue('D1', esc_html__('Acc. Name', 'cbxwpsimpleaccounting'));
                    $objPHPExcel->getActiveSheet()->setCellValue('E1', esc_html__('Bank Name', 'cbxwpsimpleaccounting'));
                    $objPHPExcel->getActiveSheet()->setCellValue('F1', esc_html__('Branch Name', 'cbxwpsimpleaccounting'));
                    $objPHPExcel->getActiveSheet()->setCellValue('G1', esc_html__('Added By', 'cbxwpsimpleaccounting'));
                    $objPHPExcel->getActiveSheet()->setCellValue('H1', esc_html__('Modified By', 'cbxwpsimpleaccounting'));
                    $objPHPExcel->getActiveSheet()->setCellValue('I1', esc_html__('Added Date', 'cbxwpsimpleaccounting'));
                    $objPHPExcel->getActiveSheet()->setCellValue('J1', esc_html__('Modified Date', 'cbxwpsimpleaccounting'));
                    $objPHPExcel->getActiveSheet()->setCellValue('K1', esc_html__('ID', 'cbxwpsimpleaccounting'));

                    do_action('cbxwpsimpleaccounting_accounts_export_other_heading', $objPHPExcel);

                    $objPHPExcel->getActiveSheet()->getStyle('A1:Z1')->getFont()->setBold(true);
                    $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn('A:K')->setAutoSize(true);

                    if ($datas) {


                        foreach ($datas as $i => $data) {
                            //for .xls,.xlsx and .csv
                            $type = (stripslashes($data['type']) == 'cash') ? esc_html__('Cash', 'cbxwpsimpleaccounting') : esc_html__('Bank', 'cbxwpsimpleaccounting');

                            if ($data['mod_by']) {
                                $mod_by = stripslashes(get_user_by('id', $data['mod_by'])->display_name);
                            } else {
                                $mod_by = esc_html__('N/A', 'cbxwpsimpleaccounting');
                            }

                            $mod_date = ($data['mod_date'] == '' || $data['mod_date'] == null) ? esc_html__('N/A', 'cbxwpsimpleaccounting') : $data['mod_date'];

                            $objPHPExcel->getActiveSheet()->setCellValue('A' . ($i + 2), esc_html($data['title']));
                            $objPHPExcel->getActiveSheet()->setCellValue('B' . ($i + 2), $type);
                            $objPHPExcel->getActiveSheet()->setCellValue('C' . ($i + 2), stripslashes($data['acc_no']));
                            $objPHPExcel->getActiveSheet()->setCellValue('D' . ($i + 2), stripslashes($data['acc_name']));
                            $objPHPExcel->getActiveSheet()->setCellValue('E' . ($i + 2), stripslashes($data['bank_name']));
                            $objPHPExcel->getActiveSheet()->setCellValue('F' . ($i + 2), stripslashes($data['branch_name']));
                            $objPHPExcel->getActiveSheet()->setCellValue('G' . ($i + 2), stripslashes(get_user_by('id', $data['add_by'])->display_name));
                            $objPHPExcel->getActiveSheet()->setCellValue('H' . ($i + 2), $mod_by);
                            $objPHPExcel->getActiveSheet()->setCellValue('I' . ($i + 2), $data['add_date']);
                            $objPHPExcel->getActiveSheet()->setCellValue('J' . ($i + 2), $mod_date);
                            $objPHPExcel->getActiveSheet()->setCellValue('K' . ($i + 2), absint($data['id']));

                            do_action('cbxwpsimpleaccounting_accounts_export_other_col', $objPHPExcel, $i, $data);

                        }
                    }

                    //for .xls,.xlsx,.csv
                    $objPHPExcel->setActiveSheetIndex(0);

                    ob_clean();
                    ob_start();

                    $filename = 'cbxaccounting-accounts';
                    switch ($export_format) {
                        case 'csv':
                            // Redirect output to a client’s web browser (csv)
                            $filename = $filename . '.csv';
                            header("Content-type: text/csv");
                            header("Cache-Control: no-store, no-cache");
                            header('Content-Disposition: attachment; filename="' . $filename . '"');
                            $objWriter = new PHPExcel_Writer_CSV($objPHPExcel);
                            $objWriter->setDelimiter(',');
                            $objWriter->setEnclosure('"');
                            $objWriter->setLineEnding("\r\n");
                            $objWriter->setSheetIndex(0);
                            $objWriter->save('php://output');
                            break;
                        case 'xls':
                            // Redirect output to a client’s web browser (Excel5)
                            $filename = $filename . '.xls';
                            header('Content-Type: application/vnd.ms-excel');
                            header('Content-Disposition: attachment;filename="' . $filename . '"');
                            header('Cache-Control: max-age=0');
                            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
                            $objWriter->save('php://output');
                            break;
                        case 'xlsx':
                            // Redirect output to a client’s web browser (Excel2007)
                            $filename = $filename . '.xlsx';
                            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                            header('Content-Disposition: attachment;filename="' . $filename . '"');
                            header('Cache-Control: max-age=0');
                            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
                            $objWriter->save('php://output');
                            break;
                    }
                    exit;
                }
            }

        }

        /**
         * call back for filter 'cbxwpsimpleaccounting_title_link'
         *
         */
        public function overview_title_link($title, $id)
        {

             if (!current_user_can('manage_cbxaccounting')) {
                 return $title;
             }

            return '<a href="' . get_admin_url() . 'admin.php?page=cbxwpsimpleaccounting_addexpinc&id=' . $id . '&view=1">' . $title . '</a>';

        }

        /**
         * Register the stylesheets for Dashboard pages.
         *
         * @since    1.0.0
         */
        public function enqueue_styles($hook)
        {

        	$current_page = isset($_GET['page'])? $_GET['page']: '';

            /*$pages = array(
                'toplevel_page_cbxwpsimpleaccounting', //overview page
                'cbx-accounting_page_cbxwpsimpleaccounting_addexpinc', //add/edit income/expense page
                'cbx-accounting_page_cbxwpsimpleaccounting_cat', //category manager page
                'cbx-accounting_page_cbxwpsimpleaccounting_accmanager', // account manager page
                'cbx-accounting_page_cbxwpsimpleaccounting_addons' //addon page
            );

	        $all_admin_pages = array(
		        'toplevel_page_cbxwpsimpleaccounting', //overview page
		        'cbx-accounting_page_cbxwpsimpleaccounting_addexpinc', //add/edit income/expense page
		        'cbx-accounting_page_cbxwpsimpleaccounting_cat', //category manager page
		        'cbx-accounting_page_cbxwpsimpleaccounting_accmanager', // account manager page
		        'cbx-accounting_page_cbxwpsimpleaccounting_addons', //addon page
		        'cbx-accounting_page_cbxwpsimpleaccountinglog', //log page
	            'cbx-accounting_page_cbxwpsimpleaccounting_settings' //setting page
	        );*/

	        $pages = array(
		        'cbxwpsimpleaccounting', //overview page
		        'cbxwpsimpleaccounting_addexpinc', //add/edit income/expense page
		        'cbxwpsimpleaccounting_cat', //category manager page
		        'cbxwpsimpleaccounting_accmanager', // account manager page
		        'cbxwpsimpleaccounting_addons' //addon page
	        );

	        $all_admin_pages = array(
		        'cbxwpsimpleaccounting', //overview page
		        'cbxwpsimpleaccounting_addexpinc', //add/edit income/expense page
		        'cbxwpsimpleaccounting_cat', //category manager page
		        'cbxwpsimpleaccounting_accmanager', // account manager page
		        'cbxwpsimpleaccounting_addons', //addon page
		        'cbxwpsimpleaccountinglog', //log page
		        'cbxwpsimpleaccounting_settings' //setting page
	        );



	        wp_register_style('chosen.min', plugin_dir_url(__FILE__) . '../assets/vendor/chosen/chosen.min.css', array(), $this->version, 'all');
	        wp_register_style('flatpickr.min', plugin_dir_url(__FILE__) . '../assets/vendor/flatpickr/flatpickr.min.css', array(), $this->version, 'all');
	        wp_register_style('cbxwpsimpleaccountingbs', plugin_dir_url(__FILE__) . '../assets/vendor/bootstrap/css/cbxwpsimpleaccountingbs.css', array(), $this->version, 'all');


	        wp_register_style('cbxwpsimpleaccounting-setting', plugin_dir_url(__FILE__) . '../assets/css/cbxwpsimpleaccounting-setting.css', array('chosen.min', 'wp-color-picker'), $this->version, 'all');

	        wp_register_style('cbxwpsimpleaccounting-admin', plugin_dir_url(__FILE__) . '../assets/css/cbxwpsimpleaccounting-admin.css', array(), $this->version, 'all');
            wp_register_style('cbxwpsimpleaccounting-overview', plugin_dir_url(__FILE__) . '../assets/css/cbxwpsimpleaccounting-overview.css', array(), $this->version, 'all');
            wp_register_style('cbxwpsimpleaccounting-addons', plugin_dir_url(__FILE__) . '../assets/css/cbxwpsimpleaccounting-addons.css', array(), $this->version, 'all');

	        wp_register_style('cbxwpsimpleaccounting-log', plugin_dir_url(__FILE__) . '../assets/css/cbxwpsimpleaccounting-log.css', array('flatpickr.min', 'chosen.min'), $this->version, 'all');

	        //adding style for admin pages except log and setting
            if (in_array($current_page, $pages)) {
                wp_enqueue_style('chosen.min');
                wp_enqueue_style('flatpickr.min');
                wp_enqueue_style('wp-color-picker');

                wp_enqueue_style('cbxwpsimpleaccountingbs');
                wp_enqueue_style('cbxwpsimpleaccounting-admin');
                wp_enqueue_style('cbxwpsimpleaccounting-overview');
                wp_enqueue_style('cbxwpsimpleaccounting-addons');
            }

            //adding style for setting page
            if($current_page == 'cbxwpsimpleaccounting_settings'){
	            wp_enqueue_style('chosen.min');
	            wp_enqueue_style('wp-color-picker');
	            wp_enqueue_style('cbxwpsimpleaccounting-setting');
            }

            //adding style for log manager
            if($current_page == 'cbxwpsimpleaccountinglog'){
	            wp_enqueue_style('flatpickr.min');
	            wp_enqueue_style('chosen.min');
	            wp_enqueue_style('cbxwpsimpleaccounting-log');
            }
        }//end method enqueue_styles

        /**
         * Register the JavaScript for Dashboard pages.
         *
         * @since    1.0.0
         */
        public function enqueue_scripts($hook)
        {

	        $current_page = isset($_GET['page'])? $_GET['page']: '';

	        /*$pages = array(
				'toplevel_page_cbxwpsimpleaccounting', //overview page
				'cbx-accounting_page_cbxwpsimpleaccounting_addexpinc', //add/edit income/expense page
				'cbx-accounting_page_cbxwpsimpleaccounting_cat', //category manager page
				'cbx-accounting_page_cbxwpsimpleaccounting_accmanager', // account manager page
				'cbx-accounting_page_cbxwpsimpleaccounting_addons' //addon page
			);

			$all_admin_pages = array(
				'toplevel_page_cbxwpsimpleaccounting', //overview page
				'cbx-accounting_page_cbxwpsimpleaccounting_addexpinc', //add/edit income/expense page
				'cbx-accounting_page_cbxwpsimpleaccounting_cat', //category manager page
				'cbx-accounting_page_cbxwpsimpleaccounting_accmanager', // account manager page
				'cbx-accounting_page_cbxwpsimpleaccounting_addons', //addon page
				'cbx-accounting_page_cbxwpsimpleaccountinglog', //log page
				'cbx-accounting_page_cbxwpsimpleaccounting_settings' //setting page
			);*/

	        $pages = array(
		        'cbxwpsimpleaccounting', //overview page
		        'cbxwpsimpleaccounting_addexpinc', //add/edit income/expense page
		        'cbxwpsimpleaccounting_cat', //category manager page
		        'cbxwpsimpleaccounting_accmanager', // account manager page
		        'cbxwpsimpleaccounting_addons' //addon page
	        );

	        $all_admin_pages = array(
		        'cbxwpsimpleaccounting', //overview page
		        'cbxwpsimpleaccounting_addexpinc', //add/edit income/expense page
		        'cbxwpsimpleaccounting_cat', //category manager page
		        'cbxwpsimpleaccounting_accmanager', // account manager page
		        'cbxwpsimpleaccounting_addons', //addon page
		        'cbxwpsimpleaccountinglog', //log page
		        'cbxwpsimpleaccounting_settings' //setting page
	        );

	        // Localize the script with new data
	        $flatpickr_inline_weekdays_shorthand = array(
		        __( 'Sun' ),
		        __( 'Mon' ),
		        __( 'Tue' ),
		        __( 'Wed' ),
		        __( 'Thu' ),
		        __( 'Fri' ),
		        __( 'Sat' )
	        );

	        $flatpickr_inline_weekdays_longhand = array(
		        __( 'Sunday' ),
		        __( 'Monday' ),
		        __( 'Tuesday' ),
		        __( 'Wednesday' ),
		        __( 'Thursday' ),
		        __( 'Friday' ),
		        __( 'Saturday' )
	        );

	        $flatpickr_inline_months_shorthand = array(
		        __( 'January' ),
		        __( 'February' ),
		        __( 'March' ),
		        __( 'April' ),
		        __( 'May' ),
		        __( 'June' ),
		        __( 'July' ),
		        __( 'August' ),
		        __( 'September' ),
		        __( 'October' ),
		        __( 'November' ),
		        __( 'December' )
	        );

	        $flatpickr_inline_months_longhand = array(
		        _x( 'Jan', 'January abbreviation' ),
		        _x( 'Feb', 'February abbreviation' ),
		        _x( 'Mar', 'March abbreviation' ),
		        _x( 'Apr', 'April abbreviation' ),
		        _x( 'May', 'May abbreviation' ),
		        _x( 'Jun', 'June abbreviation' ),
		        _x( 'Jul', 'July abbreviation' ),
		        _x( 'Aug', 'August abbreviation' ),
		        _x( 'Sep', 'September abbreviation' ),
		        _x( 'Oct', 'October abbreviation' ),
		        _x( 'Nov', 'November abbreviation' ),
		        _x( 'Dec', 'December abbreviation' )
	        );

	        $flatpickr_inline_js = '
				Flatpickr.l10ns.en.weekdays.shorthand = ' . json_encode( $flatpickr_inline_weekdays_shorthand ) . ';	   
				Flatpickr.l10ns.en.weekdays.longhand = ' . json_encode( $flatpickr_inline_weekdays_longhand ) . ';	   
				Flatpickr.l10ns.en.months.longhand = ' . json_encode( $flatpickr_inline_months_shorthand ) . ';	   
				Flatpickr.l10ns.en.months.longhand = ' . json_encode( $flatpickr_inline_months_longhand ) . ';	   
				Flatpickr.l10ns.en.rangeSeparator = "' . esc_html__( ' to ', 'cbxwpsimpleaccounting' ) . '";            
				Flatpickr.l10ns.en.scrollTitle = "' . esc_html__( 'Scroll to increment', 'cbxwpsimpleaccounting' ) . '";
				Flatpickr.l10ns.en.toggleTitle = "' . esc_html__( 'Click to toggle', 'cbxwpsimpleaccounting' ) . '";
			';

	        $validation_messages = array(
	        	'jquery_validate_messages' => array(
			        'required'    => esc_html__( 'This field is required.', 'cbxwpsimpleaccounting' ),
			        'remote'      => esc_html__( 'Please fix this field.', 'cbxwpsimpleaccounting' ),
			        'email'       => esc_html__( 'Please enter a valid email address.', 'cbxwpsimpleaccounting' ),
			        'url'         => esc_html__( 'Please enter a valid URL.', 'cbxwpsimpleaccounting' ),
			        'date'        => esc_html__( 'Please enter a valid date.', 'cbxwpsimpleaccounting' ),
			        'dateISO'     => esc_html__( 'Please enter a valid date ( ISO ).', 'cbxwpsimpleaccounting' ),
			        'number'      => esc_html__( 'Please enter a valid number.', 'cbxwpsimpleaccounting' ),
			        'digits'      => esc_html__( 'Please enter only digits.', 'cbxwpsimpleaccounting' ),
			        'equalTo'     => esc_html__( 'Please enter the same value again.', 'cbxwpsimpleaccounting' ),
			        'maxlength'   => esc_html__( 'Please enter no more than {0} characters.', 'cbxwpsimpleaccounting' ),
			        'minlength'   => esc_html__( 'Please enter at least {0} characters.', 'cbxwpsimpleaccounting' ),
			        'rangelength' => esc_html__( 'Please enter a value between {0} and {1} characters long.', 'cbxwpsimpleaccounting' ),
			        'range'       => esc_html__( 'Please enter a value between {0} and {1}.', 'cbxwpsimpleaccounting' ),
			        'max'         => esc_html__( 'Please enter a value less than or equal to {0}.', 'cbxwpsimpleaccounting' ),
			        'min'         => esc_html__( 'Please enter a value greater than or equal to {0}.', 'cbxwpsimpleaccounting' ),
			        'recaptcha'   => esc_html__( 'Please check the captcha.', 'cbxwpsimpleaccounting' ),
				),
		        'validation_msg_required' => esc_html__( 'This field is required.', 'cbxwpsimpleaccounting' ),
		        'validation_msg_email'    => esc_html__( 'Please enter a valid email address.', 'cbxwpsimpleaccounting' ),
			);

	        $monthnames     = CBXWpsimpleaccountingHelper::getMonthNamesShort();


            wp_enqueue_script('jquery');


            //register choosen scripts and form validation
	        wp_register_script('chosen.jquery.min', plugin_dir_url(__FILE__) . '../assets/vendor/chosen/chosen.jquery.min.js', array('jquery'), $this->version, true);
	        wp_register_script('jquery.validate.min', plugin_dir_url(__FILE__) . '../assets/vendor/jquery-validation/jquery.validate.min.js', array('jquery'), $this->version, true);
	        wp_register_script('jquery.validate-additional-methods.min', plugin_dir_url(__FILE__) . '../assets/vendor/jquery-validation/additional-methods.min.js', array('jquery', 'jquery.validate.min'), $this->version, true);

	        //flatpickr - Date time picker
	        wp_register_script('flatpickr.min', plugin_dir_url(__FILE__) . '../assets/vendor/flatpickr/flatpickr.min.js', array('jquery'), $this->version, true);
	        wp_add_inline_script( 'flatpickr.min', $flatpickr_inline_js, 'after' );

	        wp_register_script('cbxwpsimpleaccounting-setting', plugin_dir_url(__FILE__) . '../assets/js/cbxwpsimpleaccounting-setting.js', array('jquery', 'wp-color-picker', 'chosen.jquery.min', 'jquery.validate.min', 'jquery.validate-additional-methods.min'), $this->version, true);

	        //js hook
            wp_register_script('cbxwpsajsevents', plugin_dir_url(__FILE__) . '../assets/js/cbxwpsajsevents.js', array('jquery'), $this->version, true);

            //ajax nonce
            $ajax_nonce = wp_create_nonce("cbxwpsimpleaccounting_nonce");



            //expinc manager page
            if ($current_page == 'cbxwpsimpleaccounting_addexpinc') {
                global $wpdb;
                $cat_results_list = $wpdb->get_results('SELECT `id`, `title`, `type` FROM `' . $wpdb->prefix . 'cbaccounting_category`', ARRAY_A);

                $cats = array();
                if ($cat_results_list != null) {
                    foreach ($cat_results_list as $item) {
                        $cats[$item['id']] = $item;
                    }
                }


                wp_enqueue_script('jquery');
                //register scripts
                wp_register_script('cbxwpsimpleaccounting-add', plugin_dir_url(__FILE__) . '../assets/js/cbxwpsimpleaccounting-add.js', array('jquery', 'jquery.validate.min', 'jquery.validate-additional-methods.min'), '1.1', true);

                // Localize the script with new data
                $translation_array = array(
                    'edit'                  => esc_html__('Edit', 'cbxwpsimpleaccounting'),
                    'category_update_label' => esc_html__('Add Category', 'cbxwpsimpleaccounting'),
                    'category_add_label'    => esc_html__('Upadte Category', 'cbxwpsimpleaccounting'),
                    'nonce'                 => $ajax_nonce,
                    'cat_results_list'      => wp_json_encode($cats),
					'validation_messages'   => $validation_messages
                );
                wp_localize_script('cbxwpsimpleaccounting-add', 'cbxwpsimpleaccounting', $translation_array);

                //enqueue script

                wp_enqueue_script('jquery.validate.min');
                wp_enqueue_script('jquery.validate-additional-methods.min');
                wp_enqueue_script('chosen.jquery.min');
                wp_enqueue_script('flatpickr.min');
                wp_enqueue_script('cbxwpsajsevents');
                wp_enqueue_script('cbxwpsimpleaccounting-add');
            }

            //category manager page
            if ($current_page == 'cbxwpsimpleaccounting_cat') {

                //register scripts
                wp_register_script('cbxwpsimpleaccounting-category', plugin_dir_url(__FILE__) . '../assets/js/cbxwpsimpleaccounting-category.js', array('jquery', 'jquery.validate.min', 'jquery.validate-additional-methods.min', 'wp-color-picker', 'chosen.jquery.min'), '1.2', true);

                // Localize the script with new data
                $translation_array = array(
                    'edit'                  => esc_html__('Edit', 'cbxwpsimpleaccounting'),
                    'category_update_label' => esc_html__('Add Category', 'cbxwpsimpleaccounting'),
                    'category_add_label'    => esc_html__('Upadte Category', 'cbxwpsimpleaccounting'),
                    'protected'             => esc_html__('Protected', 'cbxwpsimpleaccounting'),
                    'nonce'                 => $ajax_nonce,
                    'validation_messages'   => $validation_messages
                );
                wp_localize_script('cbxwpsimpleaccounting-category', 'cbxwpsimpleaccounting', $translation_array);

                //enqueue scripts
	            wp_enqueue_script('jquery');
	            wp_enqueue_script('chosen.jquery.min');
	            wp_enqueue_script('wp-color-picker');
	            wp_enqueue_script('jquery.validate.min');
	            wp_enqueue_script('jquery.validate-additional-methods.min');
                wp_enqueue_script('cbxwpsimpleaccounting-category');
            }

            //overview page
            if ($current_page == 'cbxwpsimpleaccounting') {

                //register script
	            wp_register_script('cbxchartkickgjsapi', '//google.com/jsapi', array(), $this->version, false);
	            wp_register_script('cbxchartkick', plugin_dir_url(__FILE__) . '../assets/js/chartkick.js', array('jquery', 'cbxchartkickgjsapi'), $this->version, true);

	            wp_register_script('cbxwpsimpleaccounting-admin-overview', plugin_dir_url(__FILE__) . '../assets/js/cbxwpsimpleaccounting-admin-overview.js', array('jquery', 'cbxchartkickgjsapi', 'cbxchartkick'), $this->version, true);



	            //Localize the script with new data
                $translation_array = array(
                    'nonce'      => $ajax_nonce,
                    'permission' => esc_html__('This action can not be undone. Are you sure to delete this entry ?', 'cbxwpsimpleaccounting'),
                    'month'      => esc_html__('Month', 'cbxwpsimpleaccounting'),
                    'day'        => esc_html__('Day', 'cbxwpsimpleaccounting'),
                    'income'     => esc_html__('Income', 'cbxwpsimpleaccounting'),
                    'expense'    => esc_html__('Expense', 'cbxwpsimpleaccounting'),
                    'monthnames'   => $monthnames,
                    'chart_colors' => array(
	                    $this->settings_api->get_option( 'legend_color_for_income', 'cbxwpsimpleaccounting_graph', '#5cc488' ),
	                    $this->settings_api->get_option( 'legend_color_for_expense', 'cbxwpsimpleaccounting_graph', '#e74c3c' )
                    ), //Line color
                    'validation_messages'   => $validation_messages
                );
                wp_localize_script('cbxwpsimpleaccounting-admin-overview', 'cbxwpsimpleaccounting', $translation_array);




            }

            //account manager page

            if (isset($_GET['page']) && ($_GET['page'] == 'cbxwpsimpleaccounting_accmanager') && isset($_GET['view']) && $_GET['view'] == 'addedit') {

                global $wpdb;
                $accs = array();
                $acc_results_list = $wpdb->get_results('SELECT `id`, `title`, `type` FROM `' . $wpdb->prefix . 'cbaccounting_account_manager`', ARRAY_A);
                $cats = array();
                if ($acc_results_list != null) {
                    foreach ($acc_results_list as $item) {
                        $accs[$item['id']] = $item;
                    }
                }

                //wp_enqueue_script('jquery');

                //register scripts
                wp_register_script('cbxwpsacc-accountmanager', plugin_dir_url(__FILE__) . '../assets/js/cbxwpsimpleaccounting-account-manager.js', array('jquery', 'jquery.validate.min', 'jquery.validate-additional-methods.min','chosen.jquery.min'), $this->version, true);

                // Localize the script with new data
                $translation_array = array(
                    'edit'                 => esc_html__('Edit', 'cbxwpsimpleaccounting'),
                    'account_update_label' => esc_html__('Add Account', 'cbxwpsimpleaccounting'),
                    'account_add_label'    => esc_html__('Upadte Account', 'cbxwpsimpleaccounting'),
                    'nonce'                => $ajax_nonce,
                    'acc_results_list'     => wp_json_encode($accs),
                    'validation_messages'   => $validation_messages
                );
                wp_localize_script('cbxwpsacc-accountmanager', 'cbxwpsimpleaccounting', $translation_array);

                //enqueue scripts
                wp_enqueue_script('jquery.validate.min');
                wp_enqueue_script('jquery.validate-additional-methods.min');
                wp_enqueue_script('chosen.jquery.min');
                wp_enqueue_script('cbxwpsacc-accountmanager');

            }


            //setting
	        if($current_page == 'cbxwpsimpleaccounting_settings'){
		        wp_enqueue_media();
		        wp_enqueue_script('jquery');
		        wp_enqueue_script('wp-color-picker');
		        wp_enqueue_script('jquery.validate.min');
		        wp_enqueue_script('jquery.validate-additional-methods.min');
		        wp_enqueue_script('chosen.jquery.min');

		        // Localize the script with new data
		        $translation_array = array(
			        'nonce'                => $ajax_nonce,
			        'category_create_success'                => esc_html__('Category created successfully.', 'cbxwpsimpleaccounting'),
			        'category_create_failed'                => esc_html__('Category Creation failed', 'cbxwpsimpleaccounting'),
			        'validation_messages'   => $validation_messages
		        );
		        wp_localize_script('cbxwpsimpleaccounting-setting', 'cbxwpsimpleaccounting', $translation_array);


		        wp_enqueue_script('cbxwpsimpleaccounting-setting');
	        }


	        //enqueue scripts for log manager page
	        wp_register_script('cbxwpsimpleaccountinglog', plugin_dir_url(__FILE__) . '../assets/js/cbxwpsimpleaccounting-log.js', array('jquery', 'flatpickr.min'), $this->version, true);
	        if($current_page == 'cbxwpsimpleaccountinglog'){
		        wp_enqueue_script('jquery');
		        wp_enqueue_script('flatpickr.min');

		        // Localize the script with new data and enqueue it
		        $translation_array = array(
			        'nonce'      => $ajax_nonce,
			        'permission' => esc_html__('This action can not be undone. Are you sure to delete this entry ?', 'cbxwpsimpleaccounting'),
			        'validation_messages'   => $validation_messages
		        );
		        wp_localize_script('cbxwpsimpleaccountinglog', 'cbxwpsimpleaccountinglog', $translation_array);
		        wp_enqueue_script('cbxwpsimpleaccountinglog');
	        }


        }//end method enqueue_scripts

        /**
         * Get plugin basename
         *
         * @since     1.0.0
         * @return    string    The basename of the plugin.
         */
        public function get_plugin_basename()
        {
            return $this->plugin_basename;
        }//end method get_plugin_basename




        /**
         * @param array $links Default settings links
         *
         * @return array
         */
        public function add_plugin_admin_page($links)
        {
            $new_links[] = '<a href="' . admin_url('admin.php?page=cbxwpsimpleaccounting_settings') . '">' . esc_html__('Settings', 'cbxwpsimpleaccounting') . '</a>';
            return array_merge($new_links, $links);
        }//end method add_plugin_admin_page

        /**
         * Add support link to plugin description in /wp-admin/plugins.php
         *
         * @param  array $plugin_meta
         * @param  string $plugin_file
         *
         * @return array
         */
        public function support_link($plugin_meta, $plugin_file)
        {

            if ($this->plugin_basename == $plugin_file) {
                $plugin_meta[] = sprintf(
                    '<a href="%s">%s</a>', esc_url('https://codeboxr.com/product/cbx-accounting/'), esc_html__('Support', 'cbxwpsimpleaccounting')
                );
            }

            return $plugin_meta;
        }


        /**
         * Add new Expense/Income(not in used now)
         *
         *
         * @since 1.0.0
         *
         * return string
         */
        public function add_new_expinc()
        {
            global $wpdb;

            $form_validation = true;
            $cbacnt_validation['error'] = false;
            $cbacnt_validation['field'] = array();

            //verify nonce field
            if (wp_verify_nonce($_POST['new_expinc_verifier'], 'add_new_expinc')) {

                if (!current_user_can('edit_cbxaccounting')) {
                    $cbacnt_validation['error'] = true;
                    $cbacnt_validation['msg'] = esc_html__('You don\'t have enough permission to add/edit Income/Expense.', 'cbxwpsimpleaccounting');

                    echo json_encode($cbacnt_validation);
                    wp_die();
                }

                if (isset($_POST['cbacnt-exinc-source-amount']) && !empty($_POST['cbacnt-exinc-source-amount'])) {
                    $source_amount = abs(floatval($_POST['cbacnt-exinc-source-amount']));
                } else {
                    $source_amount = null;
                }

                if (isset($_POST['cbacnt-exinc-currency']) && !empty($_POST['cbacnt-exinc-currency'])) {
                    $source_currency = sanitize_text_field($_POST['cbacnt-exinc-currency']);
                } else {
                    $source_currency = null;
                }

                $col_data = array(
                    'title'           => sanitize_text_field($_POST['cbacnt-exinc-title']),
                    'note'            => sanitize_text_field($_POST['cbacnt-exinc-note']),
                    'amount'          => abs(floatval($_POST['cbacnt-exinc-amount'])),
                    'type'            => absint($_POST['cbacnt-exinc-type']),
                    'source_amount'   => abs(floatval($_POST['cbacnt-exinc-source-amount'])),
                    'source_currency' => sanitize_text_field($_POST['cbacnt-exinc-currency']),
                    'account'         => abs($_POST['cbacnt-exinc-acc']),
                    'invoice'         => sanitize_text_field($_POST['cbacnt-exinc-invoice']),
                    'istaxincluded'   => 0,
                    'tax'             => 0
                );


                if (isset($_POST['cbacnt-exinc-include-tax']) && $_POST['cbacnt-exinc-include-tax'] == 1) {
                    $col_data['istaxincluded'] = 1;
                    $col_data['tax'] = abs(floatval($_POST['cbacnt-exinc-tax']));
                }


                $title_len = mb_strlen($col_data['title']);
                $note_len = mb_strlen($col_data['note']);

                //check expense/income title length is not less than 5 or more than 200 char
                if ($title_len < 5 || $title_len > 200) {
                    $form_validation = false;
                    $cbacnt_validation['error'] = true;
                    $cbacnt_validation['field'][] = 'cbacnt-exinc-title';
                    $cbacnt_validation['msg'] = esc_html__('The title field character limit must be between 5 to 200.', 'cbxwpsimpleaccounting');
                }

                //check expense/income note length is not less than 10 or more than 2000 char if provided
                if ((!empty($note_len) && $note_len < 10) || $note_len > 2000) {
                    $form_validation = false;
                    $cbacnt_validation['error'] = true;
                    $cbacnt_validation['field'][] = 'cbacnt-exinc-note';
                    $cbacnt_validation['msg'] = esc_html__('The note field character limit must be between 10 to 2000.', 'cbxwpsimpleaccounting');
                }

                //check expense/income is not less than 1
                if ($col_data['amount'] < 1) {
                    $form_validation = false;
                    $cbacnt_validation['error'] = true;
                    $cbacnt_validation['field'][] = 'cbacnt-exinc-amount';
                    $cbacnt_validation['msg'] = esc_html__('Amount must be greater than 0.00.', 'cbxwpsimpleaccounting');
                }
                //forcing user to chose one and only one category
                if (!isset($_POST['cbacnt-expinc-cat']) || (isset($_POST['cbacnt-expinc-cat']) && $_POST['cbacnt-expinc-cat'] == null) || count($_POST['cbacnt-expinc-cat'], COUNT_RECURSIVE) > 2) {
                    $form_validation = false;
                    $cbacnt_validation['error'] = true;
                    $cbacnt_validation['field'][] = 'cbacnt-exinc-category';
                    $cbacnt_validation['msg'] = esc_html__('Please select at least and only one category', 'cbxwpsimpleaccounting');
                }


                $exinc_id = absint($_POST['cbacnt-exinc-id']);
                $exinc_cat_list = isset($_POST['cbacnt-expinc-cat']) ? $_POST['cbacnt-expinc-cat'] : array();


                //check form passes all validation rules
                if ($form_validation) {
                    //edit mode
                    if ($exinc_id > 0) {
                        //check the expense/income exist with provided id
                        if ($wpdb->get_row(
                                $wpdb->prepare("SELECT `title` FROM `" . $wpdb->prefix . "cbaccounting_expinc` WHERE `id` = %d", $exinc_id), ARRAY_A
                            ) != null
                        ) {

                            $col_data['mod_by'] = get_current_user_id();
                            $col_data['add_date'] = (isset($_POST['cbacnt-exinc-add-date']) && $_POST['cbacnt-exinc-add-date'] != NULL) ? $_POST['cbacnt-exinc-add-date'] : current_time('mysql');
                            $col_data['mod_date'] = current_time('mysql');


                            $col_data = apply_filters('cbxwpsimpleaccounting_incexp_post_process', $col_data);

                            $where = array(
                                'id' => $exinc_id
                            );


                            $col_data_format = array('%s', '%s', '%f', '%d', '%f', '%s', '%d', '%s', '%d', '%f', '%s', '%s', '%s');
                            $col_data_format = apply_filters('cbxwpsimpleaccounting_incexp_post_coldataformat', $col_data_format);

                            $where_format = array('%d');

                            //start transaction
                            $wpdb->query('START TRANSACTION');

                            //matching update function return is false, then update failed.
                            if ($wpdb->update($wpdb->prefix . 'cbaccounting_expinc', $col_data, $where, $col_data_format, $where_format) === false) {
                                //update failed
                                $cbacnt_validation['error'] = true;
                                $cbacnt_validation['field'][] = '';
                                $cbacnt_validation['msg'] = esc_html__('Update failed', 'cbxwpsimpleaccounting');
                            } else {
                                //update successful. $item_insert
                                $item_del = $wpdb->delete($wpdb->prefix . 'cbaccounting_expcat_rel', array('expinc_id' => $exinc_id), $where_format);

                                $cat_list_value = array();
                                $cat_id_holder = array();
                                $place_holders = array();


                                foreach ($exinc_cat_list as $type_key => $type) {
                                    foreach ($type as $cat_id) {
                                        //$cat_id_holder[] = $type_key . $cat_id;
                                        $cat_id_holder[] = $cat_id;
                                        array_push($cat_list_value, null, $exinc_id, $cat_id);
                                        $place_holders[] = "( %d, %d, %d )";
                                    }
                                }

                                $query = 'INSERT INTO `' . $wpdb->prefix . 'cbaccounting_expcat_rel` ( `id`, `expinc_id`, `category_id` ) VALUES ';
                                $query .= implode(', ', $place_holders);
                                $item_cat_insert = $wpdb->query($wpdb->prepare($query, $cat_list_value));

                                if ($item_del && $item_cat_insert) {
                                    $wpdb->query('COMMIT');

                                    $msg = esc_html__('Item updated.', 'cbxwpsimpleaccounting');
                                    $msg .= ' <a data-id="' . $exinc_id . '" href="javascript:void(0);" class="button cbacnt-edit-exinc">';
                                    $msg .= esc_html__('Edit', 'cbxwpsimpleaccounting');
                                    $msg .= '</a>';
                                    $msg .= ' <a href="javascript:void(0);" class="button cbacnt-new-exinc">';
                                    $msg .= esc_html__('Add new', 'cbxwpsimpleaccounting');
                                    $msg .= '</a>';

                                    $cbacnt_validation['error'] = false;
                                    $cbacnt_validation['msg'] = $msg;
                                    $cbacnt_validation['form_value']['id'] = $exinc_id;
                                    $cbacnt_validation['form_value']['status'] = 'updated';
                                    $cbacnt_validation['form_value']['title'] = stripslashes(esc_attr($col_data['title']));
                                    $cbacnt_validation['form_value']['amount'] = $col_data['amount'];
                                    $cbacnt_validation['form_value']['source_amount'] = $col_data['source_amount'];
                                    $cbacnt_validation['form_value']['source_currency'] = $col_data['source_currency'];
                                    $cbacnt_validation['form_value']['account'] = $col_data['account'];
                                    $cbacnt_validation['form_value']['invoice'] = stripslashes(esc_attr($col_data['invoice']));
                                    $cbacnt_validation['form_value']['istaxincluded'] = $col_data['istaxincluded'];
                                    $cbacnt_validation['form_value']['tax'] = $col_data['tax'];
                                    $cbacnt_validation['form_value']['type'] = $col_data['type'];
                                    $cbacnt_validation['form_value']['note'] = stripslashes(esc_textarea($col_data['note']));
                                    $cbacnt_validation['form_value']['add_date'] = $col_data['add_date'];
                                    $cbacnt_validation['form_value']['cat_list'] = $cat_id_holder;

                                    $cbacnt_validation = apply_filters('cbxwpsimpleaccounting_incexp_post_data', $cbacnt_validation, $col_data);
                                } else { //new category insertion failed
                                    $wpdb->query('ROLLBACK');
                                    $cbacnt_validation['error'] = true;
                                    $cbacnt_validation['field'][] = '';
                                    $cbacnt_validation['msg'] = esc_html__('Error editing, please reload this page.', 'cbxwpsimpleaccounting');
                                }
                            }
                        } else { //if category doesn't exist with id
                            $cbacnt_validation['msg'] = esc_html__('You attempted to edit a log that doesn\'t exist.' . $exinc_id, 'cbxwpsimpleaccounting');
                        }
                    } else {
                        //add new

                        $col_data['add_by'] = get_current_user_id();

                        $col_data['add_date'] = (isset($_POST['cbacnt-exinc-add-date']) && $_POST['cbacnt-exinc-add-date'] != NULL) ? $_POST['cbacnt-exinc-add-date'] : current_time('mysql');

                        $col_data = apply_filters('cbxwpsimpleaccounting_incexp_post_process', $col_data);


                        $wpdb->query('START TRANSACTION');


                        $col_data_format = array('%s', '%s', '%f', '%d', '%f', '%s', '%d', '%s', '%d', '%f', '%d', '%s');
                        $col_data_format = apply_filters('cbxwpsimpleaccounting_incexp_post_coldataformat', $col_data_format);

                        $item_insert = $wpdb->insert($wpdb->prefix . 'cbaccounting_expinc', $col_data, $col_data_format);
                        $item_insert_id = $wpdb->insert_id;

                        $cat_list_value = array();
                        $cat_id_holder = array();
                        $place_holders = array();

                        foreach ($exinc_cat_list as $type_key => $type) {
                            foreach ($type as $cat_id) {
                                $cat_id_holder[] = $cat_id;
                                array_push($cat_list_value, null, $wpdb->insert_id, $cat_id);
                                $place_holders[] = "( %d, %d, %d )";
                            }
                        }

                        $query = 'INSERT INTO `' . $wpdb->prefix . 'cbaccounting_expcat_rel` ( `id`, `expinc_id`, `category_id` ) VALUES ';
                        $query .= implode(', ', $place_holders);
                        $item_cat_insert = $wpdb->query($wpdb->prepare($query, $cat_list_value));

                        //insert new expense/income
                        if ($item_insert && $item_cat_insert) {
                            $wpdb->query('COMMIT');
                            //new expense/income inserted successfully
                            $msg = esc_html__('A new item inserted.', 'cbxwpsimpleaccounting');
                            $msg .= ' <a data-id="' . $item_insert_id . '" href="javascript:void(0);" class="button cbacnt-edit-exinc">';
                            $msg .= esc_html__('Edit item.', 'cbxwpsimpleaccounting');
                            $msg .= '</a>';
                            $msg .= ' <a href="javascript:void(0);" class="button cbacnt-new-exinc">';
                            $msg .= esc_html__('Add new', 'cbxwpsimpleaccounting');
                            $msg .= '</a>';

                            $cbacnt_validation['error'] = false;
                            $cbacnt_validation['msg'] = $msg;
                            $cbacnt_validation['form_value']['id'] = $item_insert_id;
                            $cbacnt_validation['form_value']['status'] = 'new';
                            $cbacnt_validation['form_value']['title'] = stripslashes(esc_attr($col_data['title']));
                            $cbacnt_validation['form_value']['amount'] = $col_data['amount'];
                            $cbacnt_validation['form_value']['source_amount'] = $col_data['source_amount'];
                            $cbacnt_validation['form_value']['source_currency'] = $col_data['source_currency'];
                            $cbacnt_validation['form_value']['account'] = $col_data['account'];
                            $cbacnt_validation['form_value']['invoice'] = stripslashes(esc_attr($col_data['invoice']));
                            $cbacnt_validation['form_value']['istaxincluded'] = $col_data['istaxincluded'];
                            $cbacnt_validation['form_value']['tax'] = $col_data['tax'];
                            $cbacnt_validation['form_value']['note'] = stripslashes(esc_textarea($col_data['note']));
                            $cbacnt_validation['form_value']['type'] = $col_data['type'];
                            $cbacnt_validation['form_value']['cat_list'] = $cat_id_holder;

                            $cbacnt_validation = apply_filters('cbxwpsimpleaccounting_incexp_post_data', $cbacnt_validation, $col_data);
                        } else { //new category insertion failed
                            $wpdb->query('ROLLBACK');
                            $cbacnt_validation['error'] = true;
                            $cbacnt_validation['field'][] = '';
                            $cbacnt_validation['msg'] = esc_html__('Error adding, please reload this page.', 'cbxwpsimpleaccounting');
                        }
                    }
                }
            } else { //if wp_nonce not verified then entry here
                $cbacnt_validation['error'] = true;
                $cbacnt_validation['field'][] = 'wp_nonce';
                $cbacnt_validation['msg'] = esc_html__('Form is security validation error. Please reload page and try again.', 'cbxwpsimpleaccounting');
            }

	        wp_send_json($cbacnt_validation);
            //echo json_encode($cbacnt_validation);
            //wp_die();
        }

        /**
         * load income/expense via ajax request for edit(not in used)
         *
         */
        public function load_expinc()
        {
            global $wpdb;
            $form_validation = true;
            $cbacnt_validation['error'] = false;
            $cbacnt_validation['field'] = array();

            check_ajax_referer('cbxwpsimpleaccounting_nonce', 'nonce');

            if (!current_user_can('edit_cbxaccounting')) {
                $cbacnt_validation['error'] = true;
                $cbacnt_validation['msg'] = esc_html__('You don\'t have enough permission to edit Income/Expense. ', 'cbxwpsimpleaccounting');
                echo json_encode($cbacnt_validation);
                wp_die();
            }

            $id = absint($_POST['id']);
            //if provide the  id
            if ($id > 0) {

                $incexp = $wpdb->get_row($wpdb->prepare('SELECT *  FROM `' . $wpdb->prefix . 'cbaccounting_expinc` WHERE id = %d', $id), ARRAY_A);
                $incexpcatlist = $wpdb->get_results($wpdb->prepare('SELECT *  FROM `' . $wpdb->prefix . 'cbaccounting_expcat_rel` WHERE expinc_id = %d', $id), ARRAY_A);

                if ($incexpcatlist != null) {
                    foreach ($incexpcatlist as $list) {
                        $catlist[] = $list['category_id'];
                    }
                }

                if ($incexp != null) {

                    if (isset($incexp['protected']) && intval($incexp['protected']) == 1) {
                        $cbacnt_validation['error'] = true;
                        $cbacnt_validation['msg'] = esc_html__('This log entry is protected, created by 3rd party plugin and can not be edited.', 'cbxwpsimpleaccounting');
                    } else {
                        $cbacnt_validation['error'] = false;
                        $cbacnt_validation['msg'] = esc_html__('Data Loaded for edit', 'cbxwpsimpleaccounting');
                        $cbacnt_validation['form_value']['id'] = $id;
                        $cbacnt_validation['form_value']['status'] = 'loaded';
                        $cbacnt_validation['form_value']['title'] = stripslashes(esc_attr($incexp['title']));
                        $cbacnt_validation['form_value']['amount'] = floatval($incexp['amount']);
                        $cbacnt_validation['form_value']['source_amount'] = floatval($incexp['source_amount']);
                        $cbacnt_validation['form_value']['source_currency'] = $incexp['source_currency'];
                        $cbacnt_validation['form_value']['account'] = absint($incexp['account']);
                        $cbacnt_validation['form_value']['invoice'] = stripslashes(esc_attr($incexp['invoice']));
                        $cbacnt_validation['form_value']['istaxincluded'] = $incexp['istaxincluded'];
                        $cbacnt_validation['form_value']['tax'] = $incexp['tax'];
                        $cbacnt_validation['form_value']['cat_list'] = $catlist;
                        $cbacnt_validation['form_value']['type'] = absint($incexp['type']);
                        $cbacnt_validation['form_value']['note'] = stripslashes(esc_textarea($incexp['note']));
                        $cbacnt_validation['form_value']['add_date'] = $incexp['add_date'];

                        $cbacnt_validation = apply_filters('cbxwpsimpleaccounting_incexp_edit_data', $cbacnt_validation, $incexp);
                    }
                } else {
                    $cbacnt_validation['error'] = true;
                    $cbacnt_validation['msg'] = esc_html__('You attempted to edit item that doesn\'t exist. ', 'cbxwpsimpleaccounting');
                }
            } else { //if category is new then go here
                $cbacnt_validation['error'] = true;
                $cbacnt_validation['msg'] = esc_html__('You attempted to edit item that doesn\'t exist. ', 'cbxwpsimpleaccounting');
            }

            echo json_encode($cbacnt_validation);
            wp_die();
        }

        /**
         * Ajax call for deleting a single log
         *
         */
        public function delete_expinc()
        {
            global $wpdb;

            check_ajax_referer('cbxwpsimpleaccounting_nonce', 'security');

            if (!current_user_can('delete_cbxaccounting')) {
                $cbacnt_validation['error'] = true;
                $cbacnt_validation['msg'] = esc_html__('You don\'t have enough permission to delete Income/Expense.', 'cbxwpsimpleaccounting');

                echo json_encode($cbacnt_validation);
                wp_die();
            }
            $cbxexpinc_table = $wpdb->prefix . 'cbaccounting_expinc'; //income expense log table
            $expcat_rel_table = $wpdb->prefix . 'cbaccounting_expcat_rel'; //cat incexp rel table

	        $expinc_deletion = array();

            if (isset($_POST['id'])) {
            	$id = intval($_POST['id']);

	            //pre-process hook before delete
	            do_action('cbxwpsimpleaccounting_log_delete_before', $id);
                $deleted_id = $wpdb->delete($cbxexpinc_table, array('id' => $_POST['id']), array('%d'));
	            if ($deleted_id !== false) {

		            //post process hook on successful delete
		            do_action('cbxwpsimpleaccounting_log_delete_after', $id);


		            //delete from category incexpense rel table after delete any log
		            //pre-process hook before delete
		            do_action('cbxwpsimpleaccounting_log_rel_delete_before', $id);
		            $delete_cat_rel = $wpdb->delete($expcat_rel_table, array('expinc_id' => $deleted_id), array('%d'));
		            if($delete_cat_rel !== false){
			            //post-process hook before delete
			            do_action('cbxwpsimpleaccounting_log_rel_delete_after', $id);
		            }
		            else{
			            //post-process hook before delete
			            do_action('cbxwpsimpleaccounting_log_rel_delete_failed', $id);
		            }

		            $expinc_deletion['error'] = false;
		            $expinc_deletion['msg'] = esc_html__('Item Deleted. ', 'cbxwpsimpleaccounting');
	            } else {
		            //post-process hook on delete failed of log rel
		            do_action('cbxwpsimpleaccounting_log_delete_failed', $id);

		            $expinc_deletion['error'] = true;
		            $expinc_deletion['msg'] = esc_html__('Cannot Delete Item. ', 'cbxwpsimpleaccounting');
	            }
            }



            echo json_encode($expinc_deletion);

            wp_die(); // this is required to terminate immediately and return a proper response
        }

        /**
         * Add/edit Expense or Income log
         *
         * return string
         */
        public function add_edit_expinc() {
            if (isset($_POST['cbxwpsimpleaccounting_expinc_addedit']) && intval($_POST['cbxwpsimpleaccounting_expinc_addedit']) === 1) {

                global $wpdb;

                $form_validation = true;
                $cbacnt_validation['error'] = false;
                $cbacnt_validation['field'] = array();

                //verify nonce field
                if (wp_verify_nonce($_POST['new_expinc_verifier'], 'add_new_expinc')) {

                    if (!current_user_can('edit_cbxaccounting')) {
                        $cbacnt_validation['error'] = true;
                        $cbacnt_validation['msg'] = esc_html__('You don\'t have enough permission to add/edit Income/Expense.', 'cbxwpsimpleaccounting');

//                    echo json_encode($cbacnt_validation);
//                    wp_die();
                    }

                    if (isset($_POST['cbacnt-exinc-source-amount']) && !empty($_POST['cbacnt-exinc-source-amount'])) {
                        $source_amount = abs(floatval($_POST['cbacnt-exinc-source-amount']));
                    } else {
                        $source_amount = null;
                    }

                    if (isset($_POST['cbacnt-exinc-currency']) && !empty($_POST['cbacnt-exinc-currency'])) {
                        $source_currency = sanitize_text_field($_POST['cbacnt-exinc-currency']);
                    } else {
                        $source_currency = null;
                    }

                    $col_data = array(
                        'title'           => sanitize_text_field($_POST['cbacnt-exinc-title']),
                        'note'            => sanitize_text_field($_POST['cbacnt-exinc-note']),
                        'amount'          => abs(floatval($_POST['cbacnt-exinc-amount'])),
                        'type'            => absint($_POST['cbacnt-exinc-type']),
                        'source_amount'   => abs(floatval($_POST['cbacnt-exinc-source-amount'])),
                        'source_currency' => sanitize_text_field($_POST['cbacnt-exinc-currency']),
                        'account'         => abs($_POST['cbacnt-exinc-acc']),
                        'invoice'         => sanitize_text_field($_POST['cbacnt-exinc-invoice']),
                        'istaxincluded'   => 0,
                        'tax'             => 0
                    );


                    if (isset($_POST['cbacnt-exinc-include-tax']) && $_POST['cbacnt-exinc-include-tax'] == 1) {
                        $col_data['istaxincluded'] = 1;
                        $col_data['tax'] = abs(floatval($_POST['cbacnt-exinc-tax']));
                    }


                    $title_len = mb_strlen($col_data['title']);
                    $note_len = mb_strlen($col_data['note']);

                    //check expense/income title length is not less than 5 or more than 200 char
                    if ($title_len < 5 || $title_len > 200) {
                        $form_validation = false;
                        $cbacnt_validation['error'] = true;
                        $cbacnt_validation['field'][] = 'cbacnt-exinc-title';
                        $cbacnt_validation['msg'] = esc_html__('The title field character limit must be between 5 to 200.', 'cbxwpsimpleaccounting');
                    }

                    //check expense/income note length is not less than 10 or more than 2000 char if provided
                    if ((!empty($note_len) && $note_len < 10) || $note_len > 2000) {
                        $form_validation = false;
                        $cbacnt_validation['error'] = true;
                        $cbacnt_validation['field'][] = 'cbacnt-exinc-note';
                        $cbacnt_validation['msg'] = esc_html__('The note field character limit must be between 10 to 2000.', 'cbxwpsimpleaccounting');
                    }

                    //check expense/income is not less than 1

                    if ($col_data['amount'] <= 0) {
                        $form_validation = false;
                        $cbacnt_validation['error'] = true;
                        $cbacnt_validation['field'][] = 'cbacnt-exinc-amount';
                        $cbacnt_validation['msg'] = esc_html__('Amount must be greater than 0.00.', 'cbxwpsimpleaccounting');
                    }

                    //forcing user to chose one and only one category
                    if (!isset($_POST['cbacnt-expinc-cat']) || (isset($_POST['cbacnt-expinc-cat']) && $_POST['cbacnt-expinc-cat'] == null) || count($_POST['cbacnt-expinc-cat'], COUNT_RECURSIVE) > 2) {
                        $form_validation = false;
                        $cbacnt_validation['error'] = true;
                        $cbacnt_validation['field'][] = 'cbacnt-exinc-category';
                        $cbacnt_validation['msg'] = esc_html__('Please select at least and only one category', 'cbxwpsimpleaccounting');
                    }


                    $exinc_id = absint($_POST['cbacnt-exinc-id']);
                    $exinc_cat_list = isset($_POST['cbacnt-expinc-cat']) ? $_POST['cbacnt-expinc-cat'] : array();


                    //check form passes all validation rules
                    if ($form_validation) {
                        //edit mode
                        if ($exinc_id > 0) {
                            //check the expense/income exist with provided id
                            if ($wpdb->get_row(
                                    $wpdb->prepare("SELECT `title` FROM `" . $wpdb->prefix . "cbaccounting_expinc` WHERE `id` = %d", $exinc_id), ARRAY_A
                                ) != null
                            ) {

                                $col_data['mod_by'] = get_current_user_id();
                                $col_data['add_date'] = (isset($_POST['cbacnt-exinc-add-date']) && $_POST['cbacnt-exinc-add-date'] != NULL) ? $_POST['cbacnt-exinc-add-date'] : current_time('mysql');
                                $col_data['mod_date'] = current_time('mysql');


                                $col_data = apply_filters('cbxwpsimpleaccounting_incexp_post_process', $col_data);

                                $where = array(
                                    'id' => $exinc_id
                                );




                                $col_data_format = array('%s', '%s', '%f', '%d', '%f', '%s', '%d', '%s', '%d', '%f', '%s', '%s', '%s');
                                $col_data_format = apply_filters('cbxwpsimpleaccounting_incexp_post_coldataformat', $col_data_format);

                                $where_format = array('%d');


                                //start transaction
                                $wpdb->query('START TRANSACTION');

                                //matching update function return is false, then update failed.
                                if ($wpdb->update($wpdb->prefix . 'cbaccounting_expinc', $col_data, $where, $col_data_format, $where_format) === false) {
                                    //update failed
                                    $cbacnt_validation['error'] = true;
                                    $cbacnt_validation['field'][] = '';
                                    $cbacnt_validation['msg'] = esc_html__('Update failed', 'cbxwpsimpleaccounting');
                                } else {
                                    //update successful. $item_insert
                                    $item_del = $wpdb->delete($wpdb->prefix . 'cbaccounting_expcat_rel', array('expinc_id' => $exinc_id), $where_format);

                                    $cat_list_value = array();
                                    $cat_id_holder = array();
                                    $place_holders = array();


                                    foreach ($exinc_cat_list as $type_key => $type) {
                                        foreach ($type as $cat_id) {
                                            //$cat_id_holder[] = $type_key . $cat_id;
                                            $cat_id_holder[] = $cat_id;
                                            array_push($cat_list_value, null, $exinc_id, $cat_id);
                                            $place_holders[] = "( %d, %d, %d )";
                                        }
                                    }

                                    $query = 'INSERT INTO `' . $wpdb->prefix . 'cbaccounting_expcat_rel` ( `id`, `expinc_id`, `category_id` ) VALUES ';
                                    $query .= implode(', ', $place_holders);
                                    $item_cat_insert = $wpdb->query($wpdb->prepare($query, $cat_list_value));

                                    if ($item_del && $item_cat_insert) {
                                        $wpdb->query('COMMIT');

                                        $msg = esc_html__('Item updated.', 'cbxwpsimpleaccounting');

                                        $cbacnt_validation['error'] = false;
                                        $cbacnt_validation['msg'] = $msg;

//                                    $cbacnt_validation = apply_filters('cbxwpsimpleaccounting_incexp_post_data', $cbacnt_validation, $col_data);
                                    } else { //new category insertion failed
                                        $wpdb->query('ROLLBACK');
                                        $cbacnt_validation['error'] = true;
                                        $cbacnt_validation['field'][] = '';
                                        $cbacnt_validation['msg'] = esc_html__('Error editing, please reload this page.', 'cbxwpsimpleaccounting');
                                    }
                                }
                            } else { //if category doesn't exist with id
                                $cbacnt_validation['msg'] = esc_html__('You attempted to edit a log that doesn\'t exist.' . $exinc_id, 'cbxwpsimpleaccounting');
                            }
                        } else {
                            //add new

                            $col_data['add_by'] = get_current_user_id();

                            $col_data['add_date'] = (isset($_POST['cbacnt-exinc-add-date']) && $_POST['cbacnt-exinc-add-date'] != NULL) ? $_POST['cbacnt-exinc-add-date'] : current_time('mysql');

                            $col_data = apply_filters('cbxwpsimpleaccounting_incexp_post_process', $col_data);


                            $wpdb->query('START TRANSACTION');


                            $col_data_format = array('%s', '%s', '%f', '%d', '%f', '%s', '%d', '%s', '%d', '%f', '%d', '%s');
                            $col_data_format = apply_filters('cbxwpsimpleaccounting_incexp_post_coldataformat', $col_data_format);



                            $item_insert = $wpdb->insert($wpdb->prefix . 'cbaccounting_expinc', $col_data, $col_data_format);
                            $item_insert_id = $wpdb->insert_id;

	                        $exinc_id = $item_insert_id;

                            $cat_list_value = array();
                            $cat_id_holder = array();
                            $place_holders = array();

                            foreach ($exinc_cat_list as $type_key => $type) {
                                foreach ($type as $cat_id) {
                                    $cat_id_holder[] = $cat_id;
                                    array_push($cat_list_value, null, $wpdb->insert_id, $cat_id);
                                    $place_holders[] = "( %d, %d, %d )";
                                }
                            }

                            $query = 'INSERT INTO `' . $wpdb->prefix . 'cbaccounting_expcat_rel` ( `id`, `expinc_id`, `category_id` ) VALUES ';
                            $query .= implode(', ', $place_holders);
                            $item_cat_insert = $wpdb->query($wpdb->prepare($query, $cat_list_value));

                            //insert new expense/income
                            if ($item_insert && $item_cat_insert) {
                                $wpdb->query('COMMIT');
                                //new expense/income inserted successfully
                                $msg = esc_html__('A new item inserted.', 'cbxwpsimpleaccounting');

                                $cbacnt_validation['error'] = false;
                                $cbacnt_validation['msg'] = $msg;

//                            $cbacnt_validation = apply_filters('cbxwpsimpleaccounting_incexp_post_data', $cbacnt_validation, $col_data);
                            } else { //new category insertion failed
                                $wpdb->query('ROLLBACK');
                                $cbacnt_validation['error'] = true;
                                $cbacnt_validation['field'][] = '';
                                $cbacnt_validation['msg'] = esc_html__('Error adding, please reload this page.', 'cbxwpsimpleaccounting');
                            }
                        }
                    }
                } else { //if wp_nonce not verified then entry here
                    $cbacnt_validation['error'] = true;
                    $cbacnt_validation['field'][] = 'wp_nonce';
                    $cbacnt_validation['msg'] = esc_html__('Form is security validation error. Please reload page and try again.', 'cbxwpsimpleaccounting');
                }

                $_SESSION['cbx_exp_inc_response'][] = $cbacnt_validation;
	//          echo json_encode($cbacnt_validation);
	//          wp_die();

	            $redirect_url = 'admin.php?page=cbxwpsimpleaccounting_addexpinc&id='.$exinc_id;

	            wp_safe_redirect(admin_url($redirect_url));
	            exit;

            }
        }

	    /**
	     * Add/edit Expense or Income Category
	     *
	     * return string
	     */
	    public function add_edit_category()
	    {
		    if (isset($_POST['cbxwpsimpleaccounting_cat_addedit']) && intval($_POST['cbxwpsimpleaccounting_cat_addedit']) == 1) {

			    global $wpdb;
			    $redirect_url = 'admin.php?page=cbxwpsimpleaccounting_cat&view=addedit';
			    $form_validated = true;
			    $validation['error'] = false;
			    $validation['field'] = array();

			    $isAjax = isset($_POST['cbxwpsimpleaccounting_cat_addedit_ajax']) ? true : false;

			    $cbxacc_table_name = $wpdb->prefix . 'cbaccounting_category';


			    //verify nonce field
			    if (wp_verify_nonce($_POST['new_cat_verifier'], 'add_new_expinc_cat')) {

				    $col_data = array(
					    'title' => sanitize_text_field($_POST['title']),
					    'type'  => absint($_POST['type']),
					    'color' => sanitize_text_field($_POST['color']),
					    'note'  => sanitize_text_field($_POST['note'])
				    );

				    $cat_id = absint($_POST['id']);
				    $title_len = mb_strlen($col_data['title']);
				    $note_len = mb_strlen($col_data['note']);
				    $cat_title = $col_data['title'];
				    $cat_color = $col_data['color'];

				    //see if category name already saved/exist
				    $query = $wpdb->prepare('SELECT COUNT(*) FROM ' . $cbxacc_table_name . ' WHERE id != %d AND title = %s ', $cat_id, $cat_title);
				    $cbxacc_cattitle_check = $wpdb->get_var($query);

				    //see if category color already saved/exist
				    $query = $wpdb->prepare('SELECT COUNT(*) FROM ' . $cbxacc_table_name . ' WHERE id != %d AND color = %s ', $cat_id, $cat_color);
				    $cbxacc_catcolor_check = $wpdb->get_var($query);

				    //check same category title
				    if ($cbxacc_cattitle_check != 0) {
					    $form_validated = false;
					    $validation['error'] = true;
					    $validation['field'][] = 'title';
					    $validation['msg'] = esc_html__('The Category title is already in use.', 'cbxwpsimpleaccounting');
				    }
				    //check same category color
				    if ($cbxacc_catcolor_check != 0) {
					    $form_validated = false;
					    $validation['error'] = true;
					    $validation['field'][] = 'color';
					    $validation['msg'] = esc_html__('The Category color is already in use.', 'cbxwpsimpleaccounting');
				    }
				    //check category title length is not less than 5 or more than 200 char
				    if ($title_len < 5 || $title_len > 200) {
					    $form_validated = false;
					    $validation['error'] = true;
					    $validation['field'][] = 'title';
					    $validation['msg'] = esc_html__('The title field character limit must be between 5 to 200.', 'cbxwpsimpleaccounting');
				    }
				    //check category note length is not less than 10 or more than 2000 char if provided
				    if ((!empty($note_len) && $note_len < 10) || $note_len > 2000) {
					    $form_validated = false;
					    $validation['error'] = true;
					    $validation['field'][] = 'note';
					    $validation['msg'] = esc_html__('The note field character limit must be between 10 to 2000.', 'cbxwpsimpleaccounting');
				    }

				    //check form passes all validation rules
				    if ($form_validated) {
					    //edit mode
					    if ($cat_id > 0) {
						    //check the category exist with provided id
						    if ($wpdb->get_row(
								    $wpdb->prepare('SELECT title FROM `' . $wpdb->prefix . 'cbaccounting_category` WHERE id = %d', $cat_id), ARRAY_A
							    ) != null
						    ) {

							    $col_data['mod_by'] = get_current_user_id();
							    $col_data['mod_date'] = current_time('mysql');

							    //title, type, color, note, mod_by, mod_date
							    $col_data_format = array('%s', '%d', '%s', '%s', '%d', '%s');

							    $where = array(
								    'id' => $cat_id
							    );

							    $where_format = array('%d');

							    //matching update function return is false, then update failed.
							    if ($wpdb->update($wpdb->prefix . 'cbaccounting_category', $col_data, $where, $col_data_format, $where_format) === false) {
								    //update failed
								    $validation['msg'] = esc_html__('Sorry! you don\'t have enough permission to update category.', 'cbxwpsimpleaccounting');
							    } else {
								    //update successful
								    $msg = esc_html__('Category updated successfully.', 'cbxwpsimpleaccounting');
								    $msg .= ' <a  href="#catsubmit" class="btn btn-default">';
								    $msg .= esc_html__('Edit Again', 'cbxwpsimpleaccounting');
								    $msg .= '</a>';
								    $msg .= ' <a  href="' . admin_url($redirect_url) . '" class="btn btn-primary">';
								    $msg .= esc_html__('Create new category', 'cbxwpsimpleaccounting');
								    $msg .= '</a>';

								    $validation['error'] = false;
								    $validation['msg'] = $msg;
								    $validation['data']['id'] = $cat_id;
								    $validation['data']['status'] = 'updated';
								    $validation['data']['title'] = stripslashes(esc_attr(($col_data['title'])));
								    $validation['data']['type'] = $col_data['type'];
								    $validation['data']['color'] = $col_data['color'];
								    $validation['data']['note'] = stripslashes(esc_html(($col_data['note'])));

							    }
						    } else { //if category doesn't exist with id
							    $validation['error'] = true;
							    $validation['msg'] = esc_html__('You attempted to edit the category that doesn\'t exist. ', 'cbxwpsimpleaccounting');
						    }
					    } else { //if category is new then go here
						    $col_data['add_by'] = get_current_user_id();
						    $col_data['add_date'] = current_time('mysql');

						    //title, type, color, note, add_by
						    $col_data_format = array('%s', '%d', '%s', '%s', '%d');
						    //insert new category
						    if ($wpdb->insert($wpdb->prefix . 'cbaccounting_category', $col_data, $col_data_format)) {
							    //new category inserted successfully

							    $cat_id = $wpdb->insert_id;
							    $msg = esc_html__('Category created successfully.', 'cbxwpsimpleaccounting');
							    $msg .= ' <a  href="' . admin_url($redirect_url . '&id=' . $cat_id) . '" class="btn btn-primary">';
							    $msg .= esc_html__('Edit', 'cbxwpsimpleaccounting');
							    $msg .= '</a>';

							    $validation['error'] = false;
							    $validation['msg'] = $msg;
							    $validation['data']['id'] = $cat_id;
							    $validation['data']['status'] = 'new';
							    $validation['data']['title'] = stripslashes(esc_attr($col_data['title']));
							    $validation['data']['type'] = $col_data['type'];
							    $validation['data']['color'] = $col_data['color'];
							    $validation['date']['note'] = stripslashes(esc_html($col_data['note']));
						    } else { //new category insertion failed
							    $validation['error'] = true;
							    $validation['msg'] = esc_html__('Error creating category', 'cbxwpsimpleaccounting');
						    }
					    }
				    }
			    } else { //if wp_nonce not verified then entry here
				    $validation['error'] = true;
				    $validation['field'][] = 'wp_nonce';
				    $validation['msg'] = esc_html__('Hacking attempt ?', 'cbxwpsimpleaccounting');
			    }


			    if ($isAjax) {
				    echo json_encode($validation);
				    wp_die();
			    } else {
				    $_SESSION['cbxwpsimpleaccounting_cat_addedit_error'] = $validation;

				    if ($cat_id > 0) {
					    $redirect_url .= '&id=' . $cat_id;
				    }

				    wp_safe_redirect(admin_url($redirect_url));
				    exit;
			    }

		    }
	    }

        /**
         * load category via ajax request(not in used)
         *
         */
        public function load_expinc_cat()
        {
            global $wpdb;
            $form_validation = true;
            $cbacnt_validation['error'] = false;
            $cbacnt_validation['field'] = array();

            check_ajax_referer('cbxwpsimpleaccounting_nonce', 'nonce');

            $cat_id = absint($_POST['catid']);
            //if provide the category id
            if ($cat_id > 0) {
                //check the category exist with provided id
                $category = $wpdb->get_row($wpdb->prepare('SELECT *  FROM `' . $wpdb->prefix . 'cbaccounting_category` WHERE id = %d', $cat_id), ARRAY_A);
                if ($category != null) {

                    if (isset($category['protected']) && intval($category['protected']) == 1) {
                        $cbacnt_validation['error'] = true;
                        $cbacnt_validation['msg'] = esc_html__('This is a protected Category created by 3rd party plugin and you can not edit this.', 'cbxwpsimpleaccounting');
                    } else {
                        $cbacnt_validation['error'] = false;
                        $cbacnt_validation['msg'] = esc_html__('Category loaded for edit', 'cbxwpsimpleaccounting');
                        $cbacnt_validation['form_value']['id'] = $cat_id;
                        $cbacnt_validation['form_value']['status'] = 'loaded';
                        $cbacnt_validation['form_value']['title'] = stripslashes($category['title']);
                        $cbacnt_validation['form_value']['type'] = $category['type'];
                        $cbacnt_validation['form_value']['color'] = (($category['color']) != NULL) ? $category['color'] : '#333333';
                        $cbacnt_validation['form_value']['note'] = stripslashes($category['note']);
                    }

                } else {
                    $cbacnt_validation['error'] = true;
                    $cbacnt_validation['msg'] = esc_html__('You attempted to load a category that doesn\'t exist. ', 'cbxwpsimpleaccounting');
                }
            } else { //if category is new then go here
                $cbacnt_validation['error'] = true;
                $cbacnt_validation['msg'] = esc_html__('You attempted to edit the category that doesn\'t exist. ', 'cbxwpsimpleaccounting');
            }

            echo json_encode($cbacnt_validation);
            wp_die();
        }

        /**
         * Add/edit Account
         *
         * return string
         */
        public function add_edit_account()
        {
            if (isset($_POST['cbxwpsimpleaccounting_acc_addedit']) && intval($_POST['cbxwpsimpleaccounting_acc_addedit']) == 1) {

                global $wpdb;
                $form_validation = true;
                $cbacnt_validation['error'] = false;
                $cbacnt_validation['field'] = array();
                $cbxacc_table_name = $wpdb->prefix . 'cbaccounting_account_manager';

                $redirect_url = 'admin.php?page=cbxwpsimpleaccounting_accmanager&view=addedit';

                //verify nonce field
                if (wp_verify_nonce($_POST['new_acc_verifier'], 'add_new_acc')) {

                    if ($_POST['cbacnt-acc-type'] == 'bank') {
                        $col_data = array(
                            'title'       => sanitize_text_field($_POST['cbacnt-acc-title']),
                            'type'        => sanitize_text_field($_POST['cbacnt-acc-type']),
                            'acc_no'      => sanitize_text_field($_POST['cbacnt-acc-acc-no']),
                            'acc_name'    => sanitize_text_field($_POST['cbacnt-acc-acc-name']),
                            'bank_name'   => sanitize_text_field($_POST['cbacnt-acc-bank-name']),
                            'branch_name' => sanitize_text_field($_POST['cbacnt-acc-branch-name'])
                        );
                    }

                    if ($_POST['cbacnt-acc-type'] == 'cash') {
                        $col_data = array(
                            'title' => sanitize_text_field($_POST['cbacnt-acc-title']),
                            'type'  => sanitize_text_field($_POST['cbacnt-acc-type'])
                        );
                    }

                    $account_id = absint($_POST['cbacnt-acc-id']);
                    $title_len = mb_strlen($col_data['title']);
                    $account_title = $col_data['title'];

                    //see if account name already saved/exist
                    $query = $wpdb->prepare('SELECT COUNT(*) FROM ' . $cbxacc_table_name . ' WHERE id != %d AND title = %s ', $account_id, $account_title);
                    $cbxacc_account_check = $wpdb->get_var($query);

                    //check same category title
                    if ($cbxacc_account_check != 0) {
                        $form_validation = false;
                        $cbacnt_validation['error'] = true;
                        $cbacnt_validation['field'][] = 'cbacnt-cat-color';
                        $cbacnt_validation['msg'] = esc_html__('The Account title is already in use.', 'cbxwpsimpleaccounting');
                    }

                    //check category title length is not less than 5 or more than 200 char
                    if ($title_len < 5 || $title_len > 200) {
                        $form_validation = false;
                        $cbacnt_validation['error'] = true;
                        $cbacnt_validation['field'][] = 'cbacnt-acc-title';
                        $cbacnt_validation['msg'] = esc_html__('The title field character limit must be between 5 to 200.', 'cbxwpsimpleaccounting');
                    }

                    //check form passes all validation rules
                    if ($form_validation) {
                        //edit mode
                        if ($account_id > 0) {
                            //check the category exist with provided id
                            if ($wpdb->get_row(
                                    $wpdb->prepare('SELECT title FROM `' . $wpdb->prefix . 'cbaccounting_account_manager` WHERE id = %d', $account_id), ARRAY_A
                                ) != null
                            ) {

                                $col_data['mod_by'] = get_current_user_id();
                                $col_data['mod_date'] = current_time('mysql');

                                if ($col_data['type'] == 'bank') {
                                    //title, type, acc_no, acc_name, bank_name, branch_name
                                    $col_data_format = array('%s', '%s', '%s', '%s', '%s', '%s', '%d', '%s');
                                }

                                if ($col_data['type'] == 'cash') {
                                    //title, type
                                    $col_data_format = array('%s', '%s', '%d', '%s');
                                }

                                $where = array(
                                    'id' => $account_id
                                );

                                $where_format = array('%d');

                                //matching update function return is false, then update failed.
                                if ($wpdb->update($wpdb->prefix . 'cbaccounting_account_manager', $col_data, $where, $col_data_format, $where_format) === false) {
                                    //update failed
                                    $cbacnt_validation['msg'] = esc_html__('Sorry! you don\'t have enough permission to update account.', 'cbxwpsimpleaccounting');
                                } else {
                                    //update successful
                                    $msg = esc_html__('Account updated.', 'cbxwpsimpleaccounting');

                                    $cbacnt_validation['error'] = false;
                                    $cbacnt_validation['msg'] = $msg;
                                }
                            } else { //if category doesn't exist with id
                                $cbacnt_validation['error'] = true;
                                $cbacnt_validation['msg'] = esc_html__('You attempted to edit the account that doesn\'t exist. ', 'cbxwpsimpleaccounting');
                            }
                        } else { //if category is new then go here
                            $col_data['add_by'] = get_current_user_id();
                            $col_data['add_date'] = current_time('mysql');

                            if ($col_data['type'] == 'bank') {
                                //title, type, acc_no, acc_name, bank_name, branch_name
                                $col_data_format = array('%s', '%s', '%s', '%s', '%s', '%s', '%d', '%s');
                            }

                            if ($col_data['type'] == 'cash') {
                                //title, type
                                $col_data_format = array('%s', '%s', '%d', '%s');
                            }

                            //insert new account
                            if ($wpdb->insert($wpdb->prefix . 'cbaccounting_account_manager', $col_data, $col_data_format)) {

                                //new account inserted successfully

                                $account_id = $wpdb->insert_id;

                                $msg = esc_html__('Account created successfully.', 'cbxwpsimpleaccounting');

                                $cbacnt_validation['error'] = false;
                                $cbacnt_validation['msg'] = $msg;
                            } else { //new category insertion failed
                                $cbacnt_validation['error'] = true;
                                $cbacnt_validation['msg'] = esc_html__('Sorry! you don\'t have enough permission to insert new account.', 'cbxwpsimpleaccounting');
                            }
                        }
                    }
                } else { //if wp_nonce not verified then entry here
                    $cbacnt_validation['error'] = true;
                    $cbacnt_validation['field'][] = 'wp_nonce';
                    $cbacnt_validation['msg'] = esc_html__('Hacking attempt ?', 'cbxwpsimpleaccounting');
                }

                $_SESSION['cbxwpsimpleaccounting_log_addeditresponse'][] = $cbacnt_validation;

                if ($account_id > 0) {
                    $redirect_url .= '&id=' . $account_id;
                }

                wp_safe_redirect(admin_url($redirect_url));
                exit;
            }
        }

        /**
         * load account via ajax request(not in used)
         *
         */
        public function load_account()
        {
            global $wpdb;
            $form_validation = true;
            $cbacnt_validation['error'] = false;
            $cbacnt_validation['field'] = array();

            //check_ajax_referer('cbxwpsimpleaccounting_nonce', 'nonce');

            $acc_id = absint($_POST['accid']);

            //if provide the category id
            if ($acc_id > 0) {
                //check the category exist with provided id
                $account = $wpdb->get_row($wpdb->prepare('SELECT *  FROM `' . $wpdb->prefix . 'cbaccounting_account_manager` WHERE id = %d', $acc_id), ARRAY_A);
                if ($account != null) {

                    $cbacnt_validation['error'] = false;
                    $cbacnt_validation['msg'] = 'Account loaded for edit';

                    $cbacnt_validation['form_value']['id'] = $acc_id;
                    $cbacnt_validation['form_value']['status'] = 'loaded';
                    $cbacnt_validation['form_value']['title'] = stripslashes($account['title']);
                    $cbacnt_validation['form_value']['type'] = $account['type'];

                    if ($account['type'] == 'bank') {
                        $cbacnt_validation['form_value']['acc_no'] = stripslashes($account['acc_no']);
                        $cbacnt_validation['form_value']['acc_name'] = stripslashes($account['acc_name']);
                        $cbacnt_validation['form_value']['bank_name'] = stripslashes($account['bank_name']);
                        $cbacnt_validation['form_value']['branch_name'] = stripslashes($account['branch_name']);
                    }
                } else {
                    $cbacnt_validation['error'] = true;
                    $cbacnt_validation['msg'] = esc_html__('You attempted to load an account that doesn\'t exist. ', 'cbxwpsimpleaccounting');
                }
            } else { //if category is new then go here
                $cbacnt_validation['error'] = true;
                $cbacnt_validation['msg'] = esc_html__('You attempted to edit the account that doesn\'t exist. ', 'cbxwpsimpleaccounting');
            }

            echo json_encode($cbacnt_validation);
            wp_die();
        }

        /**
         * Get month data for chart
         * @param type $year
         */
        public function get_month_data($month, $year)
        {

            global $wpdb;

            $total_this_month_income = $total_this_month_expense = $total_this_month_tax = $total_one_month_income = $total_one_month_expense = $one_month_tax = 0;
            $daywise_income1 = array();
            $daywise_expense1 = array();

            $month_days_array = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);

            $this_month_income = $wpdb->get_results('SELECT c . * , cat.id as catid , cat.title as cattitle, cat.color as catcolor
FROM  `' . $wpdb->prefix . 'cbaccounting_expinc` c
LEFT JOIN  `' . $wpdb->prefix . 'cbaccounting_expcat_rel` catrel ON c.id = catrel.expinc_id
LEFT JOIN  `' . $wpdb->prefix . 'cbaccounting_category` cat ON catrel.category_id = cat.id
WHERE c.type = 1 AND MONTH(c.add_date) = ' . $month . ' AND YEAR(c.add_date) = ' . $year);


            foreach ($this_month_income as $key => $value) {
                $timestamp = strtotime($value->add_date);

                if (array_key_exists(intval(date("d", $timestamp)), $daywise_income1)) {

                    $daywise_income1[intval(date("d", $timestamp))] += floatval($value->amount);

                    if ($value->istaxincluded) {
                        $tax = $value->amount * ($value->tax / 100);
                        $daywise_income1[intval(date("d", $timestamp))] += $tax;
                    }
                } else {

                    $daywise_income1[intval(date("d", $timestamp))] = floatval($value->amount);

                    if ($value->istaxincluded) {
                        $tax = $value->amount * ($value->tax / 100);
                        $daywise_income1[intval(date("d", $timestamp))] += $tax;
                    }
                }
            }

            for ($i = 1; $i <= $month_days_array[intval(date('m')) - 1]; $i++) {

                if (array_key_exists($i, $daywise_income1)) {
                    $daywise_income2[$i] = $daywise_income1[$i];
                } else {
                    $daywise_income2[$i] = 0;
                }
            }


            $this_month_expense = $wpdb->get_results('SELECT c . * , cat.id as catid, cat.title as cattitle, cat.color as catcolor
FROM  `' . $wpdb->prefix . 'cbaccounting_expinc` c
LEFT JOIN  `' . $wpdb->prefix . 'cbaccounting_expcat_rel` catrel ON c.id = catrel.expinc_id
LEFT JOIN  `' . $wpdb->prefix . 'cbaccounting_category` cat ON catrel.category_id = cat.id
WHERE c.type = 2 AND MONTH(c.add_date) = ' . $month . ' AND YEAR(c.add_date) = ' . $year);


            foreach ($this_month_expense as $key => $value) {
                $timestamp = strtotime($value->add_date);

                if (array_key_exists(intval(date("d", $timestamp)), $daywise_expense1)) {

                    $daywise_expense1[intval(date("d", $timestamp))] += floatval($value->amount);

                    if ($value->istaxincluded) {
                        $tax = $value->amount * ($value->tax / 100);
                        $daywise_expense1[intval(date("d", $timestamp))] += $tax;
                    }
                } else {

                    $daywise_expense1[intval(date("d", $timestamp))] = floatval($value->amount);

                    if ($value->istaxincluded) {
                        $tax = $value->amount * ($value->tax / 100);
                        $daywise_expense1[intval(date("d", $timestamp))] += $tax;
                    }
                }
            }

            for ($i = 1; $i <= $month_days_array[intval(date('m')) - 1]; $i++) {

                if (array_key_exists($i, $daywise_expense1)) {
                    $daywise_expense2[$i] = $daywise_expense1[$i];
                } else {
                    $daywise_expense2[$i] = 0;
                }
            }

            return array('daywise_income2' => $daywise_income2, 'daywise_expense2' => $daywise_expense2);
        }

        /**
         * Get any year total income or expense quickly
         *
         *
         * @param int $year
         *
         * return array
         */
        public function get_year_data_total_quick($year)
        {

            global $wpdb;

            $total_this_month_income = $total_this_month_expense = $total_this_month_tax = $total_one_month_income = $total_one_month_expense = $one_month_tax = 0;


            for ($i = 1; $i <= 12; $i++) {

                //income
                $one_month_incomes = $wpdb->get_results('SELECT c . * , cat.title as cattitle
FROM  `' . $wpdb->prefix . 'cbaccounting_expinc` c
LEFT JOIN  `' . $wpdb->prefix . 'cbaccounting_expcat_rel` catrel ON c.id = catrel.expinc_id
LEFT JOIN  `' . $wpdb->prefix . 'cbaccounting_category` cat ON catrel.category_id = cat.id
WHERE c.type = 1 AND MONTH(c.add_date) = ' . $i . ' AND YEAR(c.add_date) = ' . $year);


                foreach ($one_month_incomes as $one_month_income) {

                    $total_one_month_income += $one_month_income->amount;

                    if ($one_month_income->istaxincluded) {
                        $one_month_tax = $one_month_income->amount * ($one_month_income->tax / 100);
                        $total_one_month_income += $one_month_tax;
                    }
                }

                $year_income_by_month[$i] = $total_one_month_income;


                //expense
                $one_month_expenses = $wpdb->get_results('SELECT c . * , cat.title as cattitle
FROM  `' . $wpdb->prefix . 'cbaccounting_expinc` c
LEFT JOIN  `' . $wpdb->prefix . 'cbaccounting_expcat_rel` catrel ON c.id = catrel.expinc_id
LEFT JOIN  `' . $wpdb->prefix . 'cbaccounting_category` cat ON catrel.category_id = cat.id
WHERE c.type = 2 AND MONTH(c.add_date) = ' . $i . ' AND YEAR(c.add_date) = ' . $year);

                foreach ($one_month_expenses as $one_month_expense) {

                    $total_one_month_expense += $one_month_expense->amount;

                    if ($one_month_expense->istaxincluded) {
                        $one_month_tax = $one_month_expense->amount * ($one_month_expense->tax / 100);
                        $total_one_month_expense += $one_month_tax;
                    }
                }
                $year_expense_by_month[$i] = $total_one_month_expense;

                $total_one_month_income = 0;
                $total_one_month_expense = 0;
            }

            return array('year_income_by_month' => $year_income_by_month, 'year_expense_by_month' => $year_expense_by_month);
        }

        /**
         * Get Year Data for chart
         * @param type $year
         */
        public function get_year_data($year)
        {

            global $wpdb;

            $total_this_month_income = $total_this_month_expense = $total_this_month_tax = $total_one_month_income = $total_one_month_expense = $one_month_tax = 0;


            for ($i = 1; $i <= 12; $i++) {

                //income
                $one_month_incomes = $wpdb->get_results('SELECT c . * , cat.title as cattitle
FROM  `' . $wpdb->prefix . 'cbaccounting_expinc` c
LEFT JOIN  `' . $wpdb->prefix . 'cbaccounting_expcat_rel` catrel ON c.id = catrel.expinc_id
LEFT JOIN  `' . $wpdb->prefix . 'cbaccounting_category` cat ON catrel.category_id = cat.id
WHERE c.type = 1 AND MONTH(c.add_date) = ' . $i . ' AND YEAR(c.add_date) = ' . $year);


                foreach ($one_month_incomes as $one_month_income) {

                    $total_one_month_income += $one_month_income->amount;

                    if ($one_month_income->istaxincluded) {
                        $one_month_tax = $one_month_income->amount * ($one_month_income->tax / 100);
                        $total_one_month_income += $one_month_tax;
                    }
                }

                $year_income_by_month[$i] = $total_one_month_income;


                //expense
                $one_month_expenses = $wpdb->get_results('SELECT c . * , cat.title as cattitle
FROM  `' . $wpdb->prefix . 'cbaccounting_expinc` c
LEFT JOIN  `' . $wpdb->prefix . 'cbaccounting_expcat_rel` catrel ON c.id = catrel.expinc_id
LEFT JOIN  `' . $wpdb->prefix . 'cbaccounting_category` cat ON catrel.category_id = cat.id
WHERE c.type = 2 AND MONTH(c.add_date) = ' . $i . ' AND YEAR(c.add_date) = ' . $year);

                foreach ($one_month_expenses as $one_month_expense) {

                    $total_one_month_expense += $one_month_expense->amount;

                    if ($one_month_expense->istaxincluded) {
                        $one_month_tax = $one_month_expense->amount * ($one_month_expense->tax / 100);
                        $total_one_month_expense += $one_month_tax;
                    }
                }
                $year_expense_by_month[$i] = $total_one_month_expense;

                $total_one_month_income = 0;
                $total_one_month_expense = 0;
            }

            return array('year_income_by_month' => $year_income_by_month, 'year_expense_by_month' => $year_expense_by_month);
        }

        /**
         * Load year traversal data
         */
        public function load_nextprev_year()
        {

            check_ajax_referer('cbxwpsimpleaccounting_nonce', 'security');

            $year = intval($_POST['year']);

            echo json_encode($this->get_year_data($year));


            wp_die();
        }

        /**
         * Load month traversal data
         */
        public function load_nextprev_month()
        {


            check_ajax_referer('cbxwpsimpleaccounting_nonce', 'security');

            $year = intval($_POST['year']);
            $month = intval($_POST['month']);

            echo json_encode($this->get_month_data($month, $year));

            wp_die();
        }

        /**
         * Show Overview page
         *
         */
        public function admin_menu_display_overview()
        {
            global $wpdb;
            $latest_income_by_cat = $latest_expense_by_cat = $daywise_income1 = $daywise_income2 = $daywise_expense1 = $daywise_expense2 = array();

            if (intval(date('Y')) % 4 == 0) {
                $month_days_array = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
            } else {
                $month_days_array = array(31, 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
            }

            $total_income = $total_cost = $latest_tax = $latest_tax_expense = $total_this_month_income = $total_this_month_expense = $total_this_month_tax = $total_one_month_income = $total_one_month_expense = $one_month_tax = 0;

            $latest_income = $wpdb->get_results('SELECT c . * , cat.id as catid , cat.title as cattitle, cat.color as catcolor, act.acc_name as accountname 
FROM  `' . $wpdb->prefix . 'cbaccounting_expinc` c
LEFT JOIN  `' . $wpdb->prefix . 'cbaccounting_expcat_rel` catrel ON c.id = catrel.expinc_id 
LEFT JOIN  `' . $wpdb->prefix . 'cbaccounting_category` cat ON catrel.category_id = cat.id 
LEFT JOIN  `' . $wpdb->prefix . 'cbaccounting_account_manager` act ON act.id = c.account 
WHERE c.type = 1 AND MONTH(c.add_date) = MONTH(CURDATE()) AND YEAR(c.add_date) = YEAR(CURDATE()) order by add_date desc LIMIT 20');


            $latest_expense = $wpdb->get_results('SELECT c . * , cat.id as catid , cat.title as cattitle, cat.color as catcolor, act.acc_name as accountname 
FROM  `' . $wpdb->prefix . 'cbaccounting_expinc` c
LEFT JOIN  `' . $wpdb->prefix . 'cbaccounting_expcat_rel` catrel ON c.id = catrel.expinc_id 
LEFT JOIN  `' . $wpdb->prefix . 'cbaccounting_category` cat ON catrel.category_id = cat.id 
LEFT JOIN  `' . $wpdb->prefix . 'cbaccounting_account_manager` act ON act.id = c.account
WHERE c.type = 2 AND MONTH(c.add_date) = MONTH(CURDATE()) AND YEAR(c.add_date) = YEAR(CURDATE()) order by add_date desc LIMIT 20');


            $this_month_income = $wpdb->get_results('SELECT c . * , cat.id as catid , cat.title as cattitle, cat.color as catcolor
FROM  `' . $wpdb->prefix . 'cbaccounting_expinc` c
LEFT JOIN  `' . $wpdb->prefix . 'cbaccounting_expcat_rel` catrel ON c.id = catrel.expinc_id
LEFT JOIN  `' . $wpdb->prefix . 'cbaccounting_category` cat ON catrel.category_id = cat.id
WHERE c.type = 1 AND MONTH(c.add_date) = MONTH(CURDATE()) AND YEAR(c.add_date) = YEAR(CURDATE())');


            foreach ($this_month_income as $key => $value) {
                $timestamp = strtotime($value->add_date);

                if (array_key_exists(intval(date("d", $timestamp)), $daywise_income1)) {

                    $daywise_income1[intval(date("d", $timestamp))] += floatval($value->amount);

                    if ($value->istaxincluded) {
                        $tax = $value->amount * ($value->tax / 100);
                        $daywise_income1[intval(date("d", $timestamp))] += $tax;
                    }
                } else {

                    $daywise_income1[intval(date("d", $timestamp))] = floatval($value->amount);

                    if ($value->istaxincluded) {
                        $tax = $value->amount * ($value->tax / 100);
                        $daywise_income1[intval(date("d", $timestamp))] += $tax;
                    }
                }
            }

            for ($i = 1; $i <= $month_days_array[intval(date('m')) - 1]; $i++) {

                if (array_key_exists($i, $daywise_income1)) {
                    $daywise_income2[$i] = $daywise_income1[$i];
                } else {
                    $daywise_income2[$i] = 0;
                }
            }


            $this_month_expense = $wpdb->get_results('SELECT c . * , cat.id as catid, cat.title as cattitle, cat.color as catcolor
FROM  `' . $wpdb->prefix . 'cbaccounting_expinc` c
LEFT JOIN  `' . $wpdb->prefix . 'cbaccounting_expcat_rel` catrel ON c.id = catrel.expinc_id 
LEFT JOIN  `' . $wpdb->prefix . 'cbaccounting_category` cat ON catrel.category_id = cat.id 
WHERE c.type = 2 AND MONTH(c.add_date) = MONTH(CURDATE()) AND YEAR(c.add_date) = YEAR(CURDATE())');


            foreach ($this_month_expense as $key => $value) {
                $timestamp = strtotime($value->add_date);

                if (array_key_exists(intval(date("d", $timestamp)), $daywise_expense1)) {

                    $daywise_expense1[intval(date("d", $timestamp))] += floatval($value->amount);

                    if ($value->istaxincluded) {
                        $tax = $value->amount * ($value->tax / 100);
                        $daywise_expense1[intval(date("d", $timestamp))] += $tax;
                    }
                } else {

                    $daywise_expense1[intval(date("d", $timestamp))] = floatval($value->amount);

                    if ($value->istaxincluded) {
                        $tax = $value->amount * ($value->tax / 100);
                        $daywise_expense1[intval(date("d", $timestamp))] += $tax;
                    }
                }
            }

            for ($i = 1; $i <= $month_days_array[intval(date('m')) - 1]; $i++) {

                if (array_key_exists($i, $daywise_expense1)) {
                    $daywise_expense2[$i] = $daywise_expense1[$i];
                } else {
                    $daywise_expense2[$i] = 0;
                }
            }


            $currency = $this->settings_api->get_option('cbxwpsimpleaccounting_currency', 'cbxwpsimpleaccounting_basics', 'USD');
            $currency_position = $this->settings_api->get_option('cbxwpsimpleaccounting_currency_pos', 'cbxwpsimpleaccounting_basics', 'left');
            $currency_symbol = $this->get_cbxwpsimpleaccounting_currency_symbol($currency);
            $currency_thousand_separator = $this->settings_api->get_option('cbxwpsimpleaccounting_thousand_sep', 'cbxwpsimpleaccounting_basics', ',');
            $currency_decimal_separator = $this->settings_api->get_option('cbxwpsimpleaccounting_decimal_sep', 'cbxwpsimpleaccounting_basics', '.');
            $currency_number_decimal = $this->settings_api->get_option('cbxwpsimpleaccounting_num_decimals', 'cbxwpsimpleaccounting_basics', '2');


            for ($i = 1; $i <= 12; $i++) {

                $one_month_incomes = $wpdb->get_results('SELECT c . * , cat.title as cattitle
FROM  `' . $wpdb->prefix . 'cbaccounting_expinc` c
LEFT JOIN  `' . $wpdb->prefix . 'cbaccounting_expcat_rel` catrel ON c.id = catrel.expinc_id
LEFT JOIN  `' . $wpdb->prefix . 'cbaccounting_category` cat ON catrel.category_id = cat.id 
WHERE c.type = 1 AND MONTH(c.add_date) = ' . $i . ' AND YEAR(c.add_date) = YEAR(CURDATE())');


                foreach ($one_month_incomes as $one_month_income) {

                    $total_one_month_income += $one_month_income->amount;

                    if ($one_month_income->istaxincluded) {
                        $one_month_tax = $one_month_income->amount * ($one_month_income->tax / 100);
                        $total_one_month_income += $one_month_tax;
                    }
                }

                $year_income_by_month[$i] = $total_one_month_income;


                $one_month_expenses = $wpdb->get_results('SELECT c . * , cat.title as cattitle
FROM  `' . $wpdb->prefix . 'cbaccounting_expinc` c
LEFT JOIN  `' . $wpdb->prefix . 'cbaccounting_expcat_rel` catrel ON c.id = catrel.expinc_id
LEFT JOIN  `' . $wpdb->prefix . 'cbaccounting_category` cat ON catrel.category_id = cat.id
WHERE c.type = 2 AND MONTH(c.add_date) = ' . $i . ' AND YEAR(c.add_date) = YEAR(CURDATE())');

                foreach ($one_month_expenses as $one_month_expense) {

                    $total_one_month_expense += $one_month_expense->amount;

                    if ($one_month_expense->istaxincluded) {
                        $one_month_tax = $one_month_expense->amount * ($one_month_expense->tax / 100);
                        $total_one_month_expense += $one_month_tax;
                    }
                }
                $year_expense_by_month[$i] = $total_one_month_expense;

                $total_one_month_income = 0;
                $total_one_month_expense = 0;
            }


            foreach ($this_month_income as $this_month_inc) {

                $total_this_month_income += $this_month_inc->amount;

                if ($this_month_inc->istaxincluded) {
                    $total_this_month_tax = $this_month_inc->amount * ($this_month_inc->tax / 100);
                    $total_this_month_income += $total_this_month_tax;
                } else {
                    $total_this_month_tax = 0;
                }

                if (array_key_exists($this_month_inc->catid, $latest_income_by_cat)) {
                    $latest_income_by_cat[$this_month_inc->catid]['value'] += $this_month_inc->amount + $total_this_month_tax;
                    $latest_income_by_cat[$this_month_inc->catid]['color'] = $this_month_inc->catcolor;
                    $latest_income_by_cat[$this_month_inc->catid]['label'] = $this_month_inc->cattitle;
                    $latest_income_by_cat[$this_month_inc->catid]['labelColor'] = 'white';
                    $latest_income_by_cat[$this_month_inc->catid]['labelFontSize'] = '16';
                } else {
                    $latest_income_by_cat[$this_month_inc->catid]['value'] = $this_month_inc->amount + $total_this_month_tax;
                    $latest_income_by_cat[$this_month_inc->catid]['color'] = $this_month_inc->catcolor;
                    $latest_income_by_cat[$this_month_inc->catid]['label'] = $this_month_inc->cattitle;
                    $latest_income_by_cat[$this_month_inc->catid]['labelColor'] = 'white';
                    $latest_income_by_cat[$this_month_inc->catid]['labelFontSize'] = '16';
                }
            }


            foreach ($this_month_expense as $this_month_exp) {

                $total_this_month_expense = $total_this_month_expense + $this_month_exp->amount;

                if ($this_month_exp->istaxincluded) {
                    $total_this_month_tax_expense = $this_month_exp->amount * ($this_month_exp->tax / 100);
                    $total_this_month_expense += $total_this_month_tax_expense;
                } else {
                    $total_this_month_tax_expense = 0;
                }

                if (array_key_exists($this_month_exp->catid, $latest_expense_by_cat)) {
                    $latest_expense_by_cat[$this_month_exp->catid]['value'] += $this_month_exp->amount + $total_this_month_tax_expense;
                    $latest_expense_by_cat[$this_month_exp->catid]['color'] = $this_month_exp->catcolor;
                    $latest_expense_by_cat[$this_month_exp->catid]['label'] = $this_month_exp->cattitle;
                    $latest_expense_by_cat[$this_month_exp->catid]['labelColor'] = 'white';
                    $latest_expense_by_cat[$this_month_exp->catid]['labelFontSize'] = '16';
                } else {
                    $latest_expense_by_cat[$this_month_exp->catid]['value'] = $this_month_exp->amount + $total_this_month_tax_expense;
                    $latest_expense_by_cat[$this_month_exp->catid]['color'] = $this_month_exp->catcolor;
                    $latest_expense_by_cat[$this_month_exp->catid]['label'] = $this_month_exp->cattitle;
                    $latest_expense_by_cat[$this_month_exp->catid]['labelColor'] = 'white';
                    $latest_expense_by_cat[$this_month_exp->catid]['labelFontSize'] = '16';
                }
            }


            //this year total income

            $get_total_formated_income = CBXWpsimpleaccountingHelper::format_value_quick($total_income);
            $get_total_formated_expanse = CBXWpsimpleaccountingHelper::format_value_quick($total_cost);
            $get_total_formated_this_month_income = CBXWpsimpleaccountingHelper::format_value_quick($total_this_month_income);
            $get_total_formated_this_month_expanse = CBXWpsimpleaccountingHelper::format_value_quick($total_this_month_expense);


            $total_quick = $this->get_data_total_quick(true); //get total income/expense as formatted
            $total_year_quick = $this->get_data_year_total_quick(0, true); //get total income/expense as formatted for current year
            $total_month_quick = $this->get_data_month_total_quick(0, 0, true); //get total income/expense as formatted for current month


            $plugin_data = get_plugin_data(plugin_dir_path(__DIR__) . '/../' . $this->plugin_basename);

	        //enqueue script
	        wp_enqueue_script('cbxchartkickgjsapi');
	        wp_enqueue_script('cbxchartkick');
	        wp_enqueue_script('cbxwpsimpleaccounting-admin-overview');

	        wp_add_inline_script(
		        'cbxwpsimpleaccounting-admin-overview',
		        '
				cbxwpsimpleaccounting.month_days_array = ["'.implode( '","', $month_days_array ).'"];
				cbxwpsimpleaccounting.year_income_by_month = '.json_encode($year_income_by_month ).';
				cbxwpsimpleaccounting.year_expense_by_month = '.json_encode($year_expense_by_month ).';
				cbxwpsimpleaccounting.daywise_income2 = '.json_encode($daywise_income2 ).';
				cbxwpsimpleaccounting.daywise_expense2 = '.json_encode($daywise_expense2 ).';
				cbxwpsimpleaccounting.latest_income_by_cat = '.json_encode($latest_income_by_cat ).';
				cbxwpsimpleaccounting.latest_expense_by_cat = '.json_encode($latest_expense_by_cat ).';
				' ,
		        'before');

            include('partials/admin-overview-display.php');
        }//end method admin_menu_display_overview


        public function format_value($total_number, $currency_number_decimal, $currency_decimal_separator, $currency_thousand_separator, $currency_position, $currency_symbol)
        {
	        return CBXWpsimpleaccountingHelper::format_value($total_number, $currency_number_decimal, $currency_decimal_separator, $currency_thousand_separator, $currency_position, $currency_symbol);
        }//end method format_value

        /**
         * Show a value as formatted
         *
         * @param $total_number
         *
         * @return string
         */
        public function format_value_quick($total_number)
        {
        	return CBXWpsimpleaccountingHelper::format_value_quick($total_number);
        }//end method format_value_quick




        /**
         * Render Category Menu [Category menu dioplay function]
         */
        public function admin_menu_display_cats()
        {
            global $wpdb;
            $cbaccounting_cat_table = $wpdb->prefix . 'cbaccounting_category'; //expinc category table name



            $plugin_data = get_plugin_data(plugin_dir_path(__DIR__) . '/../' . $this->plugin_basename);

            if (isset($_GET['view']) && $_GET['view'] == 'addedit') {

                $cat_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
                $cat_data = array();
                $cat_found = false;
                if ($cat_id > 0) {
                    $cat_found = true;
                    $cat_data = $wpdb->get_row($wpdb->prepare("SELECT * FROM  $cbaccounting_cat_table WHERE id=%d", $cat_id), ARRAY_A);
                    if ($cat_data === null) {
                        $cat_found = false;
                        $cat_data = array();
                    }
                }

                include('partials/admin-managecategory-addedit.php');
            } else if (isset($_GET['view']) && $_GET['view'] == 'view') {
                $cat_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

                $cat_data = array();
                $cat_found = false;
                if ($cat_id > 0) {
                    $cat_found = true;
                    $cat_data = $wpdb->get_row($wpdb->prepare("SELECT * FROM  $cbaccounting_cat_table WHERE id=%d", $cat_id), ARRAY_A);

                    if ($cat_data === null) {
                        $cat_found = false;
                        $cat_data = array();
                    }
                }

                include('partials/admin-managecategory-view.php');
            } else {
                if (!class_exists('CBXWpsimpleaccountingCatListTable')) {
                    require_once(plugin_dir_path(__FILE__) . '../includes/class-cbxwpsimpleaccounting-catlist.php');
                }
                include('partials/admin-managecategory-listing.php');
            }


        }//end method admin_menu_display_cats

        /**
         * Render Account Menu [Category menu dioplay function]
         */
        public function admin_menu_display_accounts()
        {

	        $plugin_data = get_plugin_data(plugin_dir_path(__DIR__) . '/../' . $this->plugin_basename);

            global $wpdb;

            $acc_manager_table = $wpdb->prefix . 'cbaccounting_account_manager';

            if (isset($_GET['view']) && $_GET['view'] == 'addedit') {

                $acc_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
                $acc_data = array();
                $acc_found = false;
                if ($acc_id > 0) {
                    $acc_data = $wpdb->get_row($wpdb->prepare("SELECT * FROM  $acc_manager_table WHERE id=%d", $acc_id), ARRAY_A);

                    if ($acc_data !== null) {
                        $acc_found = true;
                    }
                }

                if ($acc_id == 0 || $acc_data === null) {
                    $acc_data['id'] = 0;
                    $acc_data['title'] = '';
                    $acc_data['type'] = 'cash';
                    $acc_data['acc_no'] = '';
                    $acc_data['acc_name'] = '';
                    $acc_data['bank_name'] = '';
                    $acc_data['branch_name'] = '';
                }

                //if (!session_id()) session_start();
                include('partials/admin-account-manager-addedit.php');
            } else {
                if (!class_exists('CBXWpsimpleaccountingAccListTable')) {
                    require_once(plugin_dir_path(__FILE__) . '../includes/class-cbxwpsimpleaccounting-acclist.php');
                }

	            $plugin_data = get_plugin_data(plugin_dir_path(__DIR__) . '/../' . $this->plugin_basename);
                include('partials/admin-manageaccount-listing.php');
            }


        }//end method admin_menu_display_accounts

	    /**
	     * Add/Edit Income/Expense render displayt
	     */
        public function admin_menu_display_adddexpinc()
        {
	        $plugin_data = get_plugin_data(plugin_dir_path(__DIR__) . '/../' . $this->plugin_basename);

            global $wpdb;

            $view = (isset($_GET['view']) && intval($_GET['view']) == 1) ? true : false;
            $single_incomeexpense = array();

            $cat_results_list = $wpdb->get_results('SELECT `id`, `title`, `type`, `color` FROM `' . $wpdb->prefix . 'cbaccounting_category`', ARRAY_A);

            $all_acc_list = $wpdb->get_results('SELECT * FROM `' . $wpdb->prefix . 'cbaccounting_account_manager`');

            if ($cat_results_list == null) {
                $cat_results_list = array();
            }


            if (isset($_GET['id']) && absint($_GET['id']) > 0) {

                $id = absint($_GET['id']);
                $incexp = $wpdb->get_row($wpdb->prepare('SELECT *  FROM `' . $wpdb->prefix . 'cbaccounting_expinc` WHERE id = %d', $id), ARRAY_A);
                $incexpcatlist = $wpdb->get_results($wpdb->prepare('SELECT *  FROM `' . $wpdb->prefix . 'cbaccounting_expcat_rel` WHERE expinc_id = %d', $id), ARRAY_A);

                if ($incexpcatlist != null) {
                    foreach ($incexpcatlist as $list) {
                        $catlist[] = $list['category_id'];
                    }
                }

                if ($incexp != null) {

                    if (isset($incexp['protected']) && intval($incexp['protected']) == 1 && $view == false) {
                        $single_incomeexpense['error'] = true;
                        $single_incomeexpense['msg'] = esc_html__('This log entry is protected, created by 3rd party plugin and can not be edited.', 'cbxwpsimpleaccounting');
                    } else {
                        $single_incomeexpense['error'] = false;
                        $single_incomeexpense['msg'] = esc_html__('Data Loaded for edit', 'cbxwpsimpleaccounting');

                        $single_incomeexpense['id'] = $id;
                        $single_incomeexpense['status'] = 'loaded';
                        $single_incomeexpense['title'] = stripslashes(esc_attr($incexp['title']));
                        $single_incomeexpense['amount'] = floatval($incexp['amount']);
                        $single_incomeexpense['source_amount'] = floatval($incexp['source_amount']);
                        $single_incomeexpense['source_currency'] = $incexp['source_currency'];
                        $single_incomeexpense['account'] = $incexp['account'];
                        $single_incomeexpense['invoice'] = stripslashes(esc_attr($incexp['invoice']));
                        $single_incomeexpense['istaxincluded'] = $incexp['istaxincluded'];
                        $single_incomeexpense['tax'] = $incexp['tax'];
                        $single_incomeexpense['cat_list'] = $catlist;
                        $single_incomeexpense['type'] = $incexp['type'];
                        $single_incomeexpense['note'] = stripslashes(esc_textarea($incexp['note']));
                        $single_incomeexpense['add_date'] = $incexp['add_date'];

                        $single_incomeexpense = apply_filters('cbxwpsimpleaccounting_incexp_single_data', $single_incomeexpense, $incexp, $id);
                    }


                } else {
                    $single_incomeexpense['error'] = true;
                    $single_incomeexpense['msg'] = esc_html__('You attempted to edit item that doesn\'t exist. ', 'cbxwpsimpleaccounting');
                }
            }

            $plugin_data = get_plugin_data(plugin_dir_path(__DIR__) . '/../' . $this->plugin_basename);

            if ($view) {
                include('partials/admin-viewexpinc-display.php');
            } else {

                include('partials/admin-adddexpinc-display.php');
            }
        }//end method admin_menu_display_adddexpinc

	    /**
	     * Render Setting page
	     */
        public function admin_menu_display_settings()
        {
            global $wpdb;

            $plugin_data = get_plugin_data(plugin_dir_path(__DIR__) . '/../' . $this->plugin_basename);

            include('partials/admin-settings-display.php');
        }//end method admin_menu_display_settings

	    /**
	     * Render addon page
	     */
        public function admin_menu_display_addons()
        {
            global $wpdb;

            //$cbxaccount_admin_url = plugin_dir_url(__FILE__);
            $cbxaccount_admin_url = CBXWPSIMPLEACCOUNTING_ROOT_URL;

            $plugin_data = get_plugin_data(plugin_dir_path(__DIR__) . '/../' . $this->plugin_basename);

            include('partials/admin-addons-display.php');
        }//end method admin_menu_display_addons

        /**
         * Check the status of a plugin. (https://katz.co/simple-plugin-status-wordpress/)
         *
         * @param string $location Base plugin path from plugins directory.
         * @return int 1 if active; 2 if inactive; 0 if not installed
         */
        function get_plugin_status($location = '')
        {

            if (is_plugin_active($location)) {
                return array(
                    'status'   => 1,
                    'msg'      => esc_html__('Active and Installed', 'cbxwpsimpleaccounting'),
                    'btnclass' => 'button button-primary'
                );
            }

            if (!file_exists(trailingslashit(WP_PLUGIN_DIR) . $location)) {
                return array(
                    'status'   => 0,
                    'msg'      => esc_html__('Not Installed or Active', 'cbxwpsimpleaccounting'),
                    'btnclass' => 'button'
                );
            }

            if (is_plugin_inactive($location)) {
                return array(
                    'status'   => 2,
                    'msg'      => esc_html__('Installed but Inactive', 'cbxwpsimpleaccounting'),
                    'btnclass' => 'button'
                );
            }
        }//end method get_plugin_status

        public function get_settings_sections(){
            $sections = array(
                array(
                    'id'    => 'cbxwpsimpleaccounting_basics',
                    'title' => esc_html__('Basic Settings', 'cbxwpsimpleaccounting')
                ),
                array(
                    'id'    => 'cbxwpsimpleaccounting_category',
                    'title' => esc_html__('Category Settings', 'cbxwpsimpleaccounting')
                ),
                array(
                    'id'    => 'cbxwpsimpleaccounting_tax',
                    'title' => esc_html__('Tax Settings', 'cbxwpsimpleaccounting')
                ),
                array(
                    'id'    => 'cbxwpsimpleaccounting_graph',
                    'title' => esc_html__('Graph Settings', 'cbxwpsimpleaccounting')
                ),
                array(
                    'id'    => 'cbxwpsimpleaccounting_tools',
                    'title' => esc_html__('Tools', 'cbxwpsimpleaccounting')
                )
            );

            $sections = apply_filters('cbxaccountingsettingsections', $sections);

            return $sections;
        }//end method get_settings_sections

        /**
         * Returns all the settings fields
         *
         * @return array settings fields
         */
        public function get_settings_fields()
        {
	        $settings_api = $this->settings_api;

            $currency_code_options = $this->get_cbxwpsimpleaccounting_currencies();

            foreach ($currency_code_options as $code => $name) {
                $currency_code_options[$code] = $name . ' (' . $this->get_cbxwpsimpleaccounting_currency_symbol($code) . ')';
            }

            $reset_data_link = add_query_arg('cbxwpsimpleaccounting_fullreset', 1, admin_url('admin.php?page=cbxwpsimpleaccounting_settings'));

	        $accounting_table_names = CBXWpsimpleaccountingHelper::getAllDBTablesList();
	        $accounting_table_html = '<p id="cbxwpsimpleaccounting_plg_gfig_info"><strong>'.esc_html__('Following database tables will be reset/deleted.', 'cbxwpsimpleaccounting').'</strong></p>';

	        $table_counter = 1;
	        foreach($accounting_table_names as $key => $value){
		        $accounting_table_html .= '<p>'.str_pad($table_counter, 2, '0', STR_PAD_LEFT).'. '.$key.' - (<code>'.$value.'</code>)</p>';
		        $table_counter++;
	        }

	        $accounting_table_html .= '<p><strong>'.esc_html__('Following option values created by this plugin(including addon) from wordpress core option table','cbxwpsimpleaccounting').'</strong></p>';


	        $accounting_option_values = CBXWpsimpleaccountingHelper::getAllOptionNames();
	        $table_counter = 1;
	        foreach($accounting_option_values as $key => $value){
		        $accounting_table_html .= '<p>'.str_pad($table_counter, 2, '0', STR_PAD_LEFT).'. '.$value['option_name'].' - '.$value['option_id'].' - (<code>'.$value['option_value'].'</code>)</p>';
		        $table_counter++;
	        }


	        $cats             = CBXWpsimpleaccountingHelper::getAllCategories();
	        $income_cats      = CBXWpsimpleaccountingHelper::getAllIncomeCategories( $cats );
	        $expense_cats     = CBXWpsimpleaccountingHelper::getAllExpenseCategories( $cats );
	        $income_cats_arr  = CBXWpsimpleaccountingHelper::getCatChooserArr( $income_cats, false );
	        $expense_cats_arr = CBXWpsimpleaccountingHelper::getCatChooserArr( $expense_cats , false);

	        $income_default_category    = intval($settings_api->get_option('income_default_category', 'cbxwpsimpleaccounting_category', 0));
	        $expense_default_category   = intval($settings_api->get_option('expense_default_category', 'cbxwpsimpleaccounting_category', 0));



	        if($income_default_category > 0 && !isset($income_cats_arr[$income_default_category])){
	        	$income_default_category = 0;
	        }

	        if($expense_default_category > 0 && !isset($expense_cats_arr[$expense_default_category])){
		        $expense_default_category = 0;
	        }


	        $income_default_category_html = '';
	        if($income_default_category == 0 && sizeof($income_cats_arr) == 1){
		        $income_default_category_html .= ' <a class="button default_category_create" data-type="1" data-busy="0" title="'.esc_html__('Click to create default income category', 'cbxwpsimpleaccounting').'"  href="#">'.esc_html__('Create', 'cbxwpsimpleaccounting').'</a>';
	        }
	        else if($income_default_category > 0){
		        $income_default_category_html .= ' <a target="_blank" class="button" href="'.admin_url('admin.php?page=cbxwpsimpleaccounting_cat&id='.$income_default_category.'&view=addedit').'.">'.esc_html__('Edit', 'cbxwpsimpleaccounting').'</a>';
	        }

	        $expense_default_category_html = '';
	        if($expense_default_category == 0 && sizeof($expense_cats_arr) == 1){
		        $expense_default_category_html .= ' <a class="button default_category_create" data-type="2" data-busy="0" title="'.esc_html__('Click to create default expense category', 'cbxwpsimpleaccounting').'" href="#">'.esc_html__('Create', 'cbxwpsimpleaccounting').'</a>';
	        }
	        else if($expense_default_category > 0){
		        $expense_default_category_html .= ' <a target="_blank" class="button" href="'.admin_url('admin.php?page=cbxwpsimpleaccounting_cat&id='.$expense_default_category.'&view=addedit').'.">'.esc_html__('Edit', 'cbxwpsimpleaccounting').'</a>';
	        }


	        $settings_fields = array(
                'cbxwpsimpleaccounting_basics'   => array(
                    array(
                        'name'     => 'cbxwpsimpleaccounting_currency',
                        'label'    => esc_html__('Default Currency', 'cbxwpsimpleaccounting'),
                        'desc'     => esc_html__('This controls what currency is used for calculation.', 'cbxwpsimpleaccounting'),
                        'type'     => 'select',
                        'default'  => 'no',
                        'desc_tip' => true,
                        'options'  => $currency_code_options,
                        'default'  => 'USD',
                    ),
                    array(
                        'name'     => 'cbxwpsimpleaccounting_currency_pos',
                        'label'    => esc_html__('Currency Position', 'cbxwpsimpleaccounting'),
                        'desc'     => esc_html__('This controls the position of the currency symbol.', 'cbxwpsimpleaccounting'),
                        'type'     => 'select',
                        'default'  => 'left',
                        'options'  => array(
                            'left'        => esc_html__('Left', 'cbxwpsimpleaccounting') . ' (' . $this->get_cbxwpsimpleaccounting_currency_symbol($this->settings_api->get_option('cbxwpsimpleaccounting_currency', 'cbxwpsimpleaccounting_basics', 'USD')) . '99.99)',
                            'right'       => esc_html__('Right', 'cbxwpsimpleaccounting') . ' (99.99' . $this->get_cbxwpsimpleaccounting_currency_symbol($this->settings_api->get_option('cbxwpsimpleaccounting_currency', 'cbxwpsimpleaccounting_basics', 'USD')) . ')',
                            'left_space'  => esc_html__('Left with space', 'cbxwpsimpleaccounting') . ' (' . $this->get_cbxwpsimpleaccounting_currency_symbol($this->settings_api->get_option('cbxwpsimpleaccounting_currency', 'cbxwpsimpleaccounting_basics', 'USD')) . ' 99.99)',
                            'right_space' => esc_html__('Right with space', 'cbxwpsimpleaccounting') . ' (99.99 ' . $this->get_cbxwpsimpleaccounting_currency_symbol($this->settings_api->get_option('cbxwpsimpleaccounting_currency', 'cbxwpsimpleaccounting_basics', 'USD')) . ')'
                        ),
                        'desc_tip' => true,
                    ),
                    array(
                        'name'     => 'cbxwpsimpleaccounting_thousand_sep',
                        'label'    => esc_html__('Thousand Separator', 'cbxwpsimpleaccounting'),
                        'desc'     => esc_html__('This sets the thousand separator of displayed prices.', 'cbxwpsimpleaccounting'),
                        'type'     => 'text',
                        'default'  => ',',
                        'desc_tip' => true,
                    ),
                    array(
                        'name'     => 'cbxwpsimpleaccounting_decimal_sep',
                        'label'    => esc_html__('Decimal Separator', 'cbxwpsimpleaccounting'),
                        'desc'     => esc_html__('This sets the decimal separator of displayed prices.', 'cbxwpsimpleaccounting'),
                        'type'     => 'text',
                        'default'  => '.',
                        'desc_tip' => true,
                    ),
                    array(
                        'name'     => 'cbxwpsimpleaccounting_num_decimals',
                        'label'    => esc_html__('Number of Decimals', 'cbxwpsimpleaccounting'),
                        'desc'     => esc_html__('This sets the number of decimal points shown in displayed prices.', 'cbxwpsimpleaccounting'),
                        'type'     => 'number',
                        'default'  => '2',
                        'desc_tip' => true,
                    )
                ),
                'cbxwpsimpleaccounting_category' => array(
                    array(
                        'name'    => 'cbxacc_category_color',
                        'label'   => esc_html__('Category Color', 'cbxwpsimpleaccounting'),
                        'desc'    => esc_html__('If yes each category must have a unique color', 'cbxwpsimpleaccounting'),
                        'type'    => 'checkbox',
                        'default' => 'on'
                    ),
	                array(
		                'name'    => 'income_default_category',
		                'label'   => esc_html__('Income Default Category', 'cbxwpsimpleaccounting'),
		                'desc'    => esc_html__('Choose default category for income log entry', 'cbxwpsimpleaccounting').$income_default_category_html,
		                'type'    => 'select',
		                'default' => '0',
		                'options' => $income_cats_arr
	                ),
	                array(
		                'name'    => 'expense_default_category',
		                'label'   => esc_html__('Expense Default Category', 'cbxwpsimpleaccounting'),
		                'desc'    => esc_html__('Choose default category for expense log entry', 'cbxwpsimpleaccounting').$expense_default_category_html,
		                'type'    => 'select',
		                'default' => '0',
		                'options' => $expense_cats_arr
	                )

                ),
                'cbxwpsimpleaccounting_tax'      => array(
                    array(
                        'name'     => 'cbxwpsimpleaccounting_sales_tax',
                        'label'    => esc_html__('Sales Tax (VAT)', 'cbxwpsimpleaccounting'),
                        'desc'     => esc_html__('Default Tax(Vat) %', 'cbxwpsimpleaccounting'),
                        'type'     => 'number',
                        'default'  => '0',
                        'desc_tip' => true,
                        'step'     => '.01'
                    )
                ),
                'cbxwpsimpleaccounting_graph'    => array(
                    array(
                        'name'    => 'legend_color_for_income',
                        'label'   => esc_html__('Legend Color for Income', 'cbxwpsimpleaccounting'),
                        'desc'    => esc_html__('Legend Color for Income', 'cbxwpsimpleaccounting'),
                        'type'    => 'color',
                        'default' => '#5cc488' //greenish
                    ),
                    array(
                        'name'    => 'legend_color_for_expense',
                        'label'   => esc_html__('Legend Color for Expense', 'cbxwpsimpleaccounting'),
                        'desc'    => esc_html__('Legend Color for Expense', 'cbxwpsimpleaccounting'),
                        'type'    => 'color',
                        'default' => '#e74c3c' //redish
                    )
                ),
                'cbxwpsimpleaccounting_tools'    =>
                    array(
                        'delete_global_config' => array(
                            'name'     => 'delete_global_config',
                            'label'    => esc_html__('On Uninstall delete plugin data', 'cbxwpsimpleaccounting'),
                            'desc'     => '<p>'.__('Delete Global Config data and custom table created by this plugin on uninstall.', 'cbxwpsimpleaccounting').' '. __('Details table information is <a href="#cbxwpsimpleaccounting_plg_gfig_info">here</a>', 'cbxwpsimpleaccounting').'</p>'.'<p>'.__('<strong>Please note that this process can not be undone and it is recommended to keep full database backup before doing this.</strong>', 'cbxwpsimpleaccounting').'</p>',
                            'type'     => 'radio',
                            'options'  => array(
                                'yes' => esc_html__('Yes', 'cbxwpsimpleaccounting'),
                                'no'  => esc_html__('No', 'cbxwpsimpleaccounting'),
                            ),
                            'default'  => 'no',
                            'desc_tip' => true,
                        ),
                        'reset_data'           => array(
                            'name'     => 'reset_data',
                            'label'    => esc_html__('Reset all data', 'cbxwpsimpleaccounting'),
                            'desc'     => sprintf(__('Reset option values and all tables created by this plugin. 
<a class="button button-primary" onclick="return confirm(\'%s\')" href="%s">Reset Data</a>', 'cbxwpsimpleaccounting'), esc_html__('Are you sure to reset all data, this process can not be undone?', 'cbxwpsimpleaccounting') ,$reset_data_link).$accounting_table_html,
                            'type'     => 'html',
                            'default'  => 'off',
                            'desc_tip' => true,
                        ),
                    ),
            );

            $settings_fields = apply_filters('cbxaccountingsettingfields', $settings_fields);

            return $settings_fields;
        }//end method get_settings_fields

	    /**
	     * Full reset plugin data
	     */
        public function cbxwpsimpleaccounting_plugin_fullreset(){
        	global $wpdb;

	        $option_prefix = 'cbxwpsimpleaccounting_';

	        $accounting_option_values = CBXWpsimpleaccountingHelper::getAllOptionNames();

	        foreach ($accounting_option_values as $key => $accounting_option_value ){
		        delete_option($accounting_option_value['option_name']);
	        }

	        do_action('cbxwpsimpleaccounting_plugin_option_delete');


	        //delete tables

	        $table_names = CBXWpsimpleaccountingHelper::getAllDBTablesList();
	        $sql = "DROP TABLE IF EXISTS " . implode(', ', array_values($table_names));
	        $query_result = $wpdb->query($sql);

	        do_action('cbxwpsimpleaccounting_plugin_table_delete');

	        // create plugin's core table tables
	        CBXWpsimpleaccountingHelper::dbTableCreation();

	        //please note that, the default otpions will be created by default

	        //3rd party plugin's table creation
	        do_action('cbxwpsimpleaccounting_plugin_reset', $table_names, $option_prefix);


	        //$settings_api = new CBXWPSimpleaccounting_Settings_API(CBXWPSIMPLEACCOUNTING_PLUGIN_NAME, CBXWPSIMPLEACCOUNTING_PLUGIN_VERSION);


	        $this->settings_api->set_sections($this->get_settings_sections());
	        $this->settings_api->set_fields($this->get_settings_fields());
	        $this->settings_api->admin_init();

	        //create default category
	        CBXWpsimpleaccountingHelper::defaultCategoryCreation();

	        wp_safe_redirect(admin_url('admin.php?page=cbxwpsimpleaccounting_settings#cbxwpsimpleaccounting_tools'));
	        exit();

        }
        /**
         * Get Base Currency Code.
         *
         * @return string
         */
        public function get_cbxwpsimpleaccounting_currency()
        {
            return apply_filters('cbxwpsimpleaccounting_currency', get_option('cbxwpsimpleaccounting_currency'));
        }

        /**
         * Get full list of currency codes.
         *
         * @return array
         */
        public function get_cbxwpsimpleaccounting_currencies(){
        	return CBXWpsimpleaccountingHelper::get_cbxwpsimpleaccounting_currencies();
        }

        /**
         * Get Currency symbol.
         *
         * @param string $currency (default: '')
         *
         * @return string
         */
        public function get_cbxwpsimpleaccounting_currency_symbol($currency_code = ''){
	        return CBXWpsimpleaccountingHelper::get_cbxwpsimpleaccounting_currency_symbol($currency_code);
        }

        /**
         * All time Total income or expense
         *
         * @param bool|false $format
         *
         * @return array
         */
        public function get_data_total_quick($format = false)
        {

            global $wpdb;

            $total_income = $total_expense = 0;

            //income
            $total_income_without_tax = $wpdb->get_var($wpdb->prepare('SELECT SUM(c.amount)
FROM  `' . $wpdb->prefix . 'cbaccounting_expinc` c
WHERE c.type = %d ', 1));


            $total_income += $total_income_without_tax;

            $total_income_where_tax = $wpdb->get_var($wpdb->prepare('SELECT SUM(c.amount*c.tax/100)
FROM  `' . $wpdb->prefix . 'cbaccounting_expinc` c
WHERE c.type = %d AND c.istaxincluded = %d ', 1, 1));

            $total_income += $total_income_where_tax;


            //expense
            $total_expense_without_tax = $wpdb->get_var($wpdb->prepare('SELECT SUM(c.amount)
FROM  `' . $wpdb->prefix . 'cbaccounting_expinc` c
WHERE c.type = %d ', 2));


            $total_expense += $total_expense_without_tax;

            $total_expense_where_tax = $wpdb->get_var($wpdb->prepare('SELECT SUM(c.amount*c.tax/100)
FROM  `' . $wpdb->prefix . 'cbaccounting_expinc` c
WHERE c.type = %d AND c.istaxincluded = %d ', 2, 1));

            $total_expense += $total_expense_where_tax;


            $profit = $total_income - $total_expense;

            if ($format) {
	            $total_income            = CBXWpsimpleaccountingHelper::format_value_quick( $total_income );
	            $total_income_where_tax  = CBXWpsimpleaccountingHelper::format_value_quick( $total_income_where_tax );
	            $total_expense           = CBXWpsimpleaccountingHelper::format_value_quick( $total_expense );
	            $total_expense_where_tax = CBXWpsimpleaccountingHelper::format_value_quick( $total_expense_where_tax );
	            $profit                  = CBXWpsimpleaccountingHelper::format_value_quick( $profit );
            }

            return (object)array('total_income' => $total_income, 'income_tax' => $total_income_where_tax, 'total_expense' => $total_expense, 'expense_tax' => $total_expense_where_tax, 'profit' => $profit);
        }

        /**
         * All time Total income or expense for a year
         *
         * @param bool|false $use_current
         * @param int $year
         * @param bool|false $format
         *
         * @return array
         */
        public function get_data_year_total_quick($year = 0, $format = false)
        {

            global $wpdb;

            if ($year == 0) $year = intval(date('Y'));


            $year = intval($year);

            $total_income = $total_expense = 0;

            //income
            $total_income_without_tax = $wpdb->get_var($wpdb->prepare('SELECT SUM(c.amount)
FROM  `' . $wpdb->prefix . 'cbaccounting_expinc` c
WHERE c.type = %d AND YEAR(c.add_date) = %d', 1, $year));


            $total_income += $total_income_without_tax;

            $total_income_where_tax = $wpdb->get_var($wpdb->prepare('SELECT SUM(c.amount*c.tax/100)
FROM  `' . $wpdb->prefix . 'cbaccounting_expinc` c
WHERE c.type = %d AND c.istaxincluded = %d AND YEAR(c.add_date) = %d', 1, 1, $year));

            $total_income += $total_income_where_tax;


            //expense
            $total_expense_without_tax = $wpdb->get_var($wpdb->prepare('SELECT SUM(c.amount)
FROM  `' . $wpdb->prefix . 'cbaccounting_expinc` c
WHERE c.type = %d AND YEAR(c.add_date) = %d', 2, $year));


            $total_expense += $total_expense_without_tax;

            $total_expense_where_tax = $wpdb->get_var($wpdb->prepare('SELECT SUM(c.amount*c.tax/100)
FROM  `' . $wpdb->prefix . 'cbaccounting_expinc` c
WHERE c.type = %d AND c.istaxincluded = %d AND YEAR(c.add_date) = %d ', 2, 1, $year));

            $total_expense += $total_expense_where_tax;

            $profit = $total_income - $total_expense;

            if ($format) {
                $total_income = CBXWpsimpleaccountingHelper::format_value_quick($total_income);
                $total_income_where_tax = CBXWpsimpleaccountingHelper::format_value_quick($total_income_where_tax);
                $total_expense = CBXWpsimpleaccountingHelper::format_value_quick($total_expense);
                $total_expense_where_tax = CBXWpsimpleaccountingHelper::format_value_quick($total_expense_where_tax);
                $profit = CBXWpsimpleaccountingHelper::format_value_quick($profit);
            }


            return (object)array('total_income' => $total_income, 'income_tax' => $total_income_where_tax, 'total_expense' => $total_expense, 'expense_tax' => $total_expense_where_tax, 'profit' => $profit);
        }

        /**
         * All time Total income or expense for a month
         *
         * @param bool|false $use_current
         * @param int $year
         * @param bool|false $format
         *
         * @return array
         */
        public function get_data_month_total_quick($year = 0, $month = 0, $format = false)
        {

            global $wpdb;

            if ($year == 0) $year = intval(date('Y'));
            if ($month == 0) $month = intval(date('m'));


            $year = intval($year);
            $month = intval($month);

            $total_income = $total_expense = 0;

            //income
            $total_income_without_tax = $wpdb->get_var($wpdb->prepare('SELECT SUM(c.amount)
FROM  `' . $wpdb->prefix . 'cbaccounting_expinc` c
WHERE c.type = %d AND MONTH(c.add_date) =%d  AND YEAR(c.add_date) = %d', 1, $month, $year));


            $total_income += $total_income_without_tax;

            $total_income_where_tax = $wpdb->get_var($wpdb->prepare('SELECT SUM(c.amount*c.tax/100)
FROM  `' . $wpdb->prefix . 'cbaccounting_expinc` c
WHERE c.type = %d AND c.istaxincluded = %d AND MONTH(c.add_date) =%d AND YEAR(c.add_date) = %d', 1, 1, $month, $year));

            $total_income += $total_income_where_tax;


            //expense
            $total_expense_without_tax = $wpdb->get_var($wpdb->prepare('SELECT SUM(c.amount)
FROM  `' . $wpdb->prefix . 'cbaccounting_expinc` c
WHERE c.type = %d AND MONTH(c.add_date) =%d AND YEAR(c.add_date) = %d', 2, $month, $year));


            $total_expense += $total_expense_without_tax;

            $total_expense_where_tax = $wpdb->get_var($wpdb->prepare('SELECT SUM(c.amount*c.tax/100)
FROM  `' . $wpdb->prefix . 'cbaccounting_expinc` c
WHERE c.type = %d AND c.istaxincluded = %d AND MONTH(c.add_date) =%d AND YEAR(c.add_date) = %d ', 2, 1, $month, $year));

            $total_expense += $total_expense_where_tax;

            $profit = $total_income - $total_expense;

            if ($format) {
                $total_income = CBXWpsimpleaccountingHelper::format_value_quick($total_income);
                $total_income_where_tax = CBXWpsimpleaccountingHelper::format_value_quick($total_income_where_tax);
                $total_expense = CBXWpsimpleaccountingHelper::format_value_quick($total_expense);
                $total_expense_where_tax = CBXWpsimpleaccountingHelper::format_value_quick($total_expense_where_tax);
                $profit = CBXWpsimpleaccountingHelper::format_value_quick($profit);
            }


            return (object)array('total_income' => $total_income, 'income_tax' => $total_income_where_tax, 'total_expense' => $total_expense, 'expense_tax' => $total_expense_where_tax, 'profit' => $profit);
        }

	    /**
	     * Create default category using ajax
	     */
        public function ajax_default_category_create(){
	        check_ajax_referer( 'cbxwpsimpleaccounting_nonce', 'security' );

	        $submit_data = $_POST;

	        $output = array();
	        $output['success'] = 0;

	        $type = isset($submit_data['type'])? intval($submit_data['type']): 0;
	        if($type > 0){
	        	if($type == 1){
	        		//create default income category
			        $return = CBXWpsimpleaccountingHelper::create_default_category(true, false);
			        if(isset($return['income_created']) && $return['income_created'] == true){
			        	$output['success'] = 1;
			        }
		        }
		        elseif ($type == 2){
	        		//create default expense category
			        $return = CBXWpsimpleaccountingHelper::create_default_category(false, true);
			        if(isset($return['expense_created']) && $return['expense_created'] == true){
				        $output['success'] = 1;
			        }
		        }
	        }

	        echo wp_json_encode($output);
	        wp_die();
        }

	    /**
	     * Callback for filter 'cbxwpsimpleaccounting_catlog_link'
	     *
	     */
	    public function cbxwpsimpleaccountinglog_cat_link($title, $expinc_type, $cat_id)
	    {

		    //check if user can see the log manager
		    if (!current_user_can('log_cbxaccounting')) {
			    return $title;
		    }

		    $admin_url = admin_url('admin.php?page=cbxwpsimpleaccountinglog&cbxfilter_action=Filter&cbxlogexpinc_type=' . $expinc_type . '&cbxlogexpinc_category=' . $cat_id);

		    $admin_nonce_url = wp_nonce_url($admin_url, 'bulk-cbxaccountinglogs');
		    return "<a href='$admin_nonce_url'>" . $title . "</a>";

	    }

	    /**
	     * Callback for filter cbxwpsimpleaccounting_accountlog_link
	     *
	     * @param $title
	     * @param $cat_id
	     *
	     * @return string
	     */
	    public function cbxwpsimpleaccountinglog_account_link($title, $expinc_type, $account)
	    {
		    //check if user can see the log manager
		    if (!current_user_can('log_cbxaccounting')) {
			    return $title;
		    }

		    $admin_url = admin_url('admin.php?page=cbxwpsimpleaccountinglog&cbxfilter_action=Filter&cbxlogexpinc_type=' . $expinc_type . '&cbxlogexpinc_account=' . $account);

		    $admin_nonce_url = wp_nonce_url($admin_url, 'bulk-cbxaccountinglogs');
		    return "<a href='$admin_nonce_url'>" . $title . "</a>";
	    }//end emthod cbxwpsimpleaccountinglog_account_link

	    /**
	     * Export accounting log as csv, xls, xlsx and pdf
	     *
	     */
	    public function cbxwpsimpleaccounting_log_export()
	    {




		    //proceed after checking nonce...
		    if (isset( $_POST['_wpnonce'] ) && ! empty( $_POST['_wpnonce'] )  && isset($_REQUEST['cbxwpsimpleaccounting_log_export']) && isset($_REQUEST['format']) && $_REQUEST['format'] !== null) {
			    global $wpdb;

			    $nonce  = filter_input( INPUT_POST, '_wpnonce', FILTER_SANITIZE_STRING );
			    $action = 'bulk-cbxaccountinglogs';

			    if ( ! wp_verify_nonce( $nonce, $action ) ){
				    wp_die( 'Nope! Security check failed!' );
			    }





			    $export_format = sanitize_text_field($_REQUEST['format']);

			    //Include Tcpdf if not exist
			    if (!class_exists('TCPDF') && ($export_format == 'pdf')) {
				    echo __('Please install the <a href="https://wordpress.org/plugins/tcpdf/" target="_blank">TCPDF Library</a> plugin to export as pdf.', 'cbxwpsimpleaccounting').sprintf(__(' Back to <a href="%s">Log Manager</a>.', 'cbxwpsimpleaccounting'), admin_url('admin.php?page=cbxwpsimpleaccountinglog'));
				    exit();
			    }

			    $phpexcel_loaded = false;
			    if (class_exists('PHPExcel')) {
				    $phpexcel_loaded = true;
			    } else {
				    if (file_exists(plugin_dir_path(__FILE__) . '../includes/PHPExcel.php')) {
					    //Include PHPExcel
					    require_once(plugin_dir_path(__FILE__) . '../includes/PHPExcel.php');

					    $phpexcel_loaded = true;
				    }
			    }

			    if($phpexcel_loaded == false){
				    echo esc_html__('Sorry PHPExcel library not loaded properly.', 'cbxwpsimpleaccounting').sprintf(__(' Back to <a href="%s">Log Manager</a>.', 'cbxwpsimpleaccounting'), admin_url('admin.php?page=cbxwpsimpleaccountinglog'));
				    exit();
			    }

			    if ($phpexcel_loaded) {

				    error_reporting(0);

				    //all accounting table name.
				    $cbxexpinc_table = $wpdb->prefix . 'cbaccounting_expinc';
				    $cbaccounting_expcat_rel_table = $wpdb->prefix . 'cbaccounting_expcat_rel';
				    $cbaccounting_cat_table = $wpdb->prefix . 'cbaccounting_category';
				    $cbxacc_table_name = $wpdb->prefix . 'cbaccounting_account_manager';


				    $category = isset($_REQUEST['cbxlogexpinc_category']) ? intval($_REQUEST['cbxlogexpinc_category']) : 0;
				    $account = isset($_REQUEST['cbxlogexpinc_account'])  ? intval($_REQUEST['cbxlogexpinc_account']) : 0;



				    //for pdf table start
				    $type = esc_html__('All', 'cbxwpsimpleaccounting');
				    if (isset($_REQUEST['cbxlogexpinc_type'])) {
					    if ($_REQUEST['cbxlogexpinc_type'] == '1') {
						    $type = esc_html__('Income', 'cbxwpsimpleaccounting');
					    } else if ($_REQUEST['cbxlogexpinc_type'] == '2') {
						    $type = esc_html__('Export', 'cbxwpsimpleaccounting');
					    }
				    }



				    if ($category > 0) {
					    $category_info = $wpdb->get_row($wpdb->prepare('Select title from ' . $cbaccounting_cat_table . ' where id = %d', $category), OBJECT);

					    $category_title = $category_info->title;
				    } else {

					    $category_title = esc_html__('All', 'cbxwpsimpleaccounting');
				    }

				    if ($account > 0) {
					    $account_info = $wpdb->get_row($wpdb->prepare('Select title from ' . $cbxacc_table_name . ' where id = %d', $account), OBJECT);

					    $account_title = $account_info->title;
				    } else {

					    $account_title = esc_html__('All', 'cbxwpsimpleaccounting');
				    }

				    $date_range = isset($_REQUEST['cbxlogenableDaterange']) ? esc_html__('From ', 'cbxwpsimpleaccounting') . $_REQUEST['cbxlogfromDate'] . esc_html__('-To-', 'cbxwpsimpleaccounting') . $_REQUEST['cbxlogtoDate'] : esc_html__('All', 'cbxwpsimpleaccounting');


				    $datas = CBXWpsimpleaccountingHelper::logExportQueryData();

				    $cbx_export_html = '<p>' . esc_html__('Type: ' . $type, 'cbxwpsimpleaccounting') . '</p>';
				    $cbx_export_html .= '<p>' . esc_html__('Category: ' . $category_title, 'cbxwpsimpleaccounting') . '</p>';
				    $cbx_export_html .= '<p>' . esc_html__('Account Name: ' . $account_title, 'cbxwpsimpleaccounting') . '</p>';
				    $cbx_export_html .= '<p>' . esc_html__('Date Range: ' . $date_range, 'cbxwpsimpleaccounting') . '</p>';

				    $cbx_export_html = apply_filters('cbxwpsimpleaccountinglog_export_pdf_before_table', $cbx_export_html);

				    $cbx_export_html .= '<table cellspacing="0" cellpadding="1" border="1px solid #ccc" bgcolor="#f6f6f6"><tr bgcolor="#f5f5f5">
                              <td align="center">' . esc_html__("Title", 'cbxwpsimpleaccounting') . ' </td>
                              <td align="center">' . esc_html__("Amt.", 'cbxwpsimpleaccounting') . ' </td>
                              <td align="center">' . esc_html__("Type", 'cbxwpsimpleaccounting') . ' </td>
                              <td align="center">' . esc_html__("Cat.", 'cbxwpsimpleaccounting') . ' </td>
                              <td align="center">' . esc_html__("Note", 'cbxwpsimpleaccounting') . ' </td>
                              <td align="center">' . esc_html__("Add By", 'cbxwpsimpleaccounting') . ' </td>
                              <td align="center">' . esc_html__("Mod By", 'cbxwpsimpleaccounting') . ' </td>
                              <td align="center">' . esc_html__("Add Date", 'cbxwpsimpleaccounting') . ' </td>
                              <td align="center">' . esc_html__("Mod Date", 'cbxwpsimpleaccounting') . ' </td>
                              <td align="center">' . esc_html__("Src. Amt.", 'cbxwpsimpleaccounting') . ' </td>
                              <td align="center">' . esc_html__("Src. Curr.", 'cbxwpsimpleaccounting') . ' </td>
                              <td align="center">' . esc_html__("Tax%", 'cbxwpsimpleaccounting') . ' </td>
                              <td align="center">' . esc_html__("Invoice ID", 'cbxwpsimpleaccounting') . ' </td>
                              <td align="center">' . esc_html__("Account", 'cbxwpsimpleaccounting') . ' </td>';

				    $cbx_export_html = apply_filters('cbxwpsimpleaccountinglog_export_pdf_table_heading', $cbx_export_html);

				    $cbx_export_html .= '<td align="center">' . esc_html__("Final Amt.", 'cbxwpsimpleaccounting') . ' </td>
                          </tr>';

				    $excell_cell_char = '';

				    $objPHPExcel = new PHPExcel();
				    $objPHPExcel->setActiveSheetIndex(0);
				    $objPHPExcel->getActiveSheet()->setCellValue('A1', esc_html__('Title', 'cbxwpsimpleaccounting'));
				    $objPHPExcel->getActiveSheet()->setCellValue('B1', esc_html__('Amount', 'cbxwpsimpleaccounting'));
				    $objPHPExcel->getActiveSheet()->setCellValue('C1', esc_html__('Type.', 'cbxwpsimpleaccounting'));
				    $objPHPExcel->getActiveSheet()->setCellValue('D1', esc_html__('Cat.', 'cbxwpsimpleaccounting'));
				    $objPHPExcel->getActiveSheet()->setCellValue('E1', esc_html__('Note', 'cbxwpsimpleaccounting'));
				    $objPHPExcel->getActiveSheet()->setCellValue('F1', esc_html__('Add By', 'cbxwpsimpleaccounting'));
				    $objPHPExcel->getActiveSheet()->setCellValue('G1', esc_html__('Mod By', 'cbxwpsimpleaccounting'));
				    $objPHPExcel->getActiveSheet()->setCellValue('H1', esc_html__('Add Date', 'cbxwpsimpleaccounting'));
				    $objPHPExcel->getActiveSheet()->setCellValue('I1', esc_html__('Mod Date', 'cbxwpsimpleaccounting'));
				    $objPHPExcel->getActiveSheet()->setCellValue('J1', esc_html__('Source Amount', 'cbxwpsimpleaccounting'));
				    $objPHPExcel->getActiveSheet()->setCellValue('K1', esc_html__('Source Currency', 'cbxwpsimpleaccounting'));
				    $objPHPExcel->getActiveSheet()->setCellValue('L1', esc_html__('Tax', 'cbxwpsimpleaccounting'));
				    $objPHPExcel->getActiveSheet()->setCellValue('M1', esc_html__('Invoice', 'cbxwpsimpleaccounting'));
				    $objPHPExcel->getActiveSheet()->setCellValue('N1', esc_html__('Account', 'cbxwpsimpleaccounting'));
				    $objPHPExcel->getActiveSheet()->setCellValue('O1', esc_html__('Final Amount', 'cbxwpsimpleaccounting'));

				    do_action('cbxwpsimpleaccountinglog_export_other_heading', $objPHPExcel);

				    $objPHPExcel->getActiveSheet()->getStyle('A1:Z1')->getFont()->setBold(true);
				    $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn('A:O')->setAutoSize(true);

				    if ($datas) {


					    foreach ($datas as $i => $data) {
						    //for .xls,.xlsx and .csv
						    $type = ($data->type == '1') ? esc_html__('Income', 'cbxwpsimpleaccounting') : esc_html__('Expense', 'cbxwpsimpleaccounting');
						    $source_amount = ($data->source_amount > 0) ? $data->source_amount : '';
						    $source_currency = ($data->source_currency == 'none') ? '' : $data->source_currency;
						    $mod_by = ($data->mod_by) ? get_user_by('id', $data->mod_by)->display_name : '';
						    $final_amount = ($data->tax != NULL && $data->istaxincluded == 1) ? ($data->amount + ($data->amount * $data->tax) / 100) : $data->amount;

						    $objPHPExcel->getActiveSheet()->setCellValue('A' . ($i + 2), stripslashes($data->title));
						    $objPHPExcel->getActiveSheet()->setCellValue('B' . ($i + 2), $data->amount);
						    $objPHPExcel->getActiveSheet()->setCellValue('C' . ($i + 2), $type);
						    $objPHPExcel->getActiveSheet()->setCellValue('D' . ($i + 2), $data->cattitle);
						    $objPHPExcel->getActiveSheet()->setCellValue('E' . ($i + 2), stripslashes($data->note));
						    $objPHPExcel->getActiveSheet()->setCellValue('F' . ($i + 2), stripslashes(get_user_by('id', $data->add_by)->display_name));
						    $objPHPExcel->getActiveSheet()->setCellValue('G' . ($i + 2), $mod_by);
						    $objPHPExcel->getActiveSheet()->setCellValue('H' . ($i + 2), $data->add_date);
						    $objPHPExcel->getActiveSheet()->setCellValue('I' . ($i + 2), $data->mod_date);
						    $objPHPExcel->getActiveSheet()->setCellValue('J' . ($i + 2), $source_amount);
						    $objPHPExcel->getActiveSheet()->setCellValue('K' . ($i + 2), $source_currency);
						    $objPHPExcel->getActiveSheet()->setCellValue('L' . ($i + 2), $data->tax);
						    $objPHPExcel->getActiveSheet()->setCellValue('M' . ($i + 2), $data->invoice);
						    $objPHPExcel->getActiveSheet()->setCellValue('N' . ($i + 2), $data->accountname);
						    $objPHPExcel->getActiveSheet()->setCellValue('O' . ($i + 2), $final_amount);

						    do_action('cbxwpsimpleaccountinglog_export_other_col', $objPHPExcel, $i, $data);

						    //for pdf table body
						    $cbx_export_html .= '<tr nobr="true">
                            <td align="center">' . stripslashes($data->title) . '</td>
                            <td align="center">' . $data->amount . '</td>
                            <td align="center">' . $type . ' </td>
                            <td align="center">' . $data->cattitle . ' </td>
                            <td align="center">' . stripslashes($data->note) . '</td>
                            <td align="center">' . stripslashes(get_user_by('id', $data->add_by)->display_name) . '</td>
                            <td align="center">' . stripslashes($mod_by) . '</td>
                            <td align="center">' . $data->add_date . '</td>
                            <td align="center">' . $data->mod_date . '</td>
                            <td align="center">' . $source_amount . '</td>
                            <td align="center">' . $source_currency . '</td>
                            <td align="center">' . $data->tax . '</td>
                            <td align="center">' . $data->invoice . '</td>
                            <td align="center">' . $data->accountname . '</td>';

						    $cbx_export_html = apply_filters('cbxwpsimpleaccountinglog_export_pdf_table_col', $cbx_export_html, $data);

						    $cbx_export_html .= '<td align="center">' . $final_amount . '</td>
                          </tr>';
					    }
				    } else {
					    $cbx_export_html .= '<tr nobr="true">
                            <td colspan="13">' . esc_html__('No data found', 'cbxwpsimpleaccounting') . '</td>
                          </tr>';
				    }

				    //for pdf table end.
				    $cbx_export_html .= '</table>';
				    $cbx_export_html .= '<p></p><p>' . esc_html__('Log Generated On', 'cbxwpsimpleaccounting') . ' : ' . date('Y-m-d') . '</p>' . '<p>' . esc_html__('Log Generated By', 'cbxwpsimpleaccounting') . ' : ' . get_bloginfo('name') . '(' . site_url() . ')</a></p>';

				    //for .xls,.xlsx,.csv
				    $objPHPExcel->setActiveSheetIndex(0);

				    ob_clean();
				    ob_start();

				    $filename = 'cbxaccounting-log';
				    switch ($export_format) {
					    case 'csv':
						    // Redirect output to a client’s web browser (csv)
						    $filename = $filename . '.csv';
						    header("Content-type: text/csv");
						    header("Cache-Control: no-store, no-cache");
						    header('Content-Disposition: attachment; filename="' . $filename . '"');
						    $objWriter = new PHPExcel_Writer_CSV($objPHPExcel);
						    $objWriter->setDelimiter(',');
						    $objWriter->setEnclosure('"');
						    $objWriter->setLineEnding("\r\n");
						    $objWriter->setSheetIndex(0);
						    $objWriter->save('php://output');
						    break;
					    case 'xls':
						    // Redirect output to a client’s web browser (Excel5)
						    $filename = $filename . '.xls';
						    header('Content-Type: application/vnd.ms-excel');
						    header('Content-Disposition: attachment;filename="' . $filename . '"');
						    header('Cache-Control: max-age=0');
						    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
						    $objWriter->save('php://output');
						    break;
					    case 'xlsx':
						    // Redirect output to a client’s web browser (Excel2007)
						    $filename = $filename . '.xlsx';
						    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
						    header('Content-Disposition: attachment;filename="' . $filename . '"');
						    header('Cache-Control: max-age=0');
						    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
						    $objWriter->save('php://output');
						    break;
					    case 'pdf':
						    //PDF_PAGE_ORIENTATION = P or L
						    $pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false, false);

						    $user_info = get_userdata(get_current_user_id());
						    $user_name = $user_info->display_name;

						    $title = esc_html__('Log Managment Data', 'cbxwpsimpleaccounting');
						    $subject = esc_html__('Log Managment Data', 'cbxwpsimpleaccounting');
						    $keyword = esc_html__('Log Managment Data', 'cbxwpsimpleaccounting');
						    $header = esc_html__('Accounting Log ', 'cbxwpsimpleaccounting');

						    $pdf->SetCreator($user_name);
						    $pdf->SetAuthor($user_name);
						    $pdf->SetTitle($title);
						    $pdf->SetSubject($subject);
						    $pdf->SetKeywords($keyword);

						    $pdf->SetHeaderData('', '', $header, '', array(0, 0, 0), array(0, 0, 0));


						    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', 20));
						    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

						    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

						    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
						    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
						    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

						    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

						    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

						    /*if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
								require_once(dirname(__FILE__) . '/lang/eng.php');
								$pdf->setLanguageArray($l);
							}*/


						    $pdf->setFontSubsetting(true);

						    $pdf->SetFont('dejavusans', '', 10, '', true);

						    $pdf->AddPage();


						    $html = <<<EOD
                                    $cbx_export_html

EOD;

						    $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

						    // Output pdf document
						    $pdf->Output($filename . '.pdf', 'D');
						    break;
				    }
				    exit;
			    }
		    }


	    }//end method cbxwpsimpleaccountinglog_export

    }