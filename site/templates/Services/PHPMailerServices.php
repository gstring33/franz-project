<?php

namespace App\Services;

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use ProcessWire\ProcessWire;

class PHPMailerServices
{
    /** @var ProcessWire */
    private $wire;
    /** @var PHPMailer */
    private $mailer;
    /** @var string */
    private $from;
    /** @var string */
    private $host;
    /** @var int */
    private $port;
    /** @var string */
    private $username;
    /** @var string */
    private $password;
    /** @var int */
    private $debug_mode;
    /** @var  */
    private $logger;

    /**
     * MailerService constructor.
     * @param string $from
     * @param string $host
     * @param string $port
     * @param string $username
     * @param string $password
     * @param int $debug_mode
     */
    public function __construct() {
        $wire = ProcessWire::getCurrentInstance();
        $this->mailer= new PHPMailer();
        $this->from = $wire->config->mailerFrom;
        $this->host = $wire->config->mailerHost;
        $this->port = $wire->config->mailerPort;
        $this->username = $wire->config->mailerUsername;
        $this->password = $wire->config->mailerPassword;
        $this->debug_mode = intval($wire->config->mailerDebugMode);
        $this->logger = $wire->log;
    }

    /**
     * @param array $addresses
     * @param string $subject
     * @param string $body
     * @return void
     */
    public function send(string $address, string $subject, string $body)
    {
        try {
            $this->initServer($this->host, $this->username, $this->password, $this->port)
                ->initRecipient($this->from, 'Holzwerkerei 67', $address)
                ->initContent($subject, $body);

            if ($this->mailer->send()) {
                return;
            } else {
                $this->logger->error("Email could not be sent. Subject: {$subject} Mailer Error: {$this->mailer->ErrorInfo}");
            }
        }catch (\Exception $exception) {
            $this->logger->error("Email could not be sent. Subject: {$subject} Mailer Error: {$this->mailer->ErrorInfo}");
        }
    }

    /**
     * @param string $host
     * @param string $username
     * @param string $password
     * @param string $port
     * @return PHPMailerServices
     */
    private function initServer(string $host, string $username, string $password, string $port)
    {
        try {
            if ($this->debug_mode == 1) {
                $this->mailer->SMTPDebug = SMTP::DEBUG_SERVER;
            }
            $this->mailer->isSMTP();
            $this->mailer->Host       = $host;
            $this->mailer->SMTPAuth   = true;
            $this->mailer->CharSet    = "UTF-8";
            $this->mailer->Username   = $username;
            $this->mailer->Password   = $password;
            $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $this->mailer->Port       = $port;

        } catch (Exception $e) {
            $this->logger->error("Email could not be sent. Subject: Mailer Error: {$this->mailer->ErrorInfo}");
        }

        return $this;
    }

    /**
     * @param string $from
     * @param string $name
     * @param array $adresses
     * @param string|null $replyTo
     * @param string|null $cc
     * @param string|null $bcc
     * @return PHPMailerServices
     */
    private function initRecipient(string $from, string $name, string $address, ?string $replyTo = null, ?string $cc = null, ?string $bcc =null)
    {
        try {
            $this->mailer->setFrom($from, $name);
            $this->mailer->addAddress($address);
            if ($replyTo !== null) {
                $this->mailer->addReplyTo('info@example.com', 'Information');
            }
            if ($cc !== null) {
                $this->mailer->addCC('cc@example.com');
            }
            if ($bcc !== null) {
                $this->mailer->addBCC('bcc@example.com');
            }
        }catch (Exception $e ) {
            $this->logger->error("Email could not be sent. Mailer Error: {$this->mailer->ErrorInfo}");
        }

        return $this;
    }

    /**
     * @param array $attachments
     * @return PHPMailerServices
     */
    private function initAttachment(array $attachments) {
        try {
            foreach ($attachments as $attachment) {
                $this->mailer->addAttachment($attachment['path'], $attachment['name']);         // Add attachments
            }
        }catch (Exception $e) {
            $this->logger->error("Email could not be sent. Mailer Error: {$this->mailer->ErrorInfo}");
        }

        return $this;
    }

    /**
     * @param string $subject
     * @param string $html
     * @return PHPMailerServices
     */
    private function initContent(string $subject, string $html)
    {
        try {
            $this->mailer->isHTML(true);
            $this->mailer->Subject = $subject;
            $this->mailer->Body    = $html;
            //$this->mailer->AltBody = 'This is the body in plain text for non-HTML mail clients';
        }catch (Exception $e) {
            $this->logger->error("Email could not be sent. Subject: {$subject} Mailer Error: {$this->mailer->ErrorInfo}");
        }

        return $this;
    }
}