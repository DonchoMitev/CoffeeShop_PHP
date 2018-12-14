<?php

namespace CoffeeShopBundle\Controller\Admin;

use CoffeeShopBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class AdminController
 * @Route("/admin")
 * @Security("is_granted('ROLE_ADMIN')")
 */
class AdminController extends Controller
{
    /**
     * @Route("/all_users", name="all_users")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewAllUsersAction()
    {
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();

        return $this->render('admin/all_users.html.twig', ['users' => $users]);
    }

    /**
     * @Route("/user/{id}", name="one_user")
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewOneUser($id) {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        return $this->render("admin/user.html.twig", ['user' => $user]);
    }


}
