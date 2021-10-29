<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


/**
 * @Route("/user")
 */
class UserController extends AbstractController
{

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder, TokenStorageInterface $tokenStorageInterface, JWTTokenManagerInterface $jwtManager)
    {
        $this->encoder = $encoder;
        $this->jwtManager = $jwtManager;
        $this->tokenStorageInterface = $tokenStorageInterface;
    }

    /**
     * @Route("/login", name="login", methods={"POST"})
     */
    public function login(Request $request, UserRepository $userRepository): Response
    {
        $username = $request->get("username");
        $password = $request->get("password");

        $user = new User();
        $user->setUsername($username);
        $user = $userRepository->findOneBy([
            "username" => $username
        ]);

        $isValid = $this->encoder->isPasswordValid($user, $password);

        if($isValid){
            return new JsonResponse(['token' => $this->jwtManager->create($user), "message" => "success"]);
        }
        return new JsonResponse(['token' => null, "message" => "failed"]);
    }
}
