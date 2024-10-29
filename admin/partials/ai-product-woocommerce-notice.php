<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>

<div class="aipt-woocommerce-notice">
    <div class="aipt-woocommerce-notice-inner">
       <i class="fas fa-exclamation-circle"></i>
        <div class="aipt-alert-title">Wait a second...</div>
        <h2>Please install and activate WooCommerce first.</h2>
                <button class="aipt-alert-box-button" onclick="window.location.href='<?php echo esc_url(admin_url('plugin-install.php?s=woocommerce&tab=search&type=term')); ?>'">Install WooCommerce</button>
    </div>
</div>