<?php


interface ImageEditor {
    
    public function get();
    
    public function set();
    
    public function destroy();
    
    public function save($path);
    
}