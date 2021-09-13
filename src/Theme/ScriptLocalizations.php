<?php

namespace MoneySpinner\Theme;

class ScriptLocalizations {

    static public function languageStrings(){
        return [
            'search_title' => _x('Enter search terms', 'search form title', 'moneyspinner')
        ];
    }

    static public function locationStrings(){
        return [
            'json'=> [
                'posts'     =>'/wp-json/cv/v1/posts',
                'related'   =>'/wp-json/cv/v1/related',
            ],
            'css' => [
                'opera'     => apply_filters ('opera-fallback-url' , trailingslashit( get_template_directory_uri() ). "css/opera-sprite.scss")
            ],
            'cookie' => [
                'info'      =>'/wp-json/cv/v1/cookie',
                'timeout'   => 90,
                'name'      => 'cookieconcent',
            ],            
        ];
    }

    static public function as_array(){
        return array_merge(self::languageStrings(), self::locationStrings() );
    }
}