<?php
/*
Plugin Name: Plugin for Money Button
Plugin URI: https://github.com/peddlrph/plugin-for-money-button
Description: Plugin that allows you to embed money button into Wordpress post
Version: 0.1
Author: peddlrph
Author URI: https://github.com/peddlrph 
Text Domain: plugin-for-money-button
*/

function moneybutton_enqueue_javascript() { 
    wp_enqueue_script( 'moneybutton', 'https://www.moneybutton.com/moneybutton.js' ); 
}
	
add_action( 'wp_enqueue_scripts', 'moneybutton_enqueue_javascript' );


function mb_filter( $content ) {

	$default_money_button_field = '{{MONEYBUTTON}}';
	
	$money_button_field = get_option('money_button_field',$default_money_button_field);

	$data_to = get_option('data_to');
	$data_amount = get_option('data_amount');
	$data_currency = get_option('data_currency');
	$data_label = get_option('data_label');
	$mbdiv = '<div class="money-button"  data-to="' . $data_to .'"  data-amount="' . $data_amount .'"  data-currency="' . $data_currency .'"  data-label="' . $data_label .'" ></div>';

	$content = str_replace( $money_button_field, $mbdiv, $content );

	return $content;
}

add_filter('the_content', 'mb_filter' );

add_action('admin_menu', 'money_button_plugin_create_menu');

function money_button_plugin_create_menu() {

    add_submenu_page('options-general.php', __( 'Money Button Settings', 'money-button-plugin' ), __( 'Money Button', 'money-button-plugin' ),'administrator', __FILE__, 'money_button_plugin_settings_page' );

    add_action( 'admin_init', 'register_money_button_plugin_settings' );
}


function register_money_button_plugin_settings() {
    register_setting( 'money-button-plugin-settings-group', 'data_to' );
    register_setting( 'money-button-plugin-settings-group', 'data_amount' );
    register_setting( 'money-button-plugin-settings-group', 'data_currency' );
    register_setting( 'money-button-plugin-settings-group', 'data_label' );
    register_setting( 'money-button-plugin-settings-group', 'money_button_field' );
}

function money_button_plugin_settings_page() {
?>
<div class="wrap">
<h1>Plugin for Money Button</h1>

<form method="post" action="options.php">
    <?php settings_fields( 'money-button-plugin-settings-group' ); ?>
    <?php do_settings_sections( 'money-button-plugin-settings-group' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">User ID</th>
        <td><input type="text" name="data_to" value="<?php echo esc_attr( get_option('data_to') ); ?>" /></td>
        </tr>
         
        <tr valign="top">
        <th scope="row">Amount</th>
        <td><input type="text" name="data_amount" value="<?php echo esc_attr( get_option('data_amount') ); ?>" /></td>
        </tr>
        
        <tr valign="top">
        <th scope="row">Currency</th>
        <td>
            <?php $datacurrency=get_option('data_currency'); ?>
            <select name="data_currency">
              <option value="BSV" <?php echo ($datacurrency == "BSV") ? 'selected' : ''; ?>>Bitcoin SV</option>
              <option value="USD" <?php echo ($datacurrency == "USD") ? 'selected' : ''; ?>>US Dollar</option>
              <option value="CAD" <?php echo ($datacurrency == "CAD") ? 'selected' : ''; ?>>Canadian Dollar</option>
              <option value="CNY" <?php echo ($datacurrency == "CNY") ? 'selected' : ''; ?>>Chinese Yuan</option>
              <option value="PHP" <?php echo ($datacurrency == "PHP") ? 'selected' : ''; ?>>Philippine Peso</option>
              <option value="EUR" <?php echo ($datacurrency == "EUR") ? 'selected' : ''; ?>>Euro</option>
              <option value="AUD" <?php echo ($datacurrency == "AUD") ? 'selected' : ''; ?>>Australian Dollar</option>
              <option value="CHF" <?php echo ($datacurrency == "CHF") ? 'selected' : ''; ?>>Swiss Franc</option>
              <option value="GBP" <?php echo ($datacurrency == "GBP") ? 'selected' : ''; ?>>British Pound Sterling</option>
            </select> 
        </td>
        </tr>
        <tr valign="top">
        <th scope="row">Label</th>
        <td><input type="text" name="data_label" value="<?php echo esc_attr( get_option('data_label') ); ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row">Money Button Field</th>
        <td><input type="text" name="money_button_field" value="<?php echo esc_attr( get_option('money_button_field',$default_money_button_field) ); ?>" /></td>
        </tr>
    </table>
    
    <?php submit_button(); ?>

</form>
</div>
<?php } ?>


