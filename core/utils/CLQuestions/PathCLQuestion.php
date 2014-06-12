<?php

class PathCLQuestion extends CLQuestion
{

    protected function tryAgainMessage()
    {
        
    }

    protected function autocomplete($response)
    {
        if ($response == "\t") {
            echo 'Tab hitted';
        } else {
            echo 'no tab hitted';
        }
    }

    protected function validateResponse($response)
    {
        if ($response === "\t") {
            echo 'Tab hitted';
        }
        return false;
    }

}
