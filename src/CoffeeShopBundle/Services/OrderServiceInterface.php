<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 12/18/2018
 * Time: 5:02 PM
 */

namespace CoffeeShopBundle\Services;


use CoffeeShopBundle\Entity\User;

interface OrderServiceInterface
{
    public function createOrder(
        User $user,
        \DateTime $date,
        array $products,
        $total);
}