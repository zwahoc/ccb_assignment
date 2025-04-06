<?php 
$wcmamtx_pro_settings  = (array) get_option('wcmamtx_pro_settings');  

?>
<table class="widefat wcmamtx_options_table">


	<tr>
		<td><label><?php echo esc_html__('Disable Dashboard Links','customize-my-account-for-woocommerce-pro'); ?></label> <br />
		</td>
		<td>
			<input type="checkbox" data-toggle="toggle"  data-on="<?php echo esc_html__('Yes','customize-my-account-for-woocommerce-pro'); ?>" data-off="<?php echo esc_html__('No','customize-my-account-for-woocommerce-pro'); ?>" class="" name="<?php  echo esc_html__($this->wcmamtx_pro_settings); ?>[disable_dashboard_links]" value="yes" <?php if (isset($wcmamtx_pro_settings['disable_dashboard_links']) && ($wcmamtx_pro_settings['disable_dashboard_links'] == "yes")) { echo 'checked'; } ?>>
			<p><?php echo esc_html__('Turn it on if your theme is already displaying dashboard links and you do not want plugin to duplicate it','customize-my-account-for-woocommerce-pro'); ?></p>
		</td>
	</tr>
    
    <tr>
		<td><label><?php echo esc_html__('Enable Mobile Friendly Sticky Sidebar Menu','customize-my-account-for-woocommerce-pro'); ?>&emsp;<a target="_blank" href="https://www.sysbasics.com/go/customize-demo/"><?php echo esc_html__('Preview','customize-my-account-for-woocommerce'); ?></a></label>
		</td>
		<td>
			<?php wcmamtx_show_disabled_toggle_image(); ?>
			
			
		</td>
	</tr>

  

	<tr>
		<td><label><?php echo esc_html__('Change Default My Account Page','customize-my-account-for-woocommerce'); ?></label> <br />
		</td>
		<td>
			<?php wcmamtx_show_disabled_toggle_image(); ?>
		</td>
	</tr>



	

	<tr>
		<td><label><?php echo esc_html__('Horizontal Navigation Menu','customize-my-account-for-woocommerce'); ?></label> <br />
		</td>
		<td>
			<?php wcmamtx_show_disabled_toggle_image(); ?>
		</td>
	</tr>

	<tr>
		<td><label><?php echo esc_html__('Enable Ajax Navigation Between Endpoints','customize-my-account-for-woocommerce'); ?></label> <br />
		</td>
		<td>
			<?php wcmamtx_show_disabled_toggle_image(); ?>
		</td>
	</tr>

	<tr>
		<td><label><?php echo esc_html__('My Account Navigation Menu Widget','customize-my-account-for-woocommerce'); ?></label> <br />
		</td>
		<td>
			<?php wcmamtx_show_disabled_toggle_image(); ?>
		</td>
	</tr>
</table>