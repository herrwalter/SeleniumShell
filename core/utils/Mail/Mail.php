<?php

class Mail
{

    protected $to = array();
    protected $cc = array();
    protected $bcc = array();
    protected $from = '';
    protected $message = '';
    protected $headers = array();
    protected $subject = '';

    public function __construct($to = '', $from = '', $message = '')
    {
        if( $to !== ''){
            $this->addTo($to);
        }
        if( $from !== ''){
            $this->setFrom($from);
        }
        if( $message !== ''){
            $this->setMessage($message);
        }
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    public function getTo()
    {
        return $this->to;
    }

    public function getCc()
    {
        return $this->cc;
    }

    public function getBcc()
    {
        return $this->bcc;
    }

    public function setTo(array $to)
    {
        $this->to = $to;
    }

    public function addTo($to)
    {
        if (is_array($to)) {
            $this->to = array_merge($this->to, $to);
        } else if (is_string($to)) {
            $this->to[] = $to;
        }
    }

    public function setCc(array $cc)
    {
        $this->cc = $cc;
    }

    public function addCc($cc)
    {
        if (is_array($cc)) {
            $this->cc = array_merge($this->cc, $cc);
        } else if (is_string($cc)) {
            $this->cc[] = $cc;
        }
    }

    public function setBcc(array $bcc)
    {
        $this->bcc = $bcc;
    }

    public function addBcc($bcc)
    {
        if (is_array($bcc)) {
            $this->bcc = array_merge($this->bcc, $bcc);
        } else if (is_string($bcc)) {
            $this->bcc[] = $bcc;
        }
    }

    public function getFrom()
    {
        return $this->from;
    }

    public function setFrom($from)
    {
        $this->from = $from;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function setMessage($message)
    {
        if (!is_string($message)) {
            throw new ErrorException('Message must be a string.');
        }
        $this->message = $message;
    }

    protected function setHeaders()
    {
        $this->headers[] = 'MIME-Version: 1.0';
        $this->headers[] = 'Content-type: text; charset=utf-8';
        $this->headers[] = 'To: ' . implode(',', $this->to);
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

    public function addToMessage($text)
    {
        $this->message .= $text;
    }

    protected function validateMail()
    {
        if (empty($this->to)) {
            throw new ErrorException('Mail sending failed: to is not set');
        }
        if ($this->from == '') {
            throw new ErrorException('Mail sending failed: from is not set');
        }
        if ($this->message == '') {
            throw new ErrorException('Mail sending failed: message is not set');
        }
        if ($this->subject == '') {
            throw new ErrorException('Mail sending failed: subject is not set');
        }
    }

    protected function prepareMail()
    {
        $this->setHeaders();
    }

    public function send()
    {
        $this->validateMail();
        $this->prepareMail();
        try {
            var_dump($this->headers);
            var_dump(implode(',', $this->to));
            var_dump($this->subject);
            
            mail(implode(',', $this->to), $this->subject, $this->message, implode("\r\n", $this->headers));
        } catch (Exception $ex) {
            throw $ex;
        }
    }

}
