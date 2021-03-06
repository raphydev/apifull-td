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

    /**
     * @param AuthenticationSuccessEvent $event
     */
    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event)
    {
        $data = $event->getData();
        $user = $event->getUser();

        if ( !$user instanceof UserInterface) {
            return;
        }

        $data['payload'] = [
            'username'  => $user->getUsername(),
            'roles'     => $user->getRoles(),
            'statusKey' => 'authenticated',
            'status'    => true
        ];
        $event->setData($data);
    }
}