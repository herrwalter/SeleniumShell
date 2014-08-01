<?php

class HtmlMail extends Mail
{

    protected $identifier;
    protected $currentContentType;
    protected $images;

    public function __construct($to = '', $from = '', $message = '')
    {
        $this->identifier = sha1(time());
        parent::__construct($to, $from, $message);
    }

    protected function setHeaders()
    {
        $this->headers[] = 'MIME-Version: 1.0';
        $this->headers[] = "Content-Type: multipart/mixed; boundary=\"PHP-mixed-{$this->identifier}\"";
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
            return "\n--PHP-mixed-{$this->identifier}\n" .
                  "Content-Type: image/jpeg\n" .
                  "Content-Transfer-Encoding: base64\n" .
                  "Content-ID: <PHP-CID-{$this->identifier}{$name}.jpeg>\n";
        } else {
            return '';
        }
    }

    public function getHtmlContentTypePart()
    {
        if ($this->currentContentType !== 'html') {
            $this->currentContentType = 'html';
            return "\n--PHP-mixed-{$this->identifier}\n"
                . "Content-Type: text/html; charset=iso-8859-1 \n";
        } else {
            return "";
        }
    }

    public function addParagraph($text)
    {
        //$this->message .= $this->getHtmlContentTypePart();
        $this->message .= "<p>" . $text . "</p>";
    }

    public function addImage($path)
    {
        $imgName = str_replace(array('-'), array(''), pathinfo($path, PATHINFO_FILENAME));
        $this->images[$imgName] = chunk_split(base64_encode(file_get_contents($path)));
        
        $this->message .= "<img src=\""."id:PHP-CID-{$this->identifier}{$imgName}".".jpeg\" />";
    }

    /**
     * Prepare html message
     */
    protected function prepareMessage()
    {
        $head = $this->getHtmlContentTypePart();
        $head .= "<html>"
            . "<head>"
            . "<meta http-equiv=\"Content-Type\" content=\"multipart/mixed; boundary=\"PHP-mixed-{$this->identifier}\"\" />"
            //. "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\" />"
            . "<title>" . $this->getSubject() . "</title>"
            . "</head>"
            . "<body>";
        $end = "</body></html>";
        $this->message = $head . $this->message . $end;
        
        foreach ($this->images as $imgName => $content ){
            $this->message .= $this->getImageContentTypePart($imgName);
            $this->message .= $content;
        }
    }

    protected function prepareMail()
    {
        $this->setHeaders();
        $this->prepareMessage();
    }

}
