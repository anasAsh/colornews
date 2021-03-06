<?php
/**
 * ColorNews Theme Customizer
 *
 * @package ThemeGrill
 * @subpackage ColorNews
 * @since ColorNews 1.0
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function colornews_customize_preview_js() {
	wp_enqueue_script( 'colornews_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20130508', true );
}
add_action( 'customize_preview_init', 'colornews_customize_preview_js' );

function colornews_customize_register($wp_customize) {

   $wp_customize->get_setting( 'blogname' )->transport = 'postMessage';
   $wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
   $wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

   if ( isset( $wp_customize->selective_refresh ) ) {
    $wp_customize->selective_refresh->add_partial( 'blogname', array(
       'selector'        => '#site-title a',
       'render_callback' => 'colornews_customize_partial_blogname',
    ) );

    $wp_customize->selective_refresh->add_partial( 'blogdescription', array(
       'selector'        => '#site-description',
       'render_callback' => 'colornews_customize_partial_blogdescription',
    ) );
   }

   // Theme important links started
   class COLORNEWS_Important_Links extends WP_Customize_Control {

      public $type = "colornews-important-links";

      public function render_content() {
         //Add Theme instruction, Support Forum, Demo Link, Rating Link
         $important_links = array(
            'theme-info' => array(
               'link' => esc_url('https://themegrill.com/themes/colornews/'),
               'text' => __('Theme Info', 'colornews'),
            ),
            'support' => array(
               'link' => esc_url('https://themegrill.com/support-forum/'),
               'text' => __('Support', 'colornews'),
            ),
            'documentation' => array(
               'link' => esc_url('https://themegrill.com/theme-instruction/colornews/'),
               'text' => __('Documentation', 'colornews'),
            ),
            'demo' => array(
               'link' => esc_url('https://demo.themegrill.com/colornews/'),
               'text' => __('View Demo', 'colornews'),
            ),
            'rating' => array(
               'link' => esc_url('http://wordpress.org/support/view/theme-reviews/colornews?filter=5'),
               'text' => __('Rate this theme', 'colornews'),
            ),
         );
         foreach ($important_links as $important_link) {
            echo '<p><a target="_blank" href="' . $important_link['link'] . '" >' . esc_attr($important_link['text']) . ' </a></p>';
         }
      }

   }

   $wp_customize->add_section('colornews_important_links', array(
      'priority' => 1,
      'title' => __('ColorNews Important Links', 'colornews'),
   ));

   /**
    * This setting has the dummy Sanitizaition function as it contains no value to be sanitized
    */
   $wp_customize->add_setting('colornews_important_links', array(
      'capability' => 'edit_theme_options',
      'sanitize_callback' => 'colornews_links_sanitize'
   ));

   $wp_customize->add_control(new COLORNEWS_Important_Links($wp_customize, 'important_links', array(
      'label' => __('Important Links', 'colornews'),
      'section' => 'colornews_important_links',
      'settings' => 'colornews_important_links'
   )));
   // Theme Important Links Ended

   // Start of the Header Options
   $wp_customize->add_panel('colornews_header_options', array(
      'capabitity' => 'edit_theme_options',
      'description' => __('Change the Header Settings from here as you want.', 'colornews'),
      'priority' => 500,
      'title' => __('Header Options', 'colornews')
   ));

   // logo upload options
   $wp_customize->add_section('colornews_header_logo', array(
      'priority' => 1,
      'title' => __('Header Logo', 'colornews'),
      'panel' => 'colornews_header_options'
   ));

	if ( ! function_exists('the_custom_logo') ) {

		$wp_customize->add_setting('colornews_logo', array(
			'default' => '',
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'esc_url_raw'
		));

		$wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'colornews_logo', array(
			'label' => __('Upload logo for your header here.', 'colornews'),
			'description' => sprintf(__( '%sInfo:%s This option will be removed in upcoming update. Please go to Site Identity section to upload the theme logo.', 'colornews'  ), '<strong>', '</strong>'),
			'section' => 'colornews_header_logo',
			'setting' => 'colornews_logo'
		)));
	}

   $wp_customize->add_setting('colornews_header_logo_placement', array(
      'default' => 'header_text_only',
      'capability' => 'edit_theme_options',
      'sanitize_callback' => 'colornews_radio_select_sanitize'
   ));

   $wp_customize->add_control('colornews_header_logo_placement', array(
      'type' => 'radio',
      'label' => __('Choose the option that you want from below.', 'colornews'),
      'section' => 'colornews_header_logo',
      'choices' => array(
         'header_logo_only' => __('Header Logo Only', 'colornews'),
         'header_text_only' => __('Header Text Only', 'colornews'),
         'show_both' => __('Show Both', 'colornews'),
         'disable' => __('Disable', 'colornews')
      )
   ));

   // home icon enable/disable in primary menu
   $wp_customize->add_section('colornews_home_icon_display_section', array(
      'title' => __('Show Home Icon', 'colornews'),
      'panel' => 'colornews_header_options'
   ));

   $wp_customize->add_setting('colornews_home_icon_display', array(
      'priority' => 2,
      'default' => 0,
      'capability' => 'edit_theme_options',
      'sanitize_callback' => 'colornews_checkbox_sanitize'
   ));

   $wp_customize->add_control('colornews_home_icon_display', array(
      'type' => 'checkbox',
      'label' => __('Check to enable the home icon in the primary menu.', 'colornews'),
      'section' => 'colornews_home_icon_display_section',
      'settings' => 'colornews_home_icon_display'
   ));

   // header top menu enable/disable
   $wp_customize->add_section('colornews_header_top_menu_display_section', array(
      'title' => __('Show Header Top Bar', 'colornews'),
      'panel' => 'colornews_header_options'
   ));

   $wp_customize->add_setting('colornews_header_top_menu_display', array(
      'priority' => 1,
      'default' => 0,
      'capability' => 'edit_theme_options',
      'sanitize_callback' => 'colornews_checkbox_sanitize'
   ));

   $wp_customize->add_control('colornews_header_top_menu_display', array(
      'type' => 'checkbox',
      'label' => __('Check to enable the header top bar. The top header bar includes category menu, date display and social menu .', 'colornews'),
      'section' => 'colornews_header_top_menu_display_section',
      'settings' => 'colornews_header_top_menu_display'
   ));

   $wp_customize->add_setting('colornews_header_top_menu_category_display', array(
      'priority' => 2,
      'default' => 0,
      'capability' => 'edit_theme_options',
      'sanitize_callback' => 'colornews_checkbox_sanitize'
   ));

   $wp_customize->add_control('colornews_header_top_menu_category_display', array(
      'type' => 'checkbox',
      'label' => __('Check to enable the category menu in the top header bar.', 'colornews'),
      'section' => 'colornews_header_top_menu_display_section',
      'settings' => 'colornews_header_top_menu_category_display'
   ));

   // date display enable/disable
   $wp_customize->add_section('colornews_date_display_section', array(
      'title' => __('Show Date', 'colornews'),
      'panel' => 'colornews_header_options'
   ));

   $wp_customize->add_setting('colornews_date_display', array(
      'priority' => 2,
      'default' => 0,
      'capability' => 'edit_theme_options',
      'sanitize_callback' => 'colornews_checkbox_sanitize'
   ));

   $wp_customize->add_control('colornews_date_display', array(
      'type' => 'checkbox',
      'label' => __('Check to show the date in header.', 'colornews'),
      'section' => 'colornews_date_display_section',
      'settings' => 'colornews_date_display'
   ));

   // date in header display type
  $wp_customize->add_setting('colornews_date_display_type', array(
     'default' => 'theme_default',
     'capability' => 'edit_theme_options',
     'sanitize_callback' => 'colornews_radio_select_sanitize'
  ));
 
  $wp_customize->add_control('colornews_date_display_type', array(
     'type' => 'radio',
     'label' => esc_html__('Date in header display type:', 'colornews'),
     'choices' => array(
        'theme_default' => esc_html__('Theme Default Setting', 'colornews'),
        'wordpress_date_setting' => esc_html__('From WordPress Date Setting', 'colornews'),
     ),
     'section' => 'colornews_date_display_section',
     'settings' => 'colornews_date_display_type'
  ));

   // search icon in menu enable/disable
   $wp_customize->add_section('colornews_search_icon_in_menu_section', array(
      'title' => __('Search Icon', 'colornews'),
      'panel' => 'colornews_header_options'
   ));

   $wp_customize->add_setting('colornews_search_icon_in_menu', array(
      'priority' => 5,
      'default' => 0,
      'capability' => 'edit_theme_options',
      'sanitize_callback' => 'colornews_checkbox_sanitize'
   ));

   $wp_customize->add_control('colornews_search_icon_in_menu', array(
      'type' => 'checkbox',
      'label' => __('Check to display the Search Icon in the primary menu.', 'colornews'),
      'section' => 'colornews_search_icon_in_menu_section',
      'settings' => 'colornews_search_icon_in_menu'
   ));

   // random posts in menu enable/disable
   $wp_customize->add_section('colornews_random_post_in_menu_section', array(
      'title' => __('Random Post', 'colornews'),
      'panel' => 'colornews_header_options'
   ));

   $wp_customize->add_setting('colornews_random_post_in_menu', array(
      'priority' => 6,
      'default' => 0,
      'capability' => 'edit_theme_options',
      'sanitize_callback' => 'colornews_checkbox_sanitize'
   ));

   $wp_customize->add_control('colornews_random_post_in_menu', array(
      'type' => 'checkbox',
      'label' => __('Check to display the Random Post Icon in the primary menu.', 'colornews'),
      'section' => 'colornews_random_post_in_menu_section',
      'settings' => 'colornews_random_post_in_menu'
   ));

   // breaking news enable/disable
   $wp_customize->add_section('colornews_breaking_news_section', array(
      'title' => __('Breaking News', 'colornews'),
      'panel' => 'colornews_header_options'
   ));

   $wp_customize->add_setting('colornews_breaking_news', array(
      'priority' => 1,
      'default' => 0,
      'capability' => 'edit_theme_options',
      'sanitize_callback' => 'colornews_checkbox_sanitize'
   ));

   $wp_customize->add_control('colornews_breaking_news', array(
      'type' => 'checkbox',
      'label' => __('Check to enable the breaking news section.', 'colornews'),
      'section' => 'colornews_breaking_news_section',
      'settings' => 'colornews_breaking_news'
   ));

   // primary menu sticky enable/disable
   $wp_customize->add_section('colornews_primary_sticky_menu_section', array(
      'title' => __('Sticky Menu', 'colornews'),
      'panel' => 'colornews_header_options'
   ));

   $wp_customize->add_setting('colornews_primary_sticky_menu', array(
      'priority' => 4,
      'default' => 0,
      'capability' => 'edit_theme_options',
      'sanitize_callback' => 'colornews_checkbox_sanitize'
   ));

   $wp_customize->add_control('colornews_primary_sticky_menu', array(
      'type' => 'checkbox',
      'label' => __('Check to enable the sticky behavior of the primary menu.', 'colornews'),
      'section' => 'colornews_primary_sticky_menu_section',
      'settings' => 'colornews_primary_sticky_menu'
   ));

   // header image position setting
   $wp_customize->add_section('colornews_header_image_position_setting', array(
      'priority' => 6,
      'title' => __('Header Image Position', 'colornews'),
      'panel' => 'colornews_header_options'
   ));

   $wp_customize->add_setting('colornews_header_image_position', array(
      'default' => 'position_two',
      'capability' => 'edit_theme_options',
      'sanitize_callback' => 'colornews_radio_select_sanitize'
   ));

   $wp_customize->add_control('colornews_header_image_position', array(
      'type' => 'radio',
      'label' => __('Header image display position.', 'colornews'),
      'section' => 'colornews_header_image_position_setting',
      'choices' => array(
         'position_one' => __('Display the Header image just above the site title/text.', 'colornews'),
         'position_two' => __('Default: Display the Header image between site title/text and the main/primary menu.', 'colornews'),
         'position_three' => __('Display the Header image below main/primary menu.', 'colornews')
      )
   ));

   $wp_customize->add_setting('colornews_header_image_link', array(
      'default' => 0,
      'capability' => 'edit_theme_options',
      'sanitize_callback' => 'colornews_checkbox_sanitize'
   ));

   $wp_customize->add_control('colornews_header_image_link', array(
      'type' => 'checkbox',
      'label' => __('Check to make header image link back to the home page.', 'colornews'),
      'section' => 'colornews_header_image_position_setting'
   ));
   // End of Header Options

   // Start of the Design Options
   $wp_customize->add_panel('colornews_design_options', array(
      'capabitity' => 'edit_theme_options',
      'description' => __('Change the Design Settings from here as you want for your site.', 'colornews'),
      'priority' => 505,
      'title' => __('Design Options', 'colornews')
   ));

   // FrontPage setting
   $wp_customize->add_section('colornews_front_page_setting', array(
      'priority' => 1,
      'title' => __('Front Page Settings', 'colornews'),
      'panel' => 'colornews_design_options'
   ));
   $wp_customize->add_setting('colornews_hide_blog_front', array(
      'default' => 0,
      'capability' => 'edit_theme_options',
      'sanitize_callback' => 'colornews_checkbox_sanitize'
   ));

   $wp_customize->add_control('colornews_hide_blog_front', array(
      'type' => 'checkbox',
      'label' => __('Check to hide blog posts/static page on the front page.', 'colornews'),
      'section' => 'colornews_front_page_setting'
   ));

   // site layout setting
   $wp_customize->add_section('colornews_site_layout_setting', array(
      'priority' => 2,
      'title' => __('Site Layout', 'colornews'),
      'panel' => 'colornews_design_options'
   ));

   $wp_customize->add_setting('colornews_site_layout', array(
      'default' => 'boxed_layout',
      'capability' => 'edit_theme_options',
      'sanitize_callback' => 'colornews_radio_select_sanitize'
   ));

   $wp_customize->add_control('colornews_site_layout', array(
      'type' => 'radio',
      'label' => __('Choose the required layout for your site. The change is reflected in the whole site', 'colornews'),
      'choices' => array(
         'boxed_layout' => __('Boxed Layout', 'colornews'),
         'wide_layout' => __('Wide Layout', 'colornews')
      ),
      'section' => 'colornews_site_layout_setting'
   ));

   class COLORNEWS_Image_Radio_Control extends WP_Customize_Control {

      public function render_content() {

         if ( empty( $this->choices ) )
            return;

         $name = '_customize-radio-' . $this->id;

         ?>
         <style>
            #colornews-img-container .colornews-radio-img-img {
               border: 3px solid #DEDEDE;
               margin: 0 5px 5px 0;
               cursor: pointer;
               border-radius: 3px;
               -moz-border-radius: 3px;
               -webkit-border-radius: 3px;
            }
            #colornews-img-container .colornews-radio-img-selected {
               border: 3px solid #AAA;
               border-radius: 3px;
               -moz-border-radius: 3px;
               -webkit-border-radius: 3px;
            }
            input[type=checkbox]:before {
               content: '';
               margin: -3px 0 0 -4px;
            }
         </style>
         <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
         <ul class="controls" id='colornews-img-container'>
         <?php
            foreach ( $this->choices as $value => $label ) :
               $class = ($this->value() == $value)?'colornews-radio-img-selected colornews-radio-img-img':'colornews-radio-img-img';
               ?>
               <li style="display: inline;">
               <label>
                  <input <?php $this->link(); ?>style='display:none' type="radio" value="<?php echo esc_attr( $value ); ?>" name="<?php echo esc_attr( $name ); ?>" <?php $this->link(); checked( $this->value(), $value ); ?> />
                  <img src = '<?php echo esc_html( $label ); ?>' class = '<?php echo $class; ?>' />
               </label>
               </li>
               <?php
            endforeach;
         ?>
         </ul>
         <script type="text/javascript">
            jQuery(document).ready(function($) {
               $('.controls#colornews-img-container li img').click(function(){
                  $('.controls#colornews-img-container li').each(function(){
                     $(this).find('img').removeClass ('colornews-radio-img-selected') ;
                  });
                  $(this).addClass ('colornews-radio-img-selected') ;
               });
            });
         </script>
         <?php
      }
   }

   // default layout setting
   $wp_customize->add_section('colornews_default_layout_setting', array(
      'priority' => 3,
      'title' => __('Default layout', 'colornews'),
      'panel'=> 'colornews_design_options'
   ));

   $wp_customize->add_setting('colornews_default_layout', array(
      'default' => 'right_sidebar',
      'capability' => 'edit_theme_options',
      'sanitize_callback' => 'colornews_radio_select_sanitize'
   ));

   $wp_customize->add_control(new COLORNEWS_Image_Radio_Control($wp_customize, 'colornews_default_layout', array(
      'type' => 'radio',
      'label' => __('Select default layout. This layout will be reflected in whole site archives, categories, search page etc. The layout for a single post and page can be controlled from below options.', 'colornews'),
      'section' => 'colornews_default_layout_setting',
      'settings' => 'colornews_default_layout',
      'choices' => array(
         'right_sidebar' => COLORNEWS_ADMIN_IMAGES_URL . '/right-sidebar.png',
         'left_sidebar' => COLORNEWS_ADMIN_IMAGES_URL . '/left-sidebar.png',
         'no_sidebar_full_width' => COLORNEWS_ADMIN_IMAGES_URL . '/no-sidebar-full-width-layout.png',
         'no_sidebar_content_centered' => COLORNEWS_ADMIN_IMAGES_URL . '/no-sidebar-content-centered-layout.png'
      )
   )));

   // default layout for pages
   $wp_customize->add_section('colornews_default_page_layout_setting', array(
      'priority' => 4,
      'title' => __('Default layout for pages only', 'colornews'),
      'panel'=> 'colornews_design_options'
   ));

   $wp_customize->add_setting('colornews_default_page_layout', array(
      'default' => 'right_sidebar',
      'capability' => 'edit_theme_options',
      'sanitize_callback' => 'colornews_radio_select_sanitize'
   ));

   $wp_customize->add_control(new COLORNEWS_Image_Radio_Control($wp_customize, 'colornews_default_page_layout', array(
      'type' => 'radio',
      'label' => __('Select default layout for pages. This layout will be reflected in all pages unless unique layout is set for specific page.', 'colornews'),
      'section' => 'colornews_default_page_layout_setting',
      'settings' => 'colornews_default_page_layout',
      'choices' => array(
         'right_sidebar' => COLORNEWS_ADMIN_IMAGES_URL . '/right-sidebar.png',
         'left_sidebar' => COLORNEWS_ADMIN_IMAGES_URL . '/left-sidebar.png',
         'no_sidebar_full_width' => COLORNEWS_ADMIN_IMAGES_URL . '/no-sidebar-full-width-layout.png',
         'no_sidebar_content_centered' => COLORNEWS_ADMIN_IMAGES_URL . '/no-sidebar-content-centered-layout.png'
      )
   )));

   // default layout for single posts
   $wp_customize->add_section('colornews_default_single_posts_layout_setting', array(
      'priority' => 5,
      'title' => __('Default layout for single posts only', 'colornews'),
      'panel'=> 'colornews_design_options'
   ));

   $wp_customize->add_setting('colornews_default_single_posts_layout', array(
      'default' => 'right_sidebar',
      'capability' => 'edit_theme_options',
      'sanitize_callback' => 'colornews_radio_select_sanitize'
   ));

   $wp_customize->add_control(new COLORNEWS_Image_Radio_Control($wp_customize, 'colornews_default_single_posts_layout', array(
      'type' => 'radio',
      'label' => __('Select default layout for single posts. This layout will be reflected in all single posts unless unique layout is set for specific post.', 'colornews'),
      'section' => 'colornews_default_single_posts_layout_setting',
      'settings' => 'colornews_default_single_posts_layout',
      'choices' => array(
         'right_sidebar' => COLORNEWS_ADMIN_IMAGES_URL . '/right-sidebar.png',
         'left_sidebar' => COLORNEWS_ADMIN_IMAGES_URL . '/left-sidebar.png',
         'no_sidebar_full_width' => COLORNEWS_ADMIN_IMAGES_URL . '/no-sidebar-full-width-layout.png',
         'no_sidebar_content_centered' => COLORNEWS_ADMIN_IMAGES_URL . '/no-sidebar-content-centered-layout.png'
      )
   )));

   // primary color options
   $wp_customize->add_section('colornews_primary_color_setting', array(
      'panel' => 'colornews_design_options',
      'priority' => 7,
      'title' => __('Primary color option', 'colornews')
   ));

   $wp_customize->add_setting('colornews_primary_color', array(
      'default' => '#dc3522',
      'capability' => 'edit_theme_options',
      'sanitize_callback' => 'colornews_color_option_hex_sanitize',
      'sanitize_js_callback' => 'colornews_color_escaping_option_sanitize'
   ));

   $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'colornews_primary_color', array(
      'label' => __('This will reflect in links and many others. Choose a color to match your site.', 'colornews'),
      'section' => 'colornews_primary_color_setting',
      'settings' => 'colornews_primary_color'
   )));

   // custom CSS setting
   if ( ! function_exists( 'wp_update_custom_css_post' ) ) {
   class COLORNEWS_Custom_CSS_Control extends WP_Customize_Control {

      public $type = 'custom_css';

      public function render_content() {
      ?>
         <label>
            <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
            <textarea rows="5" style="width:100%;" <?php $this->link(); ?>><?php echo esc_textarea( $this->value() ); ?></textarea>
         </label>
      <?php
      }

   }

   $wp_customize->add_section('colornews_custom_css_setting', array(
      'priority' => 9,
      'title' => __('Custom CSS', 'colornews'),
      'panel' => 'colornews_design_options'
   ));

   $wp_customize->add_setting('colornews_custom_css', array(
      'default' => '',
      'capability' => 'edit_theme_options',
      'sanitize_callback' => 'wp_filter_nohtml_kses',
      'sanitize_js_callback' => 'wp_filter_nohtml_kses'
   ));

   $wp_customize->add_control(new COLORNEWS_Custom_CSS_Control($wp_customize, 'colornews_custom_css', array(
      'label' => __('Write your custom css here and design live.', 'colornews'),
      'section' => 'colornews_custom_css_setting',
      'settings' => 'colornews_custom_css'
   )));
}
   // End of Design Options

   // Start of the Additional Options
   $wp_customize->add_panel('colornews_additional_options', array(
      'capability' => 'edit_theme_options',
      'description'=> __('Change the Additional Settings from here as you want.', 'colornews'),
      'priority' => 515,
      'title' => __('Additional Options', 'colornews')
   ));

   // related posts
   $wp_customize->add_section('colornews_related_posts_section', array(
      'priority' => 4,
      'title' => __('Related Posts', 'colornews'),
      'panel' => 'colornews_additional_options'
   ));

   $wp_customize->add_setting('colornews_related_posts_activate', array(
      'default' => 0,
      'capability' => 'edit_theme_options',
      'sanitize_callback' => 'colornews_checkbox_sanitize'
   ));

   $wp_customize->add_control('colornews_related_posts_activate', array(
      'type' => 'checkbox',
      'label' => __('Check to activate the related posts.', 'colornews'),
      'section' => 'colornews_related_posts_section',
      'settings' => 'colornews_related_posts_activate'
   ));

   $wp_customize->add_setting('colornews_related_posts', array(
      'default' => 'categories',
      'capability' => 'edit_theme_options',
      'sanitize_callback' => 'colornews_radio_select_sanitize'
   ));

   $wp_customize->add_control('colornews_related_posts', array(
      'type' => 'radio',
      'label' => __('Related Posts Must Be Shown As:', 'colornews'),
      'section' => 'colornews_related_posts_section',
      'settings' => 'colornews_related_posts',
      'choices' => array(
         'categories' => __('Related Posts By Categories', 'colornews'),
         'tags' => __('Related Posts By Tags', 'colornews')
      )
   ));

   // featured image popup check
   $wp_customize->add_section('colornews_featured_image_popup_setting', array(
      'priority' => 6,
      'title' => __('Image Lightbox', 'colornews'),
      'panel' => 'colornews_additional_options'
   ));

   $wp_customize->add_setting('colornews_featured_image_popup', array(
      'default' => 0,
      'capability' => 'edit_theme_options',
      'sanitize_callback' => 'colornews_checkbox_sanitize'
   ));

   $wp_customize->add_control('colornews_featured_image_popup', array(
      'type' => 'checkbox',
      'label' => __('Check to enable the lightbox for the featured images in single post.', 'colornews'),
      'section' => 'colornews_featured_image_popup_setting',
      'settings' => 'colornews_featured_image_popup'
   ));
   // End of Additional Options

   // Start of Category Color Options
   $wp_customize->add_panel('colornews_category_color_panel', array(
      'priority' => 535,
      'title' => __('Category Color Options', 'colornews'),
      'capability' => 'edit_theme_options',
      'description' => __('Change the color of each category items as you want for your site.', 'colornews')
   ));

   $wp_customize->add_section('colornews_category_color_setting', array(
      'priority' => 1,
      'title' => __('Category Color Settings', 'colornews'),
      'panel' => 'colornews_category_color_panel'
   ));

   $i = 1;
   $args = array(
       'orderby' => 'id',
       'hide_empty' => 0
   );
   $categories = get_categories( $args );
   $wp_category_list = array();
   foreach ($categories as $category_list ) {
      $wp_category_list[$category_list->cat_ID] = $category_list->cat_name;

      $wp_customize->add_setting('colornews_category_color_'.get_cat_id($wp_category_list[$category_list->cat_ID]), array(
         'default' => '',
         'capability' => 'edit_theme_options',
         'sanitize_callback' => 'colornews_color_option_hex_sanitize',
         'sanitize_js_callback' => 'colornews_color_escaping_option_sanitize'
      ));

      $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'colornews_category_color_'.get_cat_id($wp_category_list[$category_list->cat_ID]), array(
         'label' => sprintf(__('%s', 'colornews'), $wp_category_list[$category_list->cat_ID] ),
         'section' => 'colornews_category_color_setting',
         'settings' => 'colornews_category_color_'.get_cat_id($wp_category_list[$category_list->cat_ID]),
         'priority' => $i
      )));
      $i++;
   }
   // End of Category Color Options

   // sanitization works
   // radio/select buttons sanitization
   function colornews_radio_select_sanitize( $input, $setting ) {
      // Ensuring that the input is a slug.
      $input = sanitize_key( $input );
      // Get the list of choices from the control associated with the setting.
      $choices = $setting->manager->get_control( $setting->id )->choices;
      // If the input is a valid key, return it, else, return the default.
      return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
   }

   // checkbox sanitize
   function colornews_checkbox_sanitize($input) {
      if ( $input == 1 ) {
         return 1;
      } else {
         return '';
      }
   }

   // color sanitization
   function colornews_color_option_hex_sanitize($color) {
      if ($unhashed = sanitize_hex_color_no_hash($color))
         return '#' . $unhashed;

      return $color;
   }

   function colornews_color_escaping_option_sanitize($input) {
      $input = esc_attr($input);
      return $input;
   }

   // sanitization of links
   function colornews_links_sanitize() {
      return false;
   }
}

add_action('customize_register', 'colornews_customize_register');

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function colornews_customize_partial_blogname() {
   bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function colornews_customize_partial_blogdescription() {
   bloginfo( 'description' );
}

/*****************************************************************************************/

/**
 * Enqueue scripts for customizer
 */
function colornews_customizer_js() {
   wp_enqueue_script( 'colornews_customizer_script', get_template_directory_uri() . '/js/colornews_customizer.js', array("jquery"), 'false', true  );

   wp_localize_script( 'colornews_customizer_script', 'colornews_customizer_obj', array(

      'pro' => __('View PRO version','colornews')

   ) );
}
add_action( 'customize_controls_enqueue_scripts', 'colornews_customizer_js' );

/*
 * Custom Scripts
 */
add_action( 'customize_controls_print_footer_scripts', 'colornews_customizer_custom_scripts' );

function colornews_customizer_custom_scripts() { ?>
<style>
	/* Theme Instructions Panel CSS */
	li#accordion-section-colornews_important_links h3.accordion-section-title, li#accordion-section-colornews_important_links h3.accordion-section-title:focus { background-color: #DC3522 !important; color: #fff !important; }
	li#accordion-section-colornews_important_links h3.accordion-section-title:hover { background-color: #DC3522 !important; color: #fff !important; }
	li#accordion-section-colornews_important_links h3.accordion-section-title:after { color: #fff !important; }
/* Upsell button CSS */
	.themegrill-pro-info,
	.customize-control-colornews-important-links a {
		/* Permalink - use to edit and share this gradient: http://colorzilla.com/gradient-editor/#8fc800+0,8fc800+100;Green+Flat+%232 */
		background: #008EC2;
		color: #fff;
		display: block;
		margin: 15px 0 0;
		padding: 5px 0;
		text-align: center;
		font-weight: 600;
	}

	.customize-control-colornews-important-links a{
		padding: 8px 0;
	}

	.themegrill-pro-info:hover,
	.customize-control-colornews-important-links a:hover {
		color: #ffffff;
		/* Permalink - use to edit and share this gradient: http://colorzilla.com/gradient-editor/#006e2e+0,006e2e+100;Green+Flat+%233 */
		background:#2380BA;
	}
	</style>
<?php
}
