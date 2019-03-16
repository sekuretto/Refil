<?php
/**
 * Provide a dashboard view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://codeboxr.com
 * @since      1.0.0
 *
 * @package    Cbxwpsimpleaccounting
 * @subpackage Cbxwpsimpleaccounting/admin/partials
 */
if (!defined('WPINC')) {
    die;
}

global  $wpdb;

	$cbaccounting_cat_table = $wpdb->prefix . 'cbaccounting_category'; //expinc category table name
	$cbaccounting_account_table = $wpdb->prefix . 'cbaccounting_account_manager'; //account manager table name


	$cbxlog_cat_list = $wpdb->get_results('SELECT `id`, `title`, `type` FROM ' . $cbaccounting_cat_table . ' order by title asc', ARRAY_A); //Selecting all category
	$cbxlog_account_list = $wpdb->get_results('SELECT `id`, `title`, `type` FROM ' . $cbaccounting_account_table . ' order by title asc', ARRAY_A); //Selecting all accounts


	//request datas
	$cbxlogexpinc_type_val = isset($_REQUEST['cbxlogexpinc_type']) ? absint($_REQUEST['cbxlogexpinc_type']) : 0;
	$cbxlogexpinc_category_val = isset($_REQUEST['cbxlogexpinc_category']) ? absint($_REQUEST['cbxlogexpinc_category']) : 0;
	$cbxlogexpinc_account_val = isset($_REQUEST['cbxlogexpinc_account']) ? absint($_REQUEST['cbxlogexpinc_account']) : 0;
	$cbxlogfromDate = isset($_REQUEST['cbxlogfromDate']) ? $_REQUEST['cbxlogfromDate'] : date('Y-m-01');
	$cbxlogtoDate = isset($_REQUEST['cbxlogtoDate']) ? $_REQUEST['cbxlogtoDate'] : date('Y-m-d');
	$cbxlogenable = isset($_REQUEST['cbxlogenableDaterange']) ? sanitize_text_field($_REQUEST['cbxlogenableDaterange']) : '';


	//var_dump($this->settings_api);
	//Create an instance of our CBXPollLog class
	//$cbxaccountingllog = new CBXWpsimpleaccountinglogListTable($this->settings_api);
	$cbxaccountingllog = new CBXWpsimpleaccountinglogListTable();


	//Fetch, prepare, sort, and filter CBXPollLog data
	$cbxaccountingllog->prepare_items();
	// verify the nonce field generated near the bulk actions menu



?>
<div class="wrap">
	<h2><?php esc_html_e('CBX Accounting: Log Management', 'cbxwpsimpleaccounting'); ?></h2>
	<?php if (current_user_can('edit_cbxaccounting')): ?>
		<p><?php echo '<a class="button button-primary" href="' . admin_url('admin.php?page=cbxwpsimpleaccounting_addexpinc') . '">' . esc_attr__('Add New Income/Expense', 'cbxwpsimpleaccounting') . '</a>'; ?></p>
	<?php endif; ?>


	<div id="cbxwpsimpleaccountinglog_notice" class="notice notice-success inline"></div>
	<div id="poststuff">
		<div id="post-body" class="metabox-holder">
			<!-- main content -->
			<div id="post-body-content">
				<div class="meta-box-sortables ui-sortable">
					<div class="postbox">
						<div class="inside">
							<?php if (!class_exists('TCPDF')): ?>
								<div class="notice notice-info inline">
									<p><?php _e('Please install the <a href="https://wordpress.org/plugins/tcpdf/" target="_blank">TCPDF Library</a> plugin to export as pdf.', 'cbxwpsimpleaccounting');; ?></p>
								</div>
							<?php endif; ?>
							<?php $cbxaccountingllog->views(); ?>
							<form id="cbxwpsimpleaccountinglog_listing" method="post">
								<select name="cbxlogexpinc_type" id="cbxlogexpinc_type">
									<option value="0" <?php echo ($cbxlogexpinc_type_val == 0) ? ' selected="selected" ' : ''; ?> ><?php _e('All', 'cbxwpsimpleaccounting'); ?></option>
									<option value="1" <?php echo ($cbxlogexpinc_type_val == 1) ? ' selected="selected" ' : ''; ?>><?php _e('Income', 'cbxwpsimpleaccounting'); ?></option>
									<option value="2" <?php echo ($cbxlogexpinc_type_val == 2) ? ' selected="selected" ' : ''; ?>><?php _e('Expense', 'cbxwpsimpleaccounting'); ?></option>
								</select>
								<select <?php echo(($cbxlogexpinc_type_val == 0) ? '  disabled ' : ' '); ?>
									name="cbxlogexpinc_category" id="cbxlogexpinc_category">
									<option <?php echo ($cbxlogexpinc_category_val == 0) ? ' selected="selected" ' : ''; ?>
										value="0"><?php esc_html_e('Select Category', 'cbxwpsimpleaccounting'); ?></option>
									<?php
										foreach ($cbxlog_cat_list as $list):

											$class = (($cbxlogexpinc_type_val > 0 && $list['type'] != $cbxlogexpinc_type_val) || ($cbxlogexpinc_type_val == 0)) ? ' class="cbxlogexpinccathidden" ' : '';
											$disabled = (($cbxlogexpinc_type_val > 0 && $list['type'] != $cbxlogexpinc_type_val) || ($cbxlogexpinc_type_val == 0)) ? ' disabled ' : '';
											$selected = (($cbxlogexpinc_type_val > 0 && $cbxlogexpinc_category_val > 0 && $cbxlogexpinc_category_val == $list['id']) ? ' selected="selected" ' : '');

											echo '<option  ' . $disabled . $class . $selected . ' data-type="' . $list['type'] . '" value="' . $list['id'] . '">' . stripslashes(esc_attr($list['title'])) . '</option>';
											?>
										<?php endforeach; ?>
								</select>

								<select name="cbxlogexpinc_account" id="cbxlogexpinc_account">
									<option <?php echo ($cbxlogexpinc_account_val == 0) ? ' selected="selected" ' : ''; ?>
										value="0"><?php esc_html_e('Select Account', 'cbxwpsimpleaccounting'); ?></option>
									<?php
										foreach ($cbxlog_account_list as $list):

											$selected = (($cbxlogexpinc_account_val > 0 && $cbxlogexpinc_account_val > 0 && $cbxlogexpinc_account_val == $list['id']) ? ' selected="selected" ' : '');

											echo '<option class="cbacnt-acc-type-' . $list['type'] . '-color"  ' . $selected . ' data-type="' . $list['type'] . '" value="' . $list['id'] . '">' . stripslashes(esc_attr($list['title'])) . '</option>';
											?>
										<?php endforeach; ?>
								</select>

								<?php
									do_action('cbxwpsimpleaccountinglog_extra_filters');
								?>
								<p>

									<input type="checkbox" id="cbxlogenableDaterange"
										   name="cbxlogenableDaterange"
										   value="cbxlogenable"
										   class="cbxlogenableDaterange" <?php echo($cbxlogenable == 'cbxlogenable' ? 'checked' : ""); ?>><?php _e('Date Range', 'cbxwpsimpleaccounting'); ?>
									<input type="text" id="cbxlogfromDate" name="cbxlogfromDate"
										   style="width:12%;" value="<?php echo $cbxlogfromDate; ?>"
										   class="cbxlogdaterange"/><?php esc_html_e('To', 'cbxwpsimpleaccounting'); ?>
									<input type="text" id="cbxlogtoDate" name="cbxlogtoDate"
										   style="width:12%;" value="<?php echo $cbxlogtoDate; ?>"
										   class="cbxlogdaterange"/>
									<input type="submit" name="cbxfilter_action" id="cbx-post-query-submit"
										   class="button button-primary" value="Filter">
								</p>

								<p><select name="format" class="cbxformatstatement">
										<option id="formatPDF"
												value="pdf" <?php echo (!class_exists('TCPDF')) ? 'disabled' : ''; ?>><?php esc_html_e('pdf', 'cbxwpsimpleaccounting'); ?></option>
										<option id="formatCSV"
												value="csv"><?php esc_html_e('csv', 'cbxwpsimpleaccounting'); ?></option>
										<option id="formatXLS"
												value="xls"><?php esc_html_e('xls', 'cbxwpsimpleaccounting'); ?></option>
										<option id="formatXLSX"
												value="xlsx"><?php esc_html_e('xlsx', 'cbxwpsimpleaccounting'); ?></option>
									</select>
									<input type="submit" name="cbxwpsimpleaccounting_log_export" id="csvExport" class="button"   value="<?php esc_html_e('Export', 'cbxwpsimpleaccounting'); ?>"/>
								</p>
								<?php
									do_action('cbxwpsimpleaccountinglog_extra_buttons');
								?>
								<input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>"/>
								<?php $cbxaccountingllog->display() ?>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="clear"></div>
	</div>
</div>
