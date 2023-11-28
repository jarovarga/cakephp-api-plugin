<?php

declare(strict_types=1);

namespace Api\Controller\V1;

use Api\Controller\AppController;
use Authentication\Controller\Component\AuthenticationComponent;
use Cake\Core\Plugin;
use Cake\Event\EventInterface;
use Cake\Http\Response;
use Firebase\JWT\JWT;

/**
 * @property AuthenticationComponent|null Authentication
 */
class AuthController extends AppController
{
    /**
     * @param EventInterface $event
     * @return void
     */
    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);

        $this->Authentication->addUnauthenticatedActions(['handshake']);
    }

    /**
     * @return Response
     */
    public function handshake(): Response
    {
        $output = ['message' => 'I\'m ready!'];

        return $this->response
            ->withStringBody(json_encode($output));
    }

    /**
     * @return Response|null
     */
    public function token(): ?Response
    {
        if ($this->Authentication->getResult()->isValid()) {
            $now = time();
            $ttl = 3600;
            $payload = [
                'iss' => 'localhost',
                'sub' => $this->Authentication->getIdentityData('id'),
                'exp' => $now + $ttl,
            ];
            $key = file_get_contents(Plugin::configPath('Api') . 'jwt.key');
            $output = [
                'issued_at' => $now,
                'access_token' => JWT::encode($payload, $key, 'RS256'),
                'token_type' => 'Bearer',
                'expires_in' => $ttl,
            ];

            return $this->response
                ->withStringBody(json_encode($output));
        }

        return null;
    }
}
