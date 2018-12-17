<?php

namespace CoffeeShopBundle\Controller\Admin;

use CoffeeShopBundle\Entity\User;
use CoffeeShopBundle\Form\UserType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UsersController
 * @Route("/admin/user")
 * @Security("is_granted('ROLE_ADMIN')")
 */
class UsersController extends Controller
{
    /**
     * @Route("/all_users", name="all_users")
     * @Method("GET")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function allUsersAction(Request $request)
    {
        $paginator = $this->get('knp_paginator');
        /** User[] $users */
        $users = $paginator->paginate(
            $this
                ->getDoctrine()
                ->getRepository(User::class)
                ->findByQueryBuilder(),
            $request->query->getInt('page', 1), 6

        );

        return $this->render("/admin/users/all_users.html.twig",
        ["users" => $users]
            );
    }

    /**
     * @Route("/delete/{id}", name="delete_user")
     * @Method("POST")
     * @param User $user
     * @return RedirectResponse
     */
    public function deleteUserAction(User $user)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();
        $this->addFlash("success", "User with email {$user->getEmail()} deleted successfully!");
        return $this->redirectToRoute("all_users");
    }

    /**
     * @Route("/edit/{id}", name="edit_user")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, $id)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->merge($user);
            $em->flush();

            $$this->addFlash("success", "User {$user->getEmail()} updated successfully!");

            return $this->redirectToRoute("all_users");
        }


        return $this->render('admin/users/edit_user.html.twig', ['form' => $form->createView(), 'user' => $user]);
    }


    /**
     * @Route("/user/{id}", name="one_user")
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewOneUser($id) {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        return $this->render("admin/users/user.html.twig", ['user' => $user]);
    }
}
