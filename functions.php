<?php

function mytheme_customize_register( $wp_customize ) {

    $wp_customize->add_section( 'other_options', array(
        'title' => 'head tag',
        'priority' => 20,
    ) );

    $wp_customize->add_setting( 'insert_tag_tohead', array(
        'type'      => 'option',
        'transport' => 'postMessage',
    ) );

    $wp_customize->add_control( 'insert_tag_tohead', array(
        'settings' => 'insert_tag_tohead',
        'label' => 'Insert text to head tag.',
        'description' => 'Insert this text as is to the head tag, e.g. Google Analytics Tracking Code.',
        'section' => 'other_options',
        'type' => 'textarea',
    ) );
}
add_action( 'customize_register', 'mytheme_customize_register' );

if ( get_option( 'insert_tag_tohead' ) ) {
    add_action( 'wp_head', 'my_insert_tag_tohead' );
    function my_insert_tag_tohead() {
        echo get_option( 'insert_tag_tohead' );
    }
}
