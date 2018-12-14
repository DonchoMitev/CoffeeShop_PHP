<?php

namespace CoffeeShopBundle\Controller\Admin;

use CoffeeShopBundle\Entity\Product;
use CoffeeShopBundle\Form\ProductType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ProductController
 * @Route("admin/product")
 * @IsGranted("ROLE_ADMIN")
 */
class ProductController extends Controller
{
    /**
     * @Route("/create", name="product_create")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute("homepage");
        }


        return $this->render('admin/create_product.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/all_products", name="all_products")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewAllProducts()
    {
        $products = $this->getDoctrine()->getRepository(Product::class)->findAll();

        return $this->render('products/all_products.html.twig', ['products' => $products]);
    }
}
