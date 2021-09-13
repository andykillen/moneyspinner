<?php

namespace MoneySpinner\Helpers;

class CacheBust {
    /**
     * 
     * @param $relative_file_path, the path relative to the root of the theme. i.e. css/filename.css
     * @param $theme_directory, where to look for the files. if left out, will default to the MoneySpinner theme
     * @return the file time or false if the file does not exist
     * 
     */
    final public static function file ( $relative_file_path, $theme_directory = '' ) {
        if($theme_directory = '') {
            $theme_directory = get_stylesheet_directory();
        }

        $theme_directory = trailingslashit($theme_directory);
        
        if(file_exists($theme_directory.$relative_file_path)){
            return filemtime($theme_directory.$relative_file_path);
        } else {
            return false;
        }
    }
}