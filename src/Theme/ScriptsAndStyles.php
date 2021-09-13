<?php 

namespace MoneySpinner\Theme;

use MoneySpinner\Helpers\CacheBust;

class ScriptsAndStyles {

    final public static function init() {
        add_action( 'wp_enqueue_scripts', [__CLASS__,'frontend_scripts'],10 );
        add_action( 'wp_enqueue_scripts', [__CLASS__,'frontend_styles'],10 );
        add_action( 'wp_enqueue_scripts', [__CLASS__,'remove_frontend_styles'],100 );
        add_action( 'wp_enqueue_scripts', [__CLASS__,'shortcode_conditional_scripts']);

        add_action( 'wp_footer', [__CLASS__,'frontend_remove_script'],1 );

        add_action( 'admin_enqueue_scripts', [__CLASS__,'admin_scripts'] );
        add_action( 'admin_enqueue_scripts', [__CLASS__,'admin_styles'] );

        add_action( 'login_enqueue_scripts', [__CLASS__,'login_scripts'], 1 );
        add_action( 'login_enqueue_scripts', [__CLASS__,'login_styles'], 10 );
    }


    static public function shortcode_conditional_scripts() {
        if( ! is_singular() ) {
            return;
        }
        global $post;
        if( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'tableau') ) {
            wp_enqueue_script( 'tableau-js', 'https://public.tableau.com/javascripts/api/tableau-2.1.1.js', true);
            $script = 'tableau';
            $dependency = ['tableau-js'];
            wp_enqueue_script( 
                $script,
                get_template_directory_uri()."/js/{$script}.min.js",
                $dependency,
                self::cache_bust("/js/{$script}.min.js"),
                true
            );
        }
     }
     /**
      * queue scripts for frontend only
      *
      * @return void
      */
    static public function frontend_scripts(){


        //Default always loaded scripts
        $scripts = [
            // script name          dependency
            'theme'             =>[],
            'ajax'              =>[],
            'cv-elements'       =>[],
            'cookie'            =>['ajax','theme','cv-elements'],
            'menu'              =>['theme'],
            'search'            =>['theme'],
            'consent'           =>['ajax','cookie','theme']
        ];

        //Loaded scripts for Posts only
        if(is_single()){
            $scripts['related'] = ['ajax','theme','cv-elements'];
            $scripts['didyoufind'] = [];
        }
        //Loaded for Posts or Pages
        if(is_singular()){
            $scripts['resize'] = [];
            $scripts['externallinks'] = [];
            $scripts['socialmedia'] = [];
        }

        if(is_404()){
            // Reducing to minimal
            $scripts = [
                // script name          dependency
                'theme'             =>[],
                'menu'              =>['theme'],
            ];
        }

        // If the GDPR plugin from RNW is installed remove the basic consent management
        if(get_theme_mod('gdpr_banner_background_color') && isset($scripts['consent'])){
            unset($scripts['consent']);
        }

        $fileType = self::MinifiedOrNot();

        foreach($scripts as $script => $dependency){            
            if (wp_script_is( $script, 'registered' )) {
                wp_enqueue_script( $script);
            } else {
                
                self::enqueue_script($script, $dependency, $fileType, '//frontend//');
                
            }


            
        }

        wp_localize_script( 'theme', 'theme', ScriptLocalizations::as_array() );

        if(is_singular()){
            wp_enqueue_script( 'comment-reply' );
        }

    }

    public static function enqueue_script($script, $dependency, $fileType, $directory = '/') {
        wp_enqueue_script( 
            $script,
            get_template_directory_uri()."/js{$directory}{$script}{$fileType}.js",
            $dependency,
            CacheBust::file("js{$directory}{$script}{$fileType}.js"),
            true
        );
    }

    public static function MinifiedOrNot(){
        return (MONEYSPINNER_ENVIRONMENT === 'prod') ? '.min' : '';
    }

    /**
     * remove scripts as needed from the front end.
     *
     * Relies on all scripts being loaded via the footer.
     *
     * @return void
     */
    static public function frontend_remove_script(){
        if( !is_singular() ){
            wp_dequeue_script('wp-embed');
        }
    }
     /**
      * queue scripts for login page only
      *
      * @return void
      */
    static public function login_scripts(){

    }

     /**
      * queue scripts for admin pages only
      *
      * @return void
      */
    static public function admin_scripts($hook){
        $scripts_array = [
            'video-admin' => [],
        ];

        foreach($scripts_array as $script => $dependency ){
            wp_enqueue_script( "{$script}",
                get_template_directory_uri()."/js/admin/{$script}.js",
                $dependency,
                self::cache_bust("/js/admin/{$script}.js"),
                true
            );
        }



        if ( ! in_array( $hook, ['post.php', 'post-new.php'] ) ) {
            return;
        }

        wp_enqueue_script( 'page-template', get_template_directory_uri() . '/js/admin/page-template-fields.js', ['jquery'] );

    }

    /**
     * remove unneeded styles
     *
     * @return void
     */
    static public function remove_frontend_styles(){
        // remove WP blocks from none singular pages
        if(!is_singular()){
            wp_dequeue_style( 'wp-block-library' );
        }
    }

    /**
     * Load all styles
     */
    static public function frontend_styles(){

        // default to screen if nothing else is available
        $base_style = 'screen';
        // show only homepage CSS when on homepage
        if(is_home() || is_front_page() ){
            $base_style = 'homepage';
        }
        // Archive only CSS (Date, Author, Tag, Category)
        if( is_archive() ){
            $base_style = 'archive';
        }
        // Post type only
        if( is_single() ){
            $base_style = 'single';
        }

        $styles[$base_style] = [
                        'dependency' => [],
                        'media' => 'all'
                    ];

        $styles['print'] = [
                        'dependency' => [$base_style],
                        'media' => 'print'
                    ];

            foreach($styles as $style => $settings){
            wp_enqueue_style( "{$style}",
                           get_template_directory_uri()."/css/{$style}.min.css",
                           $settings['dependency'],
                           self::cache_bust("/css/{$style}.min.css"),
                           $settings['media']
                        );
        }

    }

    static public function login_styles(){

    }

    static public function admin_styles(){

        $admin_styles = [
            'video-admin'=>[
                'dependency' => [],
                'media' => 'all'
             ],
        ];

        foreach($admin_styles as $style => $settings){
            wp_enqueue_style( "{$style}",
                           get_template_directory_uri()."/css/admin/{$style}.css",
                           $settings['dependency'],
                           self::cache_bust("/css/admin/{$style}.css"),
                           $settings['media']
                        );
        }

    }


    static public function cache_bust($path){
        if(file_exists(get_template_directory() . $path )){
            return filemtime(get_template_directory() . $path );
        }
        return null;
    }

}