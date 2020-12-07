<?php

class Helper_Mail
{
    /**
     * SwiftMailer Agent
     */
    protected $mailer;

    /**
     * SwiftMessage
     */
    protected $message;

    /**
     * Array of recipients of the mail
     */
    protected $to = [];

    /**
     * Content of the mail subject of the mail
     */
    protected $subject;

    /**
     * Full html content of the mail to send
     */
    protected $content;

    /**
     * Constructor function to build the instance of transport and message
     *
     * @note: Exception handling should be done outside this class
     */
    public function __construct()
    {
        // Create a new connection to the SMTP server
        $transport = \Swift_SmtpTransport::newInstance(M_SERVER, M_PORT, M_CONN)
            ->setUsername(M_USERNAME)
            ->setPassword(M_PASSWORD);

        // Attempt to start the connection to SMTP
        $transport->start();

        // Create a new mailer object for this transport
        $this->mailer= \Swift_Mailer::newInstance($transport);

        // Create a new message and set the default from field
        $this->message = \Swift_Message::newInstance();
        $this->message->setFrom(array(M_EMAIL => M_NAME));
    }

    /**
     * Setter of a new email to the list of recipients. This validates a correct email before
     * attepting to send and throws an exception if is not well formed
     */
    public function setTo($to)
    {
        // Validate with swift validate the email address before and thwo exception if wrong
        if (!\Swift_Validate::email($to)) {
            throw new Exception("The email address {$to} is not correct");
        }

        // Add the address to the array of recipients
        $this->to[] = $to;

        // Validate the email is not repeated and sort the list
        $this->to = array_unique($this->to);
    }

    /**
     * Set the subject of the email
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * Set the content of the email
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * Function called in the object to send the actual mail.
     *
     * @note: Exception handling should be done outside this class
     */
    public function send()
    {
        $failure = false;

        // Validate if the list of recipients at least has one item
        if (empty($this->to)) {
            throw new Exception("The mail requires at least 1 email recipient");
        }

        // Set the list of recipients to the message object
        $this->message->setTo($this->to);

        // Validate if the subject exists before sending the email
        if (!$this->subject) {
            throw new Exception("The mail subject cannot be empty");
        }

        // Set the subject to the message object
        $this->message->setSubject($this->subject);

        // Validate if the content of the message exist before sending it
        if (!$this->content) {
            throw new Exception("The mail content cannot be empty");
        }

        // Set this content to the body of the message scpecifying html type
        $this->message->setBody($this->content . PHP_EOL . $this->addFooter(),'text/html');

        // Try to send the email through the mailer and if failed then throw exception
        if (!$this->mailer->send($this->message, $failure)) {
            throw new Exception("Failed to send mail for {count($failure)} recipients");
        }
    }

    /**
     * Function to return the generic footer of the company.
     */
    protected function addFooter()
    {
        $template = new Core_CustomCode('email/footer');
        $template->assign('BASE_PATH', APP_URL);
        return $template->printOut();
    }
}