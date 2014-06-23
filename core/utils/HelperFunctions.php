<?php

class HelperFunctions
{

    public static function deleteTree($dir)
    {
        $files = array_diff(scandir($dir), array('.', '..'));
        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? self::deleteTree("$dir/$file") : unlink("$dir/$file");
        }
        return rmdir($dir);
    }

    public static function camelize($word)
    {
        return lcfirst(
                implode('', array_map('ucfirst', array_map('strtolower', explode('-', $word)))));
    }

    public static function decamelize($word)
    {
        return preg_replace(
                '/(^|[a-z])([A-Z])/e', 'strtolower(strlen("\\1") ? "\\1-\\2" : "\\2")', $word
        );
    }

}
