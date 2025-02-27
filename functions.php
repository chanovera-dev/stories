<?php
/**
 * Stories engine room
 */

/**
 * Assign the Stories version to a var
 */
$theme = wp_get_theme( 'stories' );
$stories_version = $theme['Version'];

/**
 * WordPress functions core
 */
$stories = (object) array(
    'version' => $stories_version,

    /**
     * Initialize all the things
     */
    'core'              => require_once 'inc/core.php',
);