<?php

require_once(__DIR__ . '/../src/ImapClient.php');
require_once(__DIR__ . '/../src/Message.php');
require_once(__DIR__ . '/../src/MessageAttachment.php');

use Divil\MailReader\ImapClient;

$server = '';
$username = '';
$password = '';

$client = new ImapClient($server, $username, $password);
foreach ($client->getMessages() as $message)
{
    print "\n\n";

    print $message->getMessageNumber() . "\n";
    print $message->getFrom() . "\n";
    print $message->getSubject() . "\n";
    print $message->getDate()->format("Y-m-d H:i") . "\n";

    foreach ($message->getAttachments() as $attachment)
    {
        file_put_contents($attachment->getName(), $attachment->getBody());
    }

    print "\n" . str_pad('', 100, '-') . "\n";
}
