<?php

namespace CoffeeShopBundle\Controller;

use CoffeeShopBundle\Entity\User;
use CoffeeShopBundle\Services\CartServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CartController
 * @package CoffeeShopBundle\Controller
 * @Route("/cart")
 */
class CartController extends Controller
{
    /**
     * @var CartServiceInterface
     */
    private $cartService;

    public function __construct(CartServiceInterface $cartService)
    {
        $this->cartService = $cartService;
    }

    /**
     * @Route("/", name="view_cart")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function cartAction() {
        /** @var User $user */
        $user = $this->getUser();
        $this->cartService->getProductsTotal($user->getProducts());

        return $this->render("/cart/index.html.twig", [
            "cart" => $user->getProducts(),
            "total" => $this->cartService->getProductsTotal($user->getProducts())
        ]);
    }
}
