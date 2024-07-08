<?php
/*
Plugin Name: Recent Posts Plugin
Description: Displays the latest 10 posts using a shortcode and logs errors.
Version: 1.0
Author: Your Name
*/

class wt10Plugin {
    public function __construct() {
        // Регистрация шорткода
        add_shortcode('recent_posts', [$this, 'display_recent_posts']);
    }

    public function display_recent_posts() {
        try {
            // Проверка наличия постов
            $all_posts = wp_count_posts();
            if ($all_posts->publish == 0) {
                throw new Exception('No posts found.');
            }

            // Получаем последние 10 постов (или меньше)
            $recent_posts = wp_get_recent_posts([
                'numberposts' => 10,
                'post_status' => 'publish'
            ]);

            // Формируем HTML вывод
            $output = '<ul>';
            foreach ($recent_posts as $post) {
                $output .= sprintf(
                    '<li><a href="%s">%s</a></li>',
                    get_permalink($post['ID']),
                    esc_html($post['post_title'])
                );
            }
            $output .= '</ul>';

            return $output;
        } catch (Exception $e) {
            // Логирование ошибки в лог файл WordPress
            if (function_exists('error_log')) {
                error_log('Error displaying recent posts: ' . $e->getMessage());
            }
            return '<p>There was an error displaying recent posts.</p>';
        }
    }
}

// Инициализация плагина
new wt10Plugin();