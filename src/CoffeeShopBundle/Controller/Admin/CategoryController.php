<?php

namespace CoffeeShopBundle\Controller\Admin;

use CoffeeShopBundle\Entity\Category;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CategoryController
 * @Route("admin/category")
 * @IsGranted("ROLE_ADMIN")
 */
class CategoryController extends Controller
{
    /**
     * @Route("/add", name="add_category")
     * @param Request $request
     * @return Response
     */
    public function addCategoryAction(Request $request)
    {
        $form = $this->createForm(AddEditCategoryForm::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Category $category */
            $category = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            $this->addFlash("success", "Category {$category->getName()} was added!");
            return $this->redirectToRoute("homepage");
        }
        return $this->render("admin/categories/add.html.twig", [
            "form" => $form->createView()
        ]);
    }
}
