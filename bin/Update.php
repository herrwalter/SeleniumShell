<?php

class Update
{

    public function __construct()
    {
        $feed = new DownloadProcess('http://selenium-release.storage.googleapis.com/', '', 'looking for updates..');
        $this->_feed = simplexml_load_string($feed->getContents());
    }

    /**
     * Searches the selenium-release.storage.googleapis.com feed on key
     * for the latests selenium-standalone version by the name you provide.
     */
    public function getLastModifiedFeedByKeyContaining($name)
    {
        $dateLast = 0;
        $lastVersion = null;
        foreach ($this->_feed as $info) {
            $date = strtotime($info->LastModified);
            if (strpos($info->Key, $name) > -1 && $date && $date > $dateLast) {
                $dateLast = $date;
                $lastVersion = $info;
            }
        }
        return $lastVersion;
    }

}
