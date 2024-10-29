<?php

namespace AIPT;

if (!defined('ABSPATH'))
    exit;
if (!class_exists('\AIPT\\AIPT_hook')) {
    class AIPT_hook {

        private static $instance = null;

        public static function get_instance() {
            if (is_null(self::$instance)) {
                self::$instance = new self();
            }
            return self::$instance;
        }


        public function __construct() {
            // Sadece admin paneli için stil ve script dosyalarını yükle
            add_action('admin_enqueue_scripts', function () {
                aipt_hook::get_instance()->aipt_enqueue_styles();
                aipt_hook::get_instance()->aipt_enqueue_scripts();
            });
        }

        public function aipt_enqueue_styles() {

            // Public için CSS dosyası yolu ve sürümü
            $public_css_file_name = 'ai-product-tools-public.css';
            $public_css_path = plugin_dir_path(__DIR__) . 'public/css/' . $public_css_file_name;
            $public_css_ver = filemtime($public_css_path);

            // Admin için CSS dosyası yolu ve sürümü
            $admin_css_file_name = 'ai-product-tools-admin.css';
            $admin_css_path = plugin_dir_path(__DIR__) . 'admin/css/' . $admin_css_file_name;
            $admin_css_ver = filemtime($admin_css_path);


            // Public ve Admin CSS dosyalarını yükle
            wp_enqueue_style('ai-product-tools-public', plugin_dir_url(__DIR__) . 'public/css/' . $public_css_file_name, array(), $public_css_ver, 'all');
            wp_enqueue_style('ai-product-tools-admin', plugin_dir_url(__DIR__) . 'admin/css/' . $admin_css_file_name, array(), $admin_css_ver, 'all');

            // FontAwesome yükle
            wp_enqueue_style('ai-product-tools-fontawesome', plugin_dir_url(__DIR__) . 'public/css/all.min.css', array(), '5.15.3');
        }

        public function aipt_enqueue_scripts() {
            // Public ve Admin için JavaScript dosyalarını yükle
            wp_enqueue_script('ai-product-tools-public-js', plugin_dir_url(__DIR__) . 'public/js/ai-product-tools-public.js', array('jquery'), filemtime(plugin_dir_path(__DIR__) . 'public/js/ai-product-tools-public.js'), true);
            wp_enqueue_script('ai-product-tools-admin-js', plugin_dir_url(__DIR__) . 'admin/js/ai-product-tools-admin.js', array('jquery'), filemtime(plugin_dir_path(__DIR__) . 'admin/js/ai-product-tools-admin.js'), true);
            wp_enqueue_script('aipt-descgen', plugin_dir_url(__DIR__) . 'admin/js/aipt-descgen.js', array('jquery'), filemtime(plugin_dir_path(__DIR__) . 'admin/js/aipt-descgen.js'), true);
            // JavaScript lokalizasyonu
            aipt_AjaxHandler::localizeScripts('ai-product-tools-public-js');
            aipt_AjaxHandler::localizeScripts('ai-product-tools-admin-js');
            aipt_descgen::descgen_localize_script('aipt-descgen');
        }

    }

    aipt_hook::get_instance();
}