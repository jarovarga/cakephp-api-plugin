<?php

declare(strict_types=1);

namespace Api;

use Authentication\AuthenticationService;
use Authentication\AuthenticationServiceInterface;
use Authentication\AuthenticationServiceProviderInterface;
use Authentication\Middleware\AuthenticationMiddleware;
use Cake\Core\BasePlugin;
use Cake\Http\MiddlewareQueue;
use Psr\Http\Message\ServerRequestInterface;

class Plugin extends BasePlugin implements AuthenticationServiceProviderInterface
{
    /**
     * @param MiddlewareQueue $middlewareQueue
     * @return MiddlewareQueue
     */
    public function middleware(MiddlewareQueue $middlewareQueue): MiddlewareQueue
    {
        parent::middleware($middlewareQueue);

        $middlewareQueue->add(new AuthenticationMiddleware($this));

        return $middlewareQueue;
    }

    /**
     * @param ServerRequestInterface $request
     * @return AuthenticationServiceInterface
     */
    public function getAuthenticationService(ServerRequestInterface $request): AuthenticationServiceInterface
    {
        if (str_starts_with($request->getUri()->getPath(), '/api')) {
            if ($request->getMethod() === 'options') {
                die;
            }

            $service = new AuthenticationService();

            $service->loadAuthenticator('Authentication.Jwt', [
                'secretKey' => file_get_contents($this->getConfigPath() . 'jwt.pem'),
                'algorithm' => 'RS256',
                'tokenPrefix' => 'Bearer',
                'returnPayload' => false,
            ]);
            $service->loadIdentifier('Authentication.JwtSubject');
            $service->loadAuthenticator('Authentication.HttpBasic');
            $service->loadIdentifier('Authentication.Password');

            return $service;
        }

        return $request->getAttribute('authentication');
    }
}
