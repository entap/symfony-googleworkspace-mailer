<?php

namespace Symfony\Component\Mailer\Bridge\Google\Transport;

use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mime\MessageConverter;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class GoogleWorkspaceTransport extends AbstractTransport
{
    protected string $credentials;

    /**
     * Create a new GoogleWorkspaceTransport instance.
     *
     * @param array $config
     * @param EventDispatcherInterface|null $dispatcher
     * @param LoggerInterface|null $logger
     * @throws Throwable
     */
    public function __construct(array                    $config = [],
                                EventDispatcherInterface $dispatcher = null,
                                LoggerInterface          $logger = null)
    {
        parent::__construct($dispatcher, $logger);

        throw_if(!isset($config['credentials']), \Exception::class, 'credentials is not specified.');
        $this->credentials = $config['credentials'];
    }

    /**
     * Create a new Google_Client instance.
     *
     * @param string $subject Impersonate email address
     * @return \Google_Client
     */
    protected function createGoogleClient(string $subject): \Google_Client
    {
        $client = new \Google_Client();
        $client->setScopes([\Google_Service_Gmail::GMAIL_SEND]);
        $client->setAuthConfig(config('services.google.credentials'));
        $client->setSubject($subject);
        return $client;
    }

    /**
     * {@inheritDoc}
     */
    protected function doSend(SentMessage $message): void
    {
        $email = MessageConverter::toEmail($message->getOriginalMessage());
        foreach ($email->getFrom() as $sender) {
            $service = new \Google_Service_Gmail($this->createGoogleClient($sender->getAddress()));
            $message = new \Google_Service_Gmail_Message();
            $message->setRaw(base64_encode($email->toString()));
            $service->users_messages->send($sender->getAddress(), $message);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function __toString(): string
    {
        return 'google-workspace';
    }
}
