<?php

namespace MoneySpinner\Theme;

class Setup {

    final public static function init() {
        add_action('after_theme_setup', [__CLASS__, 'after_theme_setup'], 10);
    }

    public static function after_theme_setup() {

    }
}