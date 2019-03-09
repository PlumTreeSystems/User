<?php
/**
 * Created by PhpStorm.
 * User: matas
 * Date: 2017-01-16
 * Time: 23:49
 */

namespace PlumTreeSystems\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

class SecurityUserController extends AbstractUserController
{
    public function loginAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('@PlumTreeSystemsUserBundle/security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    public function logoutAction()
    {
    }
}
