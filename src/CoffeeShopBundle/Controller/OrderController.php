<?php

namespace CoffeeShopBundle\Controller;

use CoffeeShopBundle\Entity\OrderProducts;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class OrderController
 * @package WebShopBundle\Controller
 *
 * @Route("/admin/orders")
 * @Security("is_granted('ROLE_ADMIN')")
 */
class OrderController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     *
     * @Route("", name="all_orders")
     */
    public function listOrdersAction(Request $request)
    {
        $pager = $this->get('knp_paginator');
        $orders = $pager->paginate(
            $this->getDoctrine()->getRepository(OrderProducts::class)
                ->findByQueryBuilder()->orderBy("products_order.date", "desc"),
            $request->query->getInt('page', 1),
            5
        );
        return $this->render("admin/orders/all_orders.html.twig", [
            "orders" => $orders
        ]);
    }

}
