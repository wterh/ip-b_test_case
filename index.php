<?php
/*
Plugin Name: wt10posts
Description: Покажем 10-ок последних постов
Version: 1.0
Author: WTERH
*/

class wt10posts {
    public function __construct() {
        // Регистрация шорткода
        add_shortcode('wt10post', [$this, 'wt10p']);
    }

    public function wt10p() {
        try {
            // Проверка наличия постов
            $all_posts = wp_count_posts();
            if ($all_posts->publish == 0) {
                throw new Exception('Постов не найдено.');
            }

            // Получаем последние 10 постов (или меньше, сколько есть)
            $recent_posts = wp_get_recent_posts([
                'numberposts' => 10,
                'post_status' => 'publish'
            ]);

            // Формируем буффер для вывода, но без команды не даем
            $output = '<ul>';
            foreach ($recent_posts as $post) {
                $output .= sprintf(
                    '<li><a href="%s">%s</a></li>',
                    get_permalink($post['ID']),
                    esc_html($post['post_title'])
                );
            }
            $output .= '</ul>';

            // Возвращаем буффер, ведь требовался шорткод
            return $output;
        } catch (Exception $e) {

            // Логирование ошибок, для ВП
            if (function_exists('error_log')) {
                error_log('Не нашлось постов :С : ' . $e->getMessage());
            }
        }
    }
}

// Инициализация плагина
new wt10posts();
