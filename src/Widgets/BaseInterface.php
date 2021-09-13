<?php

namespace MoneySpinner\Widgets;

interface BaseInterface {

    // set fields array.
    public function setFields();

    // Return fields array.
    public function getFields();

    // Return id's of fields.
    public function getFieldIDs();

    // Show widget content.
    public function widget_content( $args, $instance );

    // The normal widget display.
    public function widget( $args, $instance );

    // The form of the widget.
    public function form( $instance );

    // Updating the form values and saving.
    public function update( $new_instance, $old_instance );

}
