<?php

namespace Divil\MailReader;

class ImapClient
{

    private $server;
    private $username;
    private $password;
    private $connection;

    public function __construct ($server, $username, $password)
    {
        $this->server = $server;
        $this->username = $username;
        $this->password = $password;
        $this->connect();
    }

    private function connect ()
    {
        $this->connection = imap_open("{{$this->server}/imap/ssl}INBOX", $this->username, $this->password);
    }

    public function getFolders ()
    {
        $folders = imap_list($this->connection, "{{$this->server}/imap/ssl}", "*");
        var_dump($folders);
    }

    public function getMessages ()
    {
        $arr = [];

        $messages = imap_search($this->connection, "ALL");
        foreach ($messages as $mno)
        {
            $arr[] = new Message($this->connection, $mno);
        }

        return $arr;
    }

}
