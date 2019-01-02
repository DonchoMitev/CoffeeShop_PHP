<?php

namespace CoffeeShopBundle\Controller;

use CoffeeShopBundle\Entity\Role;
use CoffeeShopBundle\Entity\User;
use CoffeeShopBundle\Form\ProfileEditType;
use CoffeeShopBundle\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    /**
     * @Route("/register", name="user_register")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function registerAction(Request $request) {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPlainPassword());

            $role = $this
                ->getDoctrine()
                ->getRepository(Role::class)
                ->findOneBy(['name' => 'ROLE_USER']);
            $user->addRole($role);
            $user->setPassword($password);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash(
                'success',
                'You succsesfully registred, you can loggin now :)'
            );

            return $this->redirectToRoute("security_login");
        }

        return $this->render("user/register.html.twig", ['form' => $form->createView()]);

    }

    /**
     * @Route("/profile", name="user_profile")
     */
    public function profile(){
        $userId = $this->getUser()->getId();
        $user = $this
            ->getDoctrine()
            ->getRepository(User::class)
            ->find($userId);
        return $this->render("user/profile.html.twig",
            ['user' => $user]);
    }

    /**
     * @Route("/profile/edit", name="user_profile_edit")
     * @Security(expression="is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @param Request $request
     * @return Response
     */
    public function editProfileAction(Request $request)
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();
        $form = $this->createForm(ProfileEditType::class, $currentUser);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($currentUser);
            $em->flush();
            $this->addFlash("success", "Profile updated!");
            return $this->redirectToRoute("user_profile");
        }
        return $this->render("/user/edit.html.twig", [
            "edit_form" => $form->createView()
        ]);
    }
}
