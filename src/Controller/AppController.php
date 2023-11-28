<?php

declare(strict_types=1);

namespace Api\Controller;

use Cake\Controller\Controller;
use Cake\Event\EventInterface;
use Exception;

class AppController extends Controller
{
    /**
     * @return void
     * @throws Exception
     */
    public function initialize(): void
    {
        parent::initialize();

        $this->loadComponent('Authentication.Authentication');
    }

    /**
     * @param EventInterface $event
     * @return void
     */
    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);

        $this->request->allowMethod(['get', 'post']);
    }
}
