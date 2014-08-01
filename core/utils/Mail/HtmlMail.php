<?php

class HtmlMail extends Mail
{

    protected $identifier;
    protected $currentContentType;
    protected $images;
    protected $charset;

    public function __construct($to = '', $from = '', $message = '')
    {
        $this->identifier = 'multipart_related_boundary';
        $this->charset = 'ISO-8859-1';
        parent::__construct($to, $from, $message);
    }

    protected function setHeaders()
    {
        $contentType = "Content-Type: multipart/related;";
        $contentType.= "\n boundary=\"{$this->identifier}\"";
        $this->headers[] = $contentType;
        //$this->headers[] = "Content-Type:text/html; charset=utf-8";
        $this->headers[] = 'To: ' . $this->to[0];
        if (!empty($this->cc)) {
            $this->headers[] = 'Cc: ' . '<' . implode('>,<', $this->cc) . '>';
        }
        if (!empty($this->bcc)) {
            $this->headers[] = 'Bcc: ' . '<' . implode('>,<', $this->bcc) . '>';
        }
        $this->headers[] = 'From: ' . $this->from;
        $this->headers[] = "Subject: {$this->subject}";
        $this->headers[] = "X-Mailer: PHP/" . phpversion();
    }

    public function getImageContentTypePart($name)
    {
        if ($this->currentContentType !== 'image') {
            $this->currentContentType = 'image';
        } 
        return "\n--{$this->identifier}\n" .
                  "Content-Type: image/jpeg; \n".
                    " name={$name}.jpg\n" .
                  "Content-Transfer-Encoding: base64\n" .
                  "Content-ID: <{$this->identifier}{$name}>\n".
                  "Content-Disposition: inline;\n".
                     " filename={$name}.jpg \n";
        
    }

    public function getHtmlContentTypePart()
    {
        if ($this->currentContentType !== 'html') {
            $this->currentContentType = 'html';
            return "\n--{$this->identifier}\n"
                . "Content-Type: text/html; charset={$this->charset} \n"
                . "Content-Transfer-Encoding: 7bit \n";
        } else {
            return "";
        }
    }

    public function addParagraph($text)
    {
        //$this->message .= $this->getHtmlContentTypePart();
        $this->message .= "<p>" . $text . "</p>\n";
    }

    public function addImage($path)
    {
        $imgName = str_replace(array('-'), array(''), pathinfo($path, PATHINFO_FILENAME));
        $this->images[$imgName] = chunk_split('data:image/' . $type . ';base64,' .base64_encode(file_get_contents($path)));
        
        $this->message .= "<img alt=\"{$imgName}\" src=\""."cid:{$this->identifier}{$imgName}"."\" />\n";
    }

    /**
     * Prepare html message
     */
    protected function prepareMessage()
    {
        $head = "This is a multi-part message in MIME format. \n";
        $head .= $this->getHtmlContentTypePart();
        $head .= "<!DOCTYPE html PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\"> \n"
            . "<html> \n"
            . "<head> \n"
            //. "<meta http-equiv=\"Content-Type\" content=\"multipart/mixed; boundary=\"PHP-mixed-{$this->identifier}\"\" />"
            //. "<meta http-equiv=\"Content-Type\" content=\"text/html; charset={$this->charset}\" />"
            . "<title>" . $this->getSubject() . "</title> \n"
            . "</head> \n"
            . "<body>\n";
        $end = "</body>\n</html>\n";
        $this->message = $head . $this->message . $end;
        
        foreach ($this->images as $imgName => $content ){
            $this->message .= $this->getImageContentTypePart($imgName);
            $this->message .= $content;
        }
        
        //$this->message .= "\n --{$this->identifier}--";
    }

    protected function prepareMail()
    {
        $this->setHeaders();
        $this->prepareMessage();
    }

}
