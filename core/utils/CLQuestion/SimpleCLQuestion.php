<?php



class SimpleCLQuestion extends CLQuestion
{
    protected function tryAgainMessage()
    {
        
    }

    protected function validateResponse($response)
    {
        if( $response !== '' ){
            return true;
        }
        return false;
    }
}