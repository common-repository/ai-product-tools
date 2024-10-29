<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly 
}

// Check if WooCommerce is active
if (!is_plugin_active('woocommerce/woocommerce.php')) {
    // Display a notice in the admin panel
    add_action('admin_notices', 'aipt_woocommerce_notice');
    function aipt_woocommerce_notice() {
        echo '<div class="notice notice-error is-dismissible"><p>Please install and activate WooCommerce first. You can install it <a href="' . esc_url(admin_url('plugin-install.php?s=woocommerce&tab=search&type=term')) . '">here</a>.</p></div>';
    }
    
} else {
    ?>
    <div class="aipt-settings-wrap">
        <h2>AI Product Tools Settings</h2>
        <?php
        settings_errors('aipt_settings');
        ?>
        <form method="post" id="aipt_settings_form" action="">
            <?php
            wp_nonce_field('aipt_settings_action', 'aipt_settings_nonce');
            ?>
            <table class="aipt-settings-table">
                <tr valign="top">
                    <th scope="row">OpenAI API Key:</th>
                    <td><input type="text" name="aipt_openai_api"
                            value="<?php echo esc_attr(get_option('aipt_openai_api')); ?>" /></td>
                </tr>
                <tr valign="top">
    <th scope="row">OpenAI API Model:</th>
    <td>
         <select name="aipt_openai_model" id="aipt_openai_model" class="aipt-select">
            <option value="gpt-4o" <?php selected(get_option('aipt_openai_model', 'gpt-4o'), 'gpt-4o'); ?>>GPT-4o</option>
            <option value="gpt-4" <?php selected(get_option('aipt_openai_model', 'gpt-4'), 'gpt-4'); ?>>GPT-4</option>
    <option value="gpt-3.5-turbo" <?php selected(get_option('aipt_openai_model', 'gpt-3.5-turbo'), 'gpt-3.5-turbo'); ?>>GPT-3.5-Turbo</option>
            <option value="text-davinci-003" <?php selected(get_option('aipt_openai_model', 'text-davinci-003'), 'text-davinci-003'); ?>>Text-Davinci-003</option>
        </select>
                    </td>
                </tr>
                <tr valign="top">
    <th scope="row">Description Language:</th>
    <td>
        <select name="aipt_descgen_language" id="aipt_descgen_language" class="aipt-select">
            <option value="English" <?php selected(get_option('aipt_descgen_language'), 'English'); ?>>English
                            </option>
                            <option value="Chinese" <?php selected(get_option('aipt_descgen_language'), 'Chinese'); ?>>Chinese
                            </option>
                            <option value="Spanish" <?php selected(get_option('aipt_descgen_language'), 'Spanish'); ?>>Spanish
                            </option>
                            <option value="Arabic" <?php selected(get_option('aipt_descgen_language'), 'Arabic'); ?>>Arabic
                            </option>
                            <option value="Hindi" <?php selected(get_option('aipt_descgen_language'), 'Hindi'); ?>>Hindi</option>
                            <option value="Bengali" <?php selected(get_option('aipt_descgen_language'), 'Bengali'); ?>>Bengali
                            </option>
                            <option value="Portuguese" <?php selected(get_option('aipt_descgen_language'), 'Portuguese'); ?>>
                                Portuguese</option>
                            <option value="Russian" <?php selected(get_option('aipt_descgen_language'), 'Russian'); ?>>Russian
                            </option>
                            <option value="Japanese" <?php selected(get_option('aipt_descgen_language'), 'Japanese'); ?>>Japanese
                            </option>
                            <option value="German" <?php selected(get_option('aipt_descgen_language'), 'German'); ?>>German
                            </option>
                            <option value="Turkish" <?php selected(get_option('aipt_descgen_language'), 'Turkish'); ?>>Turkish
                            </option>
                        </select>
                    </td>
                </tr>
                <tr valign="top">
            <th scope="row">Description Writing Style:</th>
            <td>
            <select name="aipt_writing_style" id="aipt_writing_style" class="aipt-select">
            <option value="Encouraging" <?php selected(get_option('aipt_writing_style', 'Professional'), 'Encouraging'); ?>>
                    Encouraging</option>
                <option value="Exaggerated" <?php selected(get_option('aipt_writing_style', 'Professional'), 'Exaggerated'); ?>>
                    Exaggerated</option>
                <option value="Professional" <?php selected(get_option('aipt_writing_style', 'Professional'), 'Professional'); ?>>Professional</option>
                <option value="Friendly" <?php selected(get_option('aipt_writing_style', 'Professional'), 'Friendly'); ?>>
                    Friendly</option>
                <option value="Storytelling" <?php selected(get_option('aipt_writing_style', 'Professional'), 'Storytelling'); ?>>Storytelling</option>
                <option value="Minimalist" <?php selected(get_option('aipt_writing_style', 'Professional'), 'Minimalist'); ?>>
                    Minimalist</option>
                <option value="Luxurious and Elegant" <?php selected(get_option('aipt_writing_style', 'Professional'), 'Luxurious and Elegant'); ?>>Luxurious and Elegant</option>
                <option value="Adventurous" <?php selected(get_option('aipt_writing_style', 'Professional'), 'Adventurous'); ?>>
                    Adventurous</option>
                <option value="Educational" <?php selected(get_option('aipt_writing_style', 'Professional'), 'Educational'); ?>>
                    Educational</option>
                <option value="Humorous" <?php selected(get_option('aipt_writing_style', 'Professional'), 'Humorous'); ?>>
                    Humorous</option>
            </select>
                 </td>
                </tr>
                
                <tr valign="top">
                    <th scope="row">Temperature:</th>
                    <td><div class="nice-form-group">
                        <input type="range" name="aipt_temperature" min="0" max="2" step="0.1"
                            value="<?php echo esc_attr(get_option('aipt_temperature', 1.0)); ?>"
                            oninput="this.nextElementSibling.value = this.value" />
                        <output><?php echo esc_attr(get_option('aipt_temperature', 1.0)); ?></output></div>
                    </td>
                </tr>
                
                <tr valign="top">
                    <th scope="row">Frequency Penalty:</th>
                    <td><div class="nice-form-group">
                        <input type="range" name="aipt_frequency_penalty" min="0" max="2" step="0.01"
                            value="<?php echo esc_attr(get_option('aipt_frequency_penalty', 0.01)); ?>"
                            oninput="this.nextElementSibling.value = this.value" />
                        <output><?php echo esc_attr(get_option('aipt_frequency_penalty', 0.01)); ?></output></div>
                    </td>
                </tr>
                
                <tr valign="top">
                    <th scope="row">Presence Penalty:</th>
                    <td><div class="nice-form-group">
                        <input type="range" name="aipt_presence_penalty" min="0" max="2" step="0.01"
                            value="<?php echo esc_attr(get_option('aipt_presence_penalty', 0.01)); ?>"
                            oninput="this.nextElementSibling.value = this.value" />
                        <output><?php echo esc_attr(get_option('aipt_presence_penalty', 0.01)); ?></output></div>
                    </td>
                </tr>
               
                <tr valign="top">
                    <th scope="row">Top P:</th>
                    <td><div class="nice-form-group">
                        <input type="range" name="aipt_top_p" min="0" max="1" step="0.01"
                            value="<?php echo esc_attr(get_option('aipt_top_p', 0.01)); ?>"
                            oninput="this.nextElementSibling.value = this.value" />
                        <output><?php echo esc_attr(get_option('aipt_top_p', 0.01)); ?></output></div>
                    </td>
                </tr>
                
                <tr valign="top">
                    <th scope="row">Best Of:</th>
                    <td><div class="nice-form-group">
                        <input type="range" name="aipt_best_of" min="1" max="20"
                            value="<?php echo esc_attr(get_option('aipt_best_of', 1)); ?>"
                            oninput="this.nextElementSibling.value = this.value" />
                        <output><?php echo esc_attr(get_option('aipt_best_of', 1)); ?></output></div>
                    </td>
                </tr>
            </table>
            <?php submit_button('Save Changes', 'aipt-save-button', 'aipt_settings_submit'); ?>
        </form>
    </div>
    <?php
}
?>