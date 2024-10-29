<?php

namespace AIPT;

if (!defined('ABSPATH'))
    exit;

if (!class_exists('\AIPT\\AIPT_ProductMetabox')) {
    class AIPT_ProductMetabox {

        private static $instance = null;

        public static function get_instance() {
            if (is_null(self::$instance)) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        public function __construct() {
            add_action('add_meta_boxes', [$this, 'add_metabox']);
        }

        public function add_metabox() {
            $icon_url = esc_url(plugins_url('img/icon.png', dirname(__FILE__)));
            $title = sprintf(
                '<img src="%s" class="aipt-icon" alt="%s"> %s',
                $icon_url,
                esc_attr__('AI Product Tools Icon', 'ai-product-tools'),
                esc_html__('AI Product Tools', 'ai-product-tools')
            );

            add_meta_box(
                'aipt_product_metabox',
                $title,
                [$this, 'render_metabox'],
                'product',
                'side',
                'high'
            );
        }

        public function render_metabox($post) {
            ?>
        <div class="aipt-metabox-content">
            <button type="button" class="aipt-generate-desc">
                <i class="animation"></i>
                <i class="fas fa-magic"></i>&nbsp; Generate Product Description
                <i class="animation"></i>
            </button>
            <button type="button" class="aipt-generate-short-desc">
                <i class="animation"></i>
                <i class="fas fa-magic"></i>&nbsp; Generate Short Description
                <i class="animation"></i>
            </button>
        </div>

        <?php
        }

    }

    AIPT_ProductMetabox::get_instance();
}