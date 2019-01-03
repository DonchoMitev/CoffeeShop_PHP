<?php

namespace CoffeeShopBundle\Controller\Admin;

use CoffeeShopBundle\Entity\Category;
use CoffeeShopBundle\Form\CategoryType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
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
     * @Route("/all", name="all_categories")
     * @param Request $request
     * @return Response
     */
    public function allCategoriesAction(Request $request)
    {
        $pager  = $this->get('knp_paginator');
        $categories = $pager->paginate(
            $this->getDoctrine()->getRepository(Category::class)
                ->findByQueryBuilder()->orderBy("product_category.name", "asc"),
            $request->query->getInt('page', 1),
            10
        );
        return $this->render("admin/categories/all_categories.html.twig", [
            "categories" => $categories
        ]);
    }
    /**
     * @Route("/add", name="add_category")
     * @param Request $request
     * @return Response
     */
    public function addCategoryAction(Request $request)
    {
        $form = $this->createForm(CategoryType::class);
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
        return $this->render("admin/categories/add_category.html.twig", [
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/categories/edit/{id}", name="edit_category")
     * @param Request $request
     * @param Category $category
     * @return Response
     */
    public function editCategoryAction(Request $request, Category $category)
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Category $category */
            $category = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();
            $this->addFlash("success", "Category {$category->getName()} was updated!");
            return $this->redirectToRoute("all_categories");
        }
        return $this->render("admin/categories/edit_category.html.twig", [
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/categories/delete/{id}", name="delete_category")
     * @Method("POST")
     *
     * @param Request $request
     * @param Category $category
     * @return Response
     */
    public function deleteCategoryAction(Request $request, Category $category)
    {
        /** @var Category $category */
        if ($category->getProducts()->count() > 0) {
            $this->addFlash("danger", "Category with products in it cannot be deleted.");
            return $this->redirectToRoute("all_categories");
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($category);
        $em->flush();
        $this->addFlash("success", "Category {$category->getName()} was deleted!");
        return $this->redirectToRoute("all_categories");
    }
}
