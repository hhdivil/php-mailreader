<?php

namespace Divil\MailReader;

class MessageAttachment
{

    private $structure;
    private $part;

    public function __construct ($structure, $part)
    {
        $this->structure = $structure;
        $this->part = $part;
    }

    private $_name;
    public function getName ()
    {
        if ($this->_name === null)
        {
            foreach ($this->structure->dparameters as $p)
            {
                if ($p->attribute == 'filename')
                {
                    $this->_name = $p->value;
                    break;
                }
            }
        }
        return $this->_name;
    }

    public function getBody ()
    {
        return base64_decode($this->part);
    }

}
