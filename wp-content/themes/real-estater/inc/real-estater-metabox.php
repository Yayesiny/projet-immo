<?php

/**
 * Real Estater Metabox
 *
 * 
 */

add_action('add_meta_boxes', 'real_estater_add_sidebar_layout_box');
function real_estater_add_sidebar_layout_box()
{
    add_meta_box(
         'real_estater_sidebar_layout', // $id
         'Sidebar Layout', // $title
         'real_estater_sidebar_layout_callback', // $callback
         array( 'page', 'post'), // $page
         'normal', // $context
         'high' // $priority
    ); 

}


function real_estater_sidebar_layout_callback(){ 
    global $post;
    $real_estater_sidebar_layout = array(
        'sidebar-left' => array(
            'value'     => 'sidebar-left',
            'label'     => esc_html__( 'Left sidebar', 'real-estater' ),
            'thumbnail' => get_template_directory_uri() . '/inc/images/sidebar-left.png'
        ), 
        'sidebar-right' => array(
            'value' => 'sidebar-right',
            'label' => esc_html__( 'Right sidebar (default)', 'real-estater' ),
            'thumbnail' => get_template_directory_uri() . '/inc/images/sidebar-right.png'
        ),
        'sidebar-both' => array(
            'value'     => 'sidebar-both',
            'label'     => esc_html__( 'Both Sidebar', 'real-estater' ),
            'thumbnail' => get_template_directory_uri() . '/inc/images/sidebar-both.png'
        ),
        
        'sidebar-no' => array(
            'value'     => 'sidebar-no',
            'label'     => esc_html__( 'No sidebar', 'real-estater' ),
            'thumbnail' => get_template_directory_uri() . '/inc/images/sidebar-no.png'
        )
    );
    wp_nonce_field( basename( __FILE__ ), 'real_estater_sidebar_layout_nonce' ); 
    $default = 'sidebar-right';


    $real_estater_sidebar_metalayout = get_post_meta( $post->ID, 'real_estater_sidebar_layout', true );


    if( ! $real_estater_sidebar_metalayout ) {
        $real_estater_sidebar_metalayout = $default;
    }
    ?>
    <table class="form-table">
        <tr>
            <td colspan="4"><em class="f13"><?php echo esc_html__('Choose Sidebar Template','real-estater');?></em></td>
        </tr>

        <tr>
            <td>
                <?php foreach($real_estater_sidebar_layout as $field){  ?>
                    
                    <div class="radio-image-wrapper" style="float:left; margin-right:30px;">
                        <label class="description">
                         <span><img src="<?php echo esc_url( $field['thumbnail'] ); ?>" alt="" /></span></br>
                         <input type="radio" name="real_estater_sidebar_layout" value="<?php echo esc_attr($field['value']); ?>" <?php checked( $field['value'], $real_estater_sidebar_metalayout ) ?>/>&nbsp;<?php echo esc_attr($field['label']); ?>
                        </label>
                    </div>
                <?php } // end foreach 
                ?>
                <div class="clear"></div>
            </td>
        </tr>
    </table>
    <?php } 
/**
 * save the custom metabox data
 * @hooked to save_post hook
 */
function real_estater_save_sidebar_layout( $post_id ) { 
    global $real_estater_sidebar_layout, $post; 
    // Verify the nonce before proceeding.
    if ( !isset( $_POST[ 'real_estater_sidebar_layout_nonce' ] ) || !wp_verify_nonce( $_POST[ 'real_estater_sidebar_layout_nonce' ], basename( __FILE__ ) ) )
        return;
    // Stop WP from clearing custom fields on autosave
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE)  
        return;
    
    if ('page' == $_POST['post_type'] ) {  
        if (!current_user_can( 'edit_page', $post_id ) )  
            return $post_id;  
    } elseif (!current_user_can( 'edit_post', $post_id ) ) {  
        return $post_id;  
    }  
    
    //Execute this saving function
    $old = get_post_meta( $post_id, 'real_estater_sidebar_layout', true); 
    $new = sanitize_text_field($_POST['real_estater_sidebar_layout']);
    if ($new && $new != $old) {  
        update_post_meta($post_id, 'real_estater_sidebar_layout', $new);  
    }

 }
 add_action('save_post', 'real_estater_save_sidebar_layout');