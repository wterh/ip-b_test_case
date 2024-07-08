<?php
/**
 * Plugin Name: 10posts
 * Description: Simple 10 posts for any page
 * Version: 1.0.0
 * Author: WTERH
 */

// Смотрим, есть ли аналогичный шорт
if (!function_exists('wt10posts')) {

    function wt10posts()
    {
        $output = '';

        // Назначаем путь для логов и ошибок
        $error_log_file = WP_CONTENT_DIR . '/wt10posts-error.log';

        try {
            // Получаем последние 10 постов
            $args = array(
                'posts_per_page' => 10,
                'orderby'        => 'date',
                'order'          => 'DESC',
                'post_type'      => 'post',
                'post_status'    => 'publish',
            );

            // Делаем запрос
            $latest_posts = new WP_Query($args);
            // Обрабатываем запрос и создаем 10-ок ссылок на посты
            if ($latest_posts->have_posts()) {
                while ($latest_posts->have_posts()) {
                    $latest_posts->the_post();
                    $output .= '<a href="' . get_permalink() . '">' . get_the_title() . '</a><br>';
                }

                // Обязательнос сбрасываем данные запроса, на случай, если не одни мы, такие "умные"
                wp_reset_postdata();
            } else {
                $output = 'Нет последних постов.';
            }
        } catch (Exception $e) {
            // Логируем ошибки, если такие случаются
            error_log('wt10posts error: ' . $e->getMessage() . PHP_EOL, 3, $error_log_file);

            $output = 'Произошла ошибка при получении последних постов.';
        }

        return $output;
    }

    // Регистрируцем шорт
    add_shortcode('last10', 'wt10posts');
}

