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
     * @Route("/all_products", name="all_products")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewAllProducts()
    {
        $products = $this->getDoctrine()->getRepository(Product::class)->findAll();

        return $this->render('/admin/products/all_products.html.twig', ['products' => $products]);
    }

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

            $this->addFlash("success", "Product {$product->getName()} added successfully.");

            return $this->redirectToRoute("all_products");
        }


        return $this->render('admin/products/add_product.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/edit/{id}", name="edit_product")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, $id)
    {
        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->merge($product);
            $em->flush();

            $this->addFlash("success", "Product {$product->getName()} updated successfully.");

            return $this->redirectToRoute("all_products");
        }


        return $this->render('admin/products/edit_product.html.twig', ['form' => $form->createView(), 'article' => $product]);
    }

    /**
     * @Route("/delete/{id}", name="delete_product")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteAction(Request $request, $id)
    {
        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->remove($product);
            $em->flush();

            $this->addFlash("success", "Product {$product->getName()} deleted successfully.");
            return $this->redirectToRoute("all_products");
        }


        return $this->render('admin/products/delete.html.twig', ['form' => $form->createView(), 'article' => $product]);
    }
}
