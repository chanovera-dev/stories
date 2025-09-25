<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="description" content="<?php bloginfo( 'description' ); ?>">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>
    <header id="main-header" class="block">
        <div class="content header-content">
            <?php
                if ( file_exists( get_template_directory() . '/templates/header/brand.php' ) ) :
                    include_once( TEMPLATEPATH . '/templates/header/brand.php' );
                endif;

                if ( file_exists( get_template_directory() . '/templates/header/main-menu.php' ) ) :
                    include_once( TEMPLATEPATH . '/templates/header/main-menu.php' );
                endif;

                if ( file_exists( get_template_directory() . '/templates/header/search-button.php' ) ) :
                    include_once( TEMPLATEPATH . '/templates/header/search-button.php' );
                endif;

                if ( file_exists( get_template_directory() . '/templates/header/menu-mobile-button.php' ) ) :
                    include_once( TEMPLATEPATH . '/templates/header/menu-mobile-button.php' );
                endif;
            ?>
        </div><!-- .header-content -->
    </header><!-- #main-header -->