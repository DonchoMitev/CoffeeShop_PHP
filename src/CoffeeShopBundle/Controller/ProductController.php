<?php

namespace CoffeeShopBundle\Controller;

use CoffeeShopBundle\Entity\Category;
use CoffeeShopBundle\Entity\Product;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ProductController
 * @package CoffeeShopBundle\Controller
 *
 *  @Route("/products")
 */
class ProductController extends Controller
{
    /**
     * @Route("", name="all_products")
     * @Method("GET")
     *
     * @param Request $request
     *
     *
     * @return Response
     */
    public function listAction(Request $request)
    {
        $paginator = $this->get('knp_paginator');
        /** @var ArrayCollection|Product[] $products */
        $products = $paginator->paginate(
            $this->getDoctrine()->getRepository(Product::class)
                ->findByQueryBuilder()->orderBy("product.id", "desc"),
            $request->query->getInt('page', 1),
            6
        );
        return $this->render("/products/list.html.twig", [
            "products" => $products
        ]);
    }

    /**
     * @Route("/category/{category}", name="products_by_category")
     * @Method("GET")
     *
     * @param Request $request
     *
     * @param $category
     * @return Response
     */
    public function listCategoryAction(Request $request, $category)
    {
        $id = 1;
        if ($category == "Coffee Beans"){
            $id = 2;
        }
        $pager = $this->get('knp_paginator');
        /** @var ArrayCollection|Product[] $products */
        $products = $pager->paginate(
            $this->getDoctrine()->getRepository(Product::class)
                ->findAllByCategoryQueryBuilder($id),
            $request->query->getInt('page', 1),
            6
        );
        return $this->render("/products/list_by_category.html.twig", [
            "products" => $products,
            "category" => $category
        ]);
    }
    /**
     * @Route("/view/{id}", name="view_product")
     *
     * @param Product $product
     * @return Response
     */
    public function viewProductAction(Product $product)
    {

        return $this->render("/products/one_product.html.twig", [
            "product" => $product

        ]);
    }

}
