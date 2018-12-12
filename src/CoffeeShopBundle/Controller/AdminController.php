<?php

namespace CoffeeShopBundle\Controller;

use CoffeeShopBundle\Entity\Product;
use CoffeeShopBundle\Entity\User;
use CoffeeShopBundle\Form\ProductType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends Controller
{
    /**
     * @Route("/admin/all_users", name="all_users")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewAllUsersAction()
    {
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();

        return $this->render('admin/all_users.html.twig', ['users' => $users]);
    }

    /**
     * @Route("/admin/user/{id}", name="one_user")
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewOneUser($id) {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        return $this->render("admin/user.html.twig", ['user' => $user]);
    }

    /**
     * @Route("/admin/product/create", name="product_create")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        var_dump($product);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute("homepage");
        }


        return $this->render('admin/create_product.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/admin/all_products", name="all_products")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewAllUsersProducts()
    {
        $products = $this->getDoctrine()->getRepository(Product::class)->findAll();

        return $this->render('products/all_products.html.twig', ['products' => $products]);
    }
}
