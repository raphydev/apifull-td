<?php
/**
 * Created by IntelliJ IDEA.
 * User: raphael
 * Date: 07/10/2017
 * Time: 14:23
 */

namespace Labs\ApiBundle\Security\EventListener;


use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\Security\Core\User\UserInterface;

class AuthenticationSuccessListener
{
    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event)
    {
        $data = $event->getData();
        $user = $event->getUser();

        if ( !$user instanceof UserInterface) {
           return;
        }

        $data['data'] = [
            'user' => $user->getUsername(),
            'roles' => $user->getRoles(),
            'status' => 'authenticated'
        ];
        $event->setData($data);
    }
}