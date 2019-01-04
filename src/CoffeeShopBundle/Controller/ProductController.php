<?php

namespace CoffeeShopBundle\Controller;

use CoffeeShopBundle\Entity\Category;
use CoffeeShopBundle\Entity\Product;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
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
     * @Route("/category/{cat}", name="products_by_category")
     * @Method("GET")
     *
     * @param Request $request
     *
     * @param $cat
     * @return Response
     */
    public function listCategoryAction(Request $request, $cat)
    {   var_dump($cat);
        $pager = $this->get('knp_paginator');
        /** @var ArrayCollection|Product[] $products */
        $products = $pager->paginate(
            $this->getDoctrine()->getRepository(Product::class)
                ->findAllByCategoryQueryBuilder($cat)
                ->orderBy("product.id", "desc"),
            $request->query->getInt('page', 1),
            6
        );
        return $this->render("/products/list_by_category.html.twig", [
            "products" => $products
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
            "product" => $product,
        ]);
    }
//    /**
//     * @Route("/{id}/review", name="products_add_review")
//     * @Security(expression="is_granted('IS_AUTHENTICATED_FULLY')")
//     *
//     * @param Product $product
//     * @param Request $request
//     * @return Response
//     */
//    public function addReviewAction(Product $product, Request $request)
//    {
//        $form = $this->createForm(ReviewForm::class);
//        $form->handleRequest($request);
//        if ($form->isSubmitted() && $form->isValid()) {
//            /** @var Review $review */
//            $review = $form->getData();
//            $review->setDate(new \DateTime());
//            $review->setUser($this->getUser());
//            $review->setProduct($product);
//            $em = $this->getDoctrine()->getManager();
//            $em->persist($review);
//            $em->flush();
//            $this->addFlash("success", "Review added!");
//            return $this->redirectToRoute("products_view_product", ["slug" => $product->getSlug()]);
//        }
//        return $this->render("@WebShop/products/add_review.html.twig", [
//            "add_form" => $form->createView()
//        ]);
//    }
}
