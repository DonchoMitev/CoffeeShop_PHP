<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 12/18/2018
 * Time: 11:15 AM
 */

namespace CoffeeShopBundle\Services;


use CoffeeShopBundle\Entity\Product;
use CoffeeShopBundle\Entity\User;

interface CartServiceInterface
{
    public function getProductsTotal($products);

    public function addProductToCart(User $user, Product $product);

    public function removeProductFromCart(User $user, Product $product);

    public function checkoutCart(User $user);
}