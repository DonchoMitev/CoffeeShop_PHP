<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 12/18/2018
 * Time: 11:19 AM
 */

namespace CoffeeShopBundle\Services;


use CoffeeShopBundle\Entity\Product;
use CoffeeShopBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class CartService implements CartServiceInterface
{
    private $entityManager;
    private $session;
    private $orderService;

    public function __construct(
        EntityManagerInterface $entityManager,
        SessionInterface $session,
        OrderServiceInterface $orderService
    )

    {
        $this->entityManager = $entityManager;
        $this->session = $session;
        $this->orderService = $orderService;
    }

    /**
     * @param Product[]|ArrayCollection $products
     * @return float|int
     */
    public function getProductsTotal($products)
    {
        if ($products->count() == 0) {
            return 0.;
        }

        $total = array_sum(
            array_map(function (Product $product){
                return $product->getPrice();
            },$products->toArray())
        );

        return $total;
    }

    /**
     * @param User $user
     * @param Product $product
     * @return bool
     */
    public function addProductToCart(User $user, Product $product)
    {
        if ($user->getProducts()->contains($product)) {
            $this->session->getFlashBag()->add("danger", "Product already exists in your cart.");
            return false;
        }

        $user->getProducts()->add($product);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        $this->session->getFlashBag()->add("success", "Product added to cart.");
        return true;
    }

    /**
     * @param User $user
     * @param Product $product
     */
    public function removeProductFromCart(User $user, Product $product)
    {
        if (!$user->getProducts()->removeElement($product)) {
            $this->session->getFlashBag()->add("danger", "Cart was not updated!");
            return;
        }
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        $this->session->getFlashBag()->add("success", "Product ".$product->getName()." remove from cart!");
    }

    /**
     * @param User $user
     * @return bool
     */
    public function checkoutCart(User $user)
    {
        $cartTotal = $this->getProductsTotal($user->getProducts());
        $cartProducts = $user->getProducts();
        foreach ($cartProducts as $product) {
            if ($product->getQuantity() <= 0) {
                $this->session->getFlashBag()
                    ->add("danger", "Some of the products in your cart are out of stock.");
                return false;
            }
        }

        $productsPlainText = [];
        foreach ($cartProducts as $product) {
            $product->setQuantity($product->getQuantity() - 1);
            $user->getProducts()->removeElement($product);

        }

        $order = $this->orderService->createOrder(
            $user,
            new \DateTime(),
            $productsPlainText,
            $cartTotal);
        $this->entityManager->persist($order);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        $this->session->getFlashBag()->add("success", "Checkout completed! Enjoy your order!");
        return true;
    }
}