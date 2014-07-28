<?php

class OpenCLQuestion extends CLQuestion
{
    
    protected function tryAgainMessage() {
        return 'Please enter the question.';
    }

    protected function validateResponse($response) {
        if( $response !== '' ){
            return true;
        }
        return false;
    }

}