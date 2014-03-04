<?php


class SeleniumShell_Mailbox {

    protected $_stream;
    private $_password;
    private $_username;
    protected $_host;
    protected $_mailbox;

    public function __construct($host, $username, $password) {
        $this->_host = $host;
        $this->_username = $username;
        $this->_password = $password;
        $this->_connect();
    }

    public function __destruct() {
        $this->_disconnect();
    }

    /**
     * Connect to mailbox
     */
    private function _connect() {
        $this->_stream = imap_open($this->_host, $this->_username, $this->_password);
    }

    /**
     * Disconnect from current stream.
     */
    private function _disconnect() {
        imap_close($this->_stream);
    }

    /**
     * Get all mailboxes
     * @return array mailboxes
     */
    public function getMailboxes() {
        return imap_list($this->_stream, $this->_host, '*');
    }

    public function getMailboxState() {
        return imap_mailboxmsginfo($this->_stream);
    }

    public function getUnreadEmails() {
        $mailNumbers = imap_search($this->_stream, 'UNSEEN');
        $mails = array();
        foreach ($mailNumbers as $mailNr) {
            $mail = imap_headerinfo($this->_stream, $mailNr);
            $attachments = array();
            $structure = imap_fetchstructure($this->_stream, $mailNr);
            if (isset($structure->parts) && count($structure->parts)) {
                for ($i = 0; $i < count($structure->parts); $i++) {
                    $attachments[$i] = array(
                        'is_attachment' => false,
                        'filename' => '',
                        'name' => '',
                        'attachment' => '');

                    if ($structure->parts[$i]->ifdparameters) {
                        foreach ($structure->parts[$i]->dparameters as $object) {
                            if (strtolower($object->attribute) == 'filename') {
                                $attachments[$i]['is_attachment'] = true;
                                $attachments[$i]['filename'] = $object->value;
                            }
                        }
                    }

                    if ($structure->parts[$i]->ifparameters) {
                        foreach ($structure->parts[$i]->parameters as $object) {
                            if (strtolower($object->attribute) == 'name') {
                                $attachments[$i]['is_attachment'] = true;
                                $attachments[$i]['name'] = $object->value;
                            }
                        }
                    }

                    if ($attachments[$i]['is_attachment']) {
                        $attachments[$i]['attachment'] = imap_fetchbody($this->_stream, $mailNr, $i + 1);
                        if ($structure->parts[$i]->encoding == 3) { // 3 = BASE64
                            $attachments[$i]['attachment'] = base64_decode($attachments[$i]['attachment']);
                        } elseif ($structure->parts[$i]->encoding == 4) { // 4 = QUOTED-PRINTABLE
                            $attachments[$i]['attachment'] = quoted_printable_decode($attachments[$i]['attachment']);
                        }
                    }
                }
            }
            if (count($attachments) != 0) {
                foreach ($attachments as $at) {
                    if ($at['is_attachment'] == 1) {
                        file_put_contents('D:/wamp/www/seleniumtests/attachments/' .$at['filename'], $at['attachment']);
                        echo '<a href="'.'D:/wamp/www/seleniumtests/attachments/' .$at['filename'] . '" >'.$at['name'].'</a>';
                    }
                }
            }
            $mails[] = $mail;
        }
        return $mails;
    }

    public function getHeaders() {
        return imap_headers($this->_stream);
    }

}