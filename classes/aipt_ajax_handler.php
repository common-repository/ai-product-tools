<?php

namespace AIPT;

if (!defined('ABSPATH'))
    exit;
if (!class_exists('\\AIPT\\AIPT_AjaxHandler')) {
    class AIPT_AjaxHandler {

        public static function localizeScripts($handle) {
            wp_localize_script($handle, 'aiptAjax', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('aipt_nonce')
            ));
        }
    }
}