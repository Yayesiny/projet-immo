<?php
       if( class_exists( 'WP_Customize_Control' ) ):
       
        
        /**
     * Theme info
     */
    class real_estater_Theme_Info extends WP_Customize_Control {
        public function render_content(){

            $our_theme_infos = array(
                'demo' => array(
                   'link' => esc_url( 'https://theme404.com/preview/?demo=real-estater-pro' ),
                   'text' => esc_html__( 'View Demo', 'real-estater' ),
                ),
                'documentation' => array(
                   'link' => esc_url( 'https://theme404.com/wp-documentation/realestatepro/' ),
                   'text' => esc_html__( 'Documentation', 'real-estater' ),
                ),
                'support' => array(
                   'link' => esc_url( 'https://theme404.com/support/' ),
                   'text' => esc_html__( 'Support', 'real-estater' ),
                ),
            );
            foreach ( $our_theme_infos as $our_theme_info ) {
                echo '<p><a target="_blank" href="' . $our_theme_info['link'] . '" >' . esc_html( $our_theme_info['text'] ) . ' </a></p>';
            }
        ?>
        	<label>
        	    <h2 class="customize-title"><?php echo esc_html( $this->label ); ?></h2>
        	    <span class="customize-text_editor_desc">                 
        	        <ul class="admin-pro-feature-list">   
                        <li><span><?php esc_html_e('One Click Demo Import','real-estater'); ?> </span></li>
        	            <li><span><?php esc_html_e('Modern and elegant design','real-estater'); ?> </span></li>
                        <li><span><?php esc_html_e('2 Homepage Demo','real-estater'); ?> </span></li>
        	            <li><span><?php esc_html_e('100% Responsive theme','real-estater'); ?> </span></li>
        	            <li><span><?php esc_html_e('Advanced Typography','real-estater'); ?> </span></li>
        	            <li><span><?php esc_html_e('Breadcrumb Settings','real-estater'); ?> </span></li>
        	            <li><span><?php esc_html_e('Highly configurable home page','real-estater'); ?> </span></li>
        	            <li><span><?php esc_html_e('Four Footer Widget Areas','real-estater'); ?> </span></li>
        	            <li><span><?php esc_html_e('Sidebar Options','real-estater'); ?> </span></li>
        	            <li><span><?php esc_html_e('Translation ready','real-estater'); ?> </span></li>
                        <li><span><?php esc_html_e('WordPress Live Customizer Based','real-estater'); ?> </span></li>
                        <li><span><?php esc_html_e('Agent Section','real-estater'); ?> </span></li>
                        <li><span><?php esc_html_e('Listing Plugins','real-estater'); ?> </span></li>
                        <li><span><?php esc_html_e('Advanced Search','real-estater'); ?> </span></li>
                        <li><span><?php esc_html_e('15 Home Page Section','real-estater'); ?> </span></li>
        	        </ul>
        	        <?php $real_estater_pro_link = 'https://theme404.com/downloads/real-estater-pro/'; ?>
        	        <a href="<?php echo esc_url($real_estater_pro_link); ?>" class="button button-primary buynow" target="_blank"><?php esc_html_e('Buy Now','real-estater'); ?></a>
        	    </span>
        	</label>
        <?php
        }
    }
    
      
    endif;