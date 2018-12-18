<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 12/18/2018
 * Time: 5:04 PM
 */

namespace CoffeeShopBundle\Services;


use CoffeeShopBundle\Entity\OrderProducts;
use CoffeeShopBundle\Entity\User;

class OrderService implements OrderServiceInterface
{

    public function createOrder(
        User $user,
        \DateTime $date,
        array $products,
        $total)
    {
        $order = new OrderProducts();
        $order->setUser($user);
        $order->setDate($date);
        $order->setProducts($products);
        $order->setTotal($total);
//        $order->setVerified(false);
        return $order;
    }
}