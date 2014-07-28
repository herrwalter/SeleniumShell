<?php


class Example_URLHandler extends SeleniumShell_Abstract_URLHandler {
    
    protected function getBaseUrls() {
        return array(
            'example-environment' => 'http://www.google.nl'
        );
    }

    protected function getProjectConfigPath() {
        $path = __DIR__ . "\\..\\config\\project.ini";
        return $path;
    }

    protected function getUris() {
        return array(
            Example_Pages::MAIN => '/'
        );
    }

}
