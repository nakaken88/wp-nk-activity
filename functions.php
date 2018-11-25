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


// setting to add font awesome as category icon
function add_term_fields() {
    echo '
        <div class="form-field">
            <label for="tag-fa">Add font awesome icon</label>
            <textarea name="tag-fa" id="tag-fa" rows="2"></textarea>
            <p>Add font awesome icon. Default: &lt;i class="fas fa-comment-alt"&gt;&lt;/i&gt;</p>
        </div>';
}
add_action( 'category_add_form_fields', 'add_term_fields' );

function edit_term_fields( $term ) {
    $term_id = $term->term_id;
    $teg_fa = esc_html( get_term_meta( $term_id, 'tag-fa', true ) );
    echo '
        <tr class="form-field">
            <th scope="row"><label for="tag-fa">Add font awesome icon</label></th>
            <td>
                <textarea name="tag-fa" id="tag-fa" rows="2" cols="50" class="large-text">'
                . $teg_fa . '</textarea>
                <p>Add font awesome icon. Default: &lt;i class="fas fa-comment-alt"&gt;&lt;/i&gt;</p>
            </td>
        </tr>';
}
add_action( 'category_edit_form_fields','edit_term_fields' );

function save_terms( $term_id ) {
    if( isset( $_POST['tag-fa'] ) ) {
		update_term_meta( $term_id, 'tag-fa', $_POST['tag-fa'] );
	}
}
add_action( 'create_term', 'save_terms' );
add_action( 'edit_terms', 'save_terms' );


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
