<?php

namespace AIPT;

if (!defined('ABSPATH'))
    exit;

if (!class_exists('\AIPT\\AIPT_descgen')) {
    class AIPT_descgen {
        private static $instance = null;
        private $api_key;
        private $settings;

        private function __construct() {
            $this->api_key = get_option('aipt_openai_api');
            $this->settings = array(
                'aipt_openai_model' => get_option('aipt_openai_model', 'gpt-3.5-turbo'),
                'aipt_temperature' => floatval(get_option('aipt_temperature', '0.7')),
                'aipt_frequency_penalty' => floatval(get_option('aipt_frequency_penalty', '0.0')),
                'aipt_presence_penalty' => floatval(get_option('aipt_presence_penalty', '0.0')),
                'aipt_top_p' => floatval(get_option('aipt_top_p', '1.0')),
                'aipt_best_of' => intval(get_option('aipt_best_of', '1')),
                'aipt_writing_style' => get_option('aipt_writing_style', 'Professional'),
                'aipt_descgen_language' => get_option('aipt_descgen_language', 'English') 
            );

            add_action('wp_ajax_aipt_generate_long_description', array($this, 'aipt_generate_long_description'));
            add_action('wp_ajax_aipt_generate_short_description', array($this, 'aipt_generate_short_description'));
        }

        public static function get_instance() {
            if (is_null(self::$instance)) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        public static function descgen_localize_script($handle) {
            $instance = self::get_instance();
            wp_localize_script($handle, 'aiptDescGenAjax', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('aipt_descgen_nonce'),
                'aipt_openai_model' => $instance->settings['aipt_openai_model'],
                'aipt_temperature' => $instance->settings['aipt_temperature'],
                'aipt_frequency_penalty' => $instance->settings['aipt_frequency_penalty'],
                'aipt_presence_penalty' => $instance->settings['aipt_presence_penalty'],
                'aipt_top_p' => $instance->settings['aipt_top_p'],
                'aipt_best_of' => $instance->settings['aipt_best_of'],
                'aipt_writing_style' => $instance->settings['aipt_writing_style'],
                'aipt_descgen_language' => $instance->settings['aipt_descgen_language']
            ));
        }

        private function aipt_handle_openai_error($body) {
            if (isset($body['error'])) {
                wp_send_json_error(['message' => 'OpenAI API Error: ' . $body['error']['message']]);
                die();
            }
        }

        public function aipt_generate_long_description() {
            if (!check_ajax_referer('aipt_descgen_nonce', 'nonce', false)) {
                wp_send_json_error(['message' => 'Nonce verification failed.']);
                die();
            }
            $title = sanitize_text_field($_POST['title']);
            $writing_style = sanitize_text_field($_POST['aipt_writing_style']);
            $response = $this->aipt_generate_description($title, $writing_style);
            wp_send_json_success(['description' => $response]);
        }

        public function aipt_generate_short_description() {
            if (!check_ajax_referer('aipt_descgen_nonce', 'nonce', false)) {
                wp_send_json_error(['message' => 'Nonce verification failed.']);
                die();
            }
            $title = sanitize_text_field($_POST['title']);
            $writing_style = sanitize_text_field($_POST['aipt_writing_style']);
            $response = $this->aipt_generate_description($title, $writing_style, true);
            wp_send_json_success(['description' => $response]);
        }

        private function aipt_generate_description($title, $writing_style, $short = false) {
            if (!$this->api_key) {
                wp_send_json_error(['message' => 'Missing OpenAI API Key.']);
                die();
            }

            $style_descriptions = [
                'Encouraging' => 'Uses positive and motivating language to highlight the benefits of the product and its positive impact on the user’s life.',
                'Exaggerated' => 'Describes product features and benefits with great excitement and in an exaggerated manner, using attention-grabbing and playful language.',
                'Professional' => 'Utilizes a formal and informative tone to provide detailed and technical information about the product.',
                'Friendly' => 'Employs a warm and approachable tone to establish a personal connection with the reader and build trust.',
                'Storytelling' => 'Narrates stories or usage scenarios related to the product, facilitating empathy and engagement from the reader.',
                'Minimalist' => 'Focuses on communicating only the most important features and benefits in a concise and straightforward manner.',
                'Luxurious and Elegant' => 'Highlights the quality and exclusiveness of the product, focusing on luxury and elegance.',
                'Adventurous' => 'Associates the product with themes of adventure or exploration, invoking excitement and a sense of discovery.',
                'Educational' => 'Uses an informative and instructional tone to explain how to use the product or its benefits.',
                'Humorous' => 'Adds witty comments to the product description to capture the reader’s interest and entertain.'
            ];

            $style_description = $style_descriptions[$writing_style] ?? '';
            $writing_language = $this->settings['aipt_descgen_language'];

            $api_response = wp_remote_post('https://api.openai.com/v1/chat/completions', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $this->api_key
                ],
                'body' => wp_json_encode([
                    "model" => $this->settings['aipt_openai_model'],
                    "temperature" => $this->settings['aipt_temperature'],
                    "frequency_penalty" => $this->settings['aipt_frequency_penalty'],
                    "presence_penalty" => $this->settings['aipt_presence_penalty'],
                    "top_p" => $this->settings['aipt_top_p'],
                    "n" => $this->settings['aipt_best_of'],
                    "messages" => [
                        [
                            "role" => "system",
                            "content" => "You are a product specialist. You are writing product descriptions. Your writing language: {$writing_language}. Your writing style: {$style_description}"
                        ],
                        [
                            "role" => "user",
                            "content" => "Write a " . ($short ? 'very short' : '') . " product description for {$title} product."
                        ]
                    ]
                        ]),
                'timeout' => 90
            ]);

            if (is_wp_error($api_response)) {
                wp_send_json_error(['message' => $api_response->get_error_message()]);
                die();
            }

            $body = json_decode(wp_remote_retrieve_body($api_response), true);
            $this->aipt_handle_openai_error($body);

            if (isset($body['choices'][0]['message']['content'])) {
                return $body['choices'][0]['message']['content'];
            } else {
                wp_send_json_error(['message' => 'OpenAI API did not return a valid description.']);
                die();
            }
        }
    }

    AIPT_descgen::get_instance();
}