<!doctype html>
<html <?php language_attributes(); ?>>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?php 
            do_action('dns_prefetch');
            wp_head();
        ?>
        <!--[if lt IE 10]>
            <script src='<?php echo get_template_directory_uri('url').'/js/frontend/classlist.min.js'; ?>'></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"></script>
        < ![endif]-->
    </head>
    <body <?php body_class(); ?>>
        <?php do_action('after_body'); ?>
        <a class="skip-link screen-reader-text" href="#content"><?php echo esc_html_x( 'Skip to content', 'screen reader text', 'moneyspinner' ); ?></a>
        <div id="page">
            <?php get_template_part('template/header'); ?>
            <?php do_action("after_header") ?>
            <div id="content" class="container">