<?php

namespace App\Controller;

use App\Entity\Order;
use App\Form\OrderType;
use App\Repository\OrderRepository;
use App\Repository\UserRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


/**
 * @Route("/order")
 */
class OrderController extends AbstractController
{

    public function __construct(TokenStorageInterface $tokenStorageInterface, JWTTokenManagerInterface $jwtManager)
    {
        $this->jwtManager = $jwtManager;
        $this->tokenStorageInterface = $tokenStorageInterface;
        $this->jwt = $this->jwtManager->decode($this->tokenStorageInterface->getToken());
    }

    /**
     * @Route("/all", name="order_index", methods={"GET"})
     */
    public function index(Request $request, UserRepository $userRepository, OrderRepository $orderRepository): Response
    {
        $user = $userRepository->findOneBy([
            "username" => $this->jwt["username"]
        ]);

        $orders = $orderRepository->findBy([
            "user" => $user->getId()
        ]);

        return new JsonResponse(["message" => "success", "orders" => $orders]);
    }

    /**
     * @Route("/new", name="order_new", methods={"GET","POST"})
     */
    public function new(Request $request, UserRepository $userRepository, ProductRepository $productRepository): Response
    {
        $user = $userRepository->findOneBy([
            "username" => $this->jwt["username"]
        ]);

        $product = $productRepository->findOneBy([
            "id" => $request->get("productid")
        ]);

        $format = 'Y-m-d H:i:s';
        $date = \DateTime::createFromFormat($format, $request->get("shippingDate"));

        $order = new Order();
        $order->setOrderCode($request->get("orderCode"));
        $order->setQuantity($request->get("quantity"));
        $order->setAddress($request->get("address"));
        $order->setShippingDate($date);
        $order->setUser($user);
        $order->setProduct($product);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($order);
        $entityManager->flush();

        return new JsonResponse(["message" => "success"]);
    }

    /**
     * @Route("/{id}", name="order_show", methods={"GET"})
     */
    public function show(UserRepository $userRepository, OrderRepository $orderRepository, Int $id): Response
    {
        $user = $userRepository->findOneBy([
            "username" => $this->jwt["username"]
        ]);

        $order = $orderRepository->findOneBy([
            "id" => $id,
            "user" => $user->getId(),
        ]);

        return new JsonResponse(["message" => "success", "order" => $order]);
    }

    /**
     * @Route("/{id}/edit", name="order_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, UserRepository $userRepository, OrderRepository $orderRepository, ProductRepository $productRepository, Int $id): Response
    {
        $user = $userRepository->findOneBy([
            "username" => $this->jwt["username"]
        ]);

        $order = $orderRepository->findOneBy([
            "id" => $id,
            "user" => $user->getId(),
        ]);

        $product = $productRepository->findOneBy([
            "id" => $request->get("productid")
        ]);

        $format = 'Y-m-d H:i:s';
        $currentShippingDate = $order->getShippingDate();
        $now = \DateTime::createFromFormat($format, date($format));

        if ($currentShippingDate > $now) return new JsonResponse(["message" => "Your order is already sent!"]);

        $date = \DateTime::createFromFormat($format, $request->get("shippingDate"));

        $order->setOrderCode($request->get("orderCode"));
        $order->setQuantity($request->get("quantity"));
        $order->setAddress($request->get("address"));
        $order->setShippingDate($date);
        $order->setUser($user);
        $order->setProduct($product);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($order);
        $entityManager->flush();

        return new JsonResponse(["message" => "success"]);
    }

}
