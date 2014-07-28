<?php


class Example_URLHandler extends SeleniumShell_Abstract_URLHandler {
    
    protected function getBaseUrls() {
        return array(
            'example-environment' => 'http://www.google.nl'
        );
    }

    protected function getProjectConfigPath() {
        return __DIR__ . "..//config//project.ini";
    }

    protected function getUris() {
        return array(
            Example_Pages::MAIN => '/'
        );
    }

}
