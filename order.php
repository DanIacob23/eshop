<?PHP
$sender = 'daniacob587@gmail.com';
$recipient = 'daniacob587@gmail.com';

$to = $sender;

$subject = 'Website Change Reqest';

$headers = "From: " . strip_tags($sender) . "\r\n";
$headers .= "Reply-To: ". strip_tags($sender) . "\r\n";
$headers .= "CC: susan@example.com\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8\r\n";

$message = '<p><strong>This is strong text</strong> while this is not.</p>';



if (mail($to, $subject, $message, $headers))
{
    echo "Message accepted!!!";
    mail($to, $subject, $message, $headers);
}
else
{
    echo "Error: Message not accepted";
}
?>