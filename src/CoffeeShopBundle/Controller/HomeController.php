<?php

namespace CoffeeShopBundle\Controller;

use CoffeeShopBundle\Entity\Category;
use CoffeeShopBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)
            ->getNonEmptyCategories();
        $products = $this->getDoctrine()->getRepository(Product::class)->selectNewProduct();
        return $this->render('default/index.html.twig', ['products' => $products, "categories" => $categories]);
    }

    /**
     * @Route("/about", name="about")
     */
    public function aboutAction()
    {
        return $this->render('default/about.html.twig');
    }

    /**
     * @Route("/contacts", name="contacts")
     */
    public function contactAction()
    {
        return $this->render('default/contacts.html.twig');
    }

    public function categoryMenuAction()
    {

        $categories = $this->getDoctrine()->getRepository(Category::class)
            ->getNonEmptyCategories();
        var_dump($categories);
        return $this->render("_categories_menu.html.twig", [
            "categories" => $categories
        ]);
    }
}
