<?php
declare(strict_types=1);

namespace MobilityWork\Service;

/**
 * Cette classe est un serivce (défini dans mon container applicatif) et injecter dans ZendeskService
 */
class ZendeskAPI implements ZendeskAPIInterface
{
    public function __construct(array $config)
    {
        $this->client = new ZendeskAPI($config['subdomain']);
        $this->client->setAuth(
            'basic',
            [
                'username' => $config['username'],
                'token' => $this->getServiceManager()->get('Config')['zendesk']['token'],
            ]
        );
    }

    public function createUser(array $user): string
    {
        $response = $this->client->users()->createOrUpdate($user);

        return $response->user->id;
    }

    /**
     * @throws \Exception
     */
    public function createTicket(array $ticket): array
    {
        $this->client->tickets()->create($ticket);
    }
}