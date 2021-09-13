<?php

namespace MoneySpinner\Widgets;

use \WP_Widget;
use MoneySpinner\Widgets\BaseInterface;
use function set_transient;
use function get_transient;
use function delete_transient;

class Base extends WP_Widget implements BaseInterface {

    // Class Properties

    // Widget name will appear in UI
    protected $widget_name = '';
    // The id of the widget
    protected $widget_id;
    // Optional class name
    protected $classname = '';
    // Required desription will appear in UI
    protected $widget_description = '';
    // The Fields used in the form
    protected $fields = array();
    // Is caching enabled?
    protected $caching = false;


    // Class Methods

    // Constructor loads the wanted items and calls the parrent.
    public function __construct() {
		$widget_ops = array( 
			'classname'                     => $this->classname,
			'description'                   => $this->widget_description,
            'show_instance_in_rest'         => true,
            'customize_selective_refresh'   => true,
		);
		parent::__construct( $this->widget_id, $this->widget_name, $widget_ops );

        $this->setFields();
        $this->setupCaching();
	}

    /**
     * Add caching settings to the form of the widget
     * 
     */
    public function setupCaching() {
        if($this->caching) {

            $this->fields[] = array(
                'id'        => 'enableCache',
                'title'     => __( 'Enable caching', 'moneyspinner' ),
                'type'      => 'select',
                'options'   => array( 'yes' => __( 'Yes', 'moneyspinner' ), 'no' => __( 'No', 'moneyspinner' ) ),
                'default'   => 'no'
            );

            $this->fields[] = array(
                'id'        => 'cacheTimeout',
                'title'     => __( 'Cache length', 'moneyspinner' ),
                'type'      => 'select',
                'options'   => array( '1' => 1 . __( 'hour', 'moneyspinner' ),
                                      '2' => 2 . __( 'horus', 'moneyspinner' ),
                                      '4' => 4 . __( 'horus', 'moneyspinner' ),
                                      '6' => 6 . __( 'horus', 'moneyspinner' ),
                                      '8' => 8 . __( 'horus', 'moneyspinner' ),
                                      '10' => 10 . __( 'horus', 'moneyspinner' ),
                                      '12' => 12 . __( 'horus', 'moneyspinner' ),
                                      '24' => 24 . __( 'horus', 'moneyspinner' ),
                                      '36' => 36 . __( 'horus', 'moneyspinner' ),
                                      '48' => 48 . __( 'horus', 'moneyspinner' ),
                                      '72' => 72 . __( 'horus', 'moneyspinner' ),
                                ),
                'default'   => '4'
            );
        }
    }

    /**
     * Define the wanted fields as an array of arrays.
     * 
     * should always be overridden 
     */
    public function setFields() {
        error_log('fileds setup');
        
        $this->fields = array();
    }

    public function getFields() {
        return $this->fields;
    }

    /**
     * Get the field id's for updating
     */
    public function getFieldIDs() {
        return array_map( function($set){ return $set['id']; }, $this->fields );
    }

    /**
     * Example of widget layout.  
     * 
     * this should always be overriden
     * 
     * @param array $args
     * @param array $instance 
     */
    public function widget( $args, $instance ) {
        
        $content = false;

        if( $this->caching ) {
            $content = get_transient($args['widget_id']);
        }

        if( !$content ) {
            $content = $this->widget_content( $args, $instance );
        }
        
        if( $this->caching ) {
            set_transient( $args['widget_id'], $content, $instance['cacheTimeout'] * 60 * 60 );
        }

        echo $content;
    }

    public function widget_content( $args, $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : '';
     
        $title = apply_filters( $args['widget_id'] . 'widget_title', $title, $instance, $this->widget_id );

        ob_start();
            echo $args['before_widget'];
            
            if ( $title ) {
                echo $args['before_title'] . $title . $args['after_title'];
            }
        
            echo $args['after_widget'];
        return ob_get_clean();
     
    }

    /**
     * Build the form
     * 
     * loop through the fiels, add default value if needed, and display
     * the wanted field types. 
     * 
     * @param array $instance
     */
    public function form( $instance ) {

        foreach($this->getFields() as $info) {
            if( isset( $instance[ $info['id'] ] ) ) {
                $info['value'] = $instance[ $info['id'] ];
            } else {
                $info['value'] = ( isset( $info['default'] ) ) ? $info['default'] : ''; 
            }

            switch($info['type']) {
                case 'text':
                case 'email':
                case 'password':
                case 'telephone':
                    $this->input( $info, $info['type'] );
                    break;
                case 'textarea':
                    $this->textarea( $info );
                    break;
                case 'select':
                    $this->select( $info );
                    break;
                case 'hidden':
                    $this->hidden( $info );
                    break;    
            }
        }
    }

    /**
     * Build a select field 
     * 
     * @param array $info
     */
    public function select( $info ) {
        ?>
        <div>
            <label for='<?php echo $this->get_field_id( $info['id'] ); ?>'><?php echo $info['title']; ?></label>
            <select name='<?php echo $this->get_field_name( $info['id'] ); ?>' id='<?php echo $this->get_field_id( $info['id'] ); ?>' >
                <?php foreach($info['options'] as $value => $text ) : ?>
                    <option value="<?php echo $value ?>" <?php echo ( $value == $info['value'] ) ? 'selected' : '' ?> ><?php echo esc_attr( $text ) ?></option>
                <?php endforeach; ?>    
            </select>
        </div>
        <?php
    }

    /**
     * Build a textarea field 
     * 
     * @param array $info
     */
    public function textarea( $info ) {
        ?>
        <div>
            <label for='<?php echo $this->get_field_id( $info['id'] ); ?>'><?php echo $info['title']; ?></label>
            <textarea  name='<?php echo $this->get_field_name( $info['id'] ); ?>' id='<?php echo $this->get_field_id( $info['id'] ); ?>' >
                   <?php echo esc_attr( $info['value'] ); ?>
            </textarea>
        </div>
        <?php
    }

    /**
     * Build a hidden field type
     * 
     * @param array $info
     */
    public function hidden( $info ) {
        ?>
        <div>
            <input type='hidden' 
                   value='<?php echo esc_attr( $info['value'] ); ?>' 
                   name='<?php echo $this->get_field_name( $info['id'] ); ?>' 
                   id='<?php echo $this->get_field_id( $info['id'] ); ?>' />
        </div>
        <?php

    }

    /**
     * Build a simple input field type
     * 
     * @param array $info
     * @param string $type
     */
    public function input( $info, $type= 'text' ) {
        ?>
        <div>
            <label for='<?php echo $this->get_field_id( $info['id'] ); ?>'><?php echo $info['title']; ?></label>
            <input type='<?php echo $type ?>' 
                   value='<?php echo esc_attr( $info['value'] ); ?>' 
                   name='<?php echo $this->get_field_name( $info['id'] ); ?>' 
                   id='<?php echo $this->get_field_id( $info['id'] ); ?>' />
        </div>
        <?php
    }

    /**
     * Clear cache if there is one, and then save the contents of the form.
     * 
     * @param array $new_instance;
     * @param array $old_instance; 
     */
    public function update( $new_instance, $old_instance ) {
        
        $this->clearCache();        

        $instance = array();
        
        foreach($this->getFieldIDs() as $field) {
            $instance[ $field ] = ( ! empty( $new_instance[ $field ] ) ) ? strip_tags( $new_instance[ $field ] ) : '';
        }
        
        return $instance;
    }

    /**
     * Clear the transient if it is set. 
     * 
     */
    public function clearCache() {
        if( $this->caching ) {
            delete_transient( $this->id );
        }
    }

}
