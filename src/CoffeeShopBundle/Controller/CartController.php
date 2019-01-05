<?php

namespace CoffeeShopBundle\Controller;

use CoffeeShopBundle\Entity\Product;
use CoffeeShopBundle\Entity\User;
use CoffeeShopBundle\Services\CartServiceInterface;
use CoffeeShopBundle\Services\OrderServiceInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CartController
 * @package CoffeeShopBundle\Controller
 * @Route("/cart")
 *
 * @Security(expression="is_granted('IS_AUTHENTICATED_FULLY')")
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

    /**
     * @Route("/add/{id}", name="add_product_cart")
     * @Method("POST")
     *
     * @param Product $product
     * @return RedirectResponse
     */
    public function addToCartAction(Product $product)
    {
        $cartService = $this->cartService;

        if (!$cartService->addProductToCart($this->getUser(), $product)) {
            return $this->redirectToRoute("view_cart");
        }
        return $this->redirectToRoute("view_cart");
    }

    /**
     * @Route("/delete/{id}", name="remove_product_cart")
     * @Method("POST")
     *
     * @param Product $product
     * @return RedirectResponse
     */
    public function removeFromCartAction(Product $product)
    {
        $cartService = $this->cartService;
        $cartService->removeProductFromCart($this->getUser(), $product);
        return $this->redirectToRoute("view_cart");
    }

    /**
     * @Route("/checkout", name="finish_cart")
     *
     * @return RedirectResponse
     */
    public function checkoutCartAction()
    {
        $cartService = $this->cartService;
        if (!$cartService->checkoutCart($this->getUser())) {
            return $this->redirectToRoute("view_cart");
        }
        return $this->redirectToRoute("all_products");
    }
}
