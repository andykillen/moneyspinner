<?php
namespace MoneySpinner\Widgets;

use MoneySpinner\Widgets\Base;

class Example extends Base {

    public function __construct() {
        $this->widget_name = __('Example widget', 'moneyspinner');
        $this->widget_description = __('Example widget for testing', 'moneyspinner');
        $this->widget_id = 'example_widget';
        $this->class = 'examplewidget';
        parent::__construct();
    }


    public function setFields() {
        $this->fields = array(
            array(
                'id'      => 'choose',
                'title'     => __( 'Choose', 'moneyspinner' ),
                'type'      => 'select',
                'default'   => 'yes',
                'options'   => array( 'yes' => __( 'Yes', 'moneyspinner' ), 'no' => __( 'No', 'moneyspinner' ) )
            ),
            array(
                'id'      => 'choose1',
                'title'     => __( 'Choose', 'moneyspinner' ),
                'type'      => 'text',
            )
        );
    }

    public function widget_content( $args, $instance ) {
        return "<p>" . $instance['choose1'] . "</p>";
    }
    

}