<?php

namespace Divil\MailReader;

class Message
{

    private $connection;

    private $messageNumber;
    public function getMessageNumber ()
    {
        return $this->messageNumber;
    }

    private $header;
    private $structure;

    public function __construct ($connection, $mno)
    {
        $this->connection = $connection;
        $this->messageNumber = $mno;

        $this->load();
    }

    public function load ()
    {
        $this->header = imap_headerinfo($this->connection, $this->messageNumber);
        $this->structure = imap_fetchstructure($this->connection, $this->messageNumber);
    }

    private $_date;
    public function getDate ()
    {
        if ($this->_date === null)
        {
            $this->_date = \DateTime::createFromFormat("U", $this->header->udate);
        }
        return $this->_date;
    }

    private $_subject;
    public function getSubject ()
    {
        if ($this->_subject === null)
        {
            $this->_subject = $this->header->Subject;
        }
        return $this->_subject;
    }

    private $_body;
    public function getBody ()
    {
        if ($this->_body === null)
        {
            if (!property_exists($this->structure, 'parts'))
            {
                $this->_body = imap_body($this->connection, $this->messageNumber);
            }
            else
            {
                // this isn't right just so you know
                // it's not always part 0
                // and sometimes it's split amongst different parts
                $this->_body = $this->getPart(0);
            }
        }

        return $this->_body;
    }

    private function getPart ($partNumber)
    {
        return imap_fetchbody($this->connection, $this->messageNumber, $partNumber);
    }

    private $_from;
    public function getFrom ()
    {
        if ($this->_from === null)
        {
            if (!property_exists($this->header, 'from'))
            {
                $this->_from = 'Unknown';
            }
            else
            {
                throw new \Exception('Not yet implemented');
            }
        }
        return $this->_from;
    }

    public function getAttachments ()
    {
        $attachments = [];
        foreach ($this->structure->parts as $partno => $part)
        {
            if ($part->type == TYPEAPPLICATION)
            {
                $a = new MessageAttachment($part, $this->getPart($partno + 1));
                $attachments[] = $a;
            }
        }
        return $attachments;
    }
}
