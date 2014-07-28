<?php

class PathCLQuestion extends CLQuestion
{

    protected function tryAgainMessage()
    {
        return 'path does not exist, please try again. ';
    }


    protected function validateResponse($response)
    {
        return file_exists($response);
    }

}
