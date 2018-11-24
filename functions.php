<?php

// add Content column to All Posts Screen in admin
function add_post_columns( $columns ) {

    $new_columns = array();

    foreach ( $columns as $column_name => $column_display_name ) {
        if ( $column_name == 'comments' ) {
            $new_columns['content'] = __( 'Content' );
        }
        $new_columns[ $column_name ] = $column_display_name;
    }

    return $new_columns;
}
add_filter( 'manage_posts_columns' , 'add_post_columns' );

function custom_columns( $column, $post_id ) {
    switch ( $column ) {
        case 'content' :
            $max_length = 100;
            $my_content = get_the_content();
            $my_content = wp_strip_all_tags( $my_content );

            if ( mb_strlen( $my_content ) > $max_length ) {
                $my_content = mb_substr( $my_content, 0, $max_length ) . ' [...]';
            }
            echo $my_content;
            break;
    }
}
add_action( 'manage_posts_custom_column' , 'custom_columns', 10, 2 );


// setting to insert tag to head in customizer
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
