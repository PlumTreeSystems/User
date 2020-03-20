<?php
/**
 * Created by PhpStorm.
 * User: marius
 * Date: 2018-05-04
 * Time: 14:42
 */

namespace PlumTreeSystems\UserBundle\Controller;

use PlumTreeSystems\UserBundle\Entity\User;
use PlumTreeSystems\UserBundle\Model\TokenizeableInterface;
use PlumTreeSystems\UserBundle\Service\FormErrorExtractor;
use PlumTreeSystems\UserBundle\Service\JWTManager;
use PlumTreeSystems\UserBundle\Service\UserManager;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class TokenSecurityUserController extends AbstractUserController
{
    public function createTokenAction(
        Request $request,
        UserPasswordEncoderInterface $encoder,
        JWTManager $jwtManager,
        UserManager $userManager
    ) {

        $form = $this->createFormBuilder()
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('password', TextType::class, [
                'constraints' => [
                    new NotBlank()
                ]
            ])->getForm();
        $form->submit($request->request->all());
        if ($form->isValid()) {
            $email = $form->get('email')->getData();
            /** @var User $user */
            $user = $userManager->loadUserByUsername($email);
            if ($user) {
                try {
                    $this->preLogin($user);
                } catch (HttpException $exception) {
                    return new JsonResponse($exception->getMessage(), $exception->getStatusCode(), [], true);
                }
            }
            $pass = $form->get('password')->getData();
            if ($user && $encoder->isPasswordValid($user, $pass)) {
                /**
                 * @var $user TokenizeableInterface
                 */
                $token = $jwtManager->createToken($user);
                $response = [
                    'token' => $token,
                ];
                $this->passLogin($user);
                return new JsonResponse($response, JsonResponse::HTTP_OK);
            }
            $form->addError(new FormError('Incorrect email or password'));
            if ($user) {
                $this->failLogin($user);
            }
        }
        return new JsonResponse(FormErrorExtractor::getErrorMessages($form), 400);
    }
}
