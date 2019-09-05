<?php

namespace App\Controller;

use App\Entity\Meet;
use App\Entity\MenuAccess;
use App\Entity\Role;
use App\Entity\Users;
use App\Form\ProfilType;
use App\Form\RegistrationType;
use App\Form\RoleAddType;
use App\Repository\MeetRepository;
use App\Repository\MenuAccessRepository;
use App\Repository\RoleRepository;
use App\Repository\UsersRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use TwitterPhp\Connection\User;


class AccountController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     * @param AuthenticationUtils $utils
     * @return Response
     */
    public function login(AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();

        return $this->render('account/login.html.twig', [
            'hasError' => $error !== null,
            'username' => $username
        ]);
    }


    /**
     * déconnexion
     *
     * @Route("/logout", name="logout")
     *
     */
    public function logout()
    {

    }

    /**
     *
     * @Route("/register" , name="register")
     */
    public function register(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder)
    {
        $user = new Users();
        $form = $this->createForm(RegistrationType::class, $user, [
            'validation_groups' => ['Default', 'create']
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $pwd = $encoder->encodePassword($user, $user->getPwd());
            $baseRole = $this->getDoctrine()->getManager()->getRepository(Role::class)->findOneBy(['title'=>'Civil']);
            $user->addUserRole($baseRole);
            $user->setPwd($pwd);
            $manager->persist($user);
            $manager->flush();
            $this->addFlash(
                'success',
                'Demande de compte effectuée, en attente d approbation'
            );
            return $this->redirectToRoute('homepage');
        }
        return $this->render('account/register.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/profil", name="profil")
     * 
     */
    public function profil(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder)
    {
        try {
            $currentRoles = $this->getUser()->getRoles();
        } catch (\Throwable $th) {
            return $this->redirectToRoute('homepage');
        }
        $user = $this->getUser();
        $form = $this->createForm(ProfilType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pwd = $encoder->encodePassword($user, $user->getPwd());
            $user->setPwd($pwd);
            $manager->persist($user);
            $manager->flush();
            $this->addFlash(
                'success',
                'Compte modifiée avec succes'
            );
            return $this->redirectToRoute('profil');
        }

        return $this->render('account/profil.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet la gestion des user par le patrion
     * @Route("/userControl", name="userAccess")
     * @param Users $users
     * @return Response
     */
    public function userAccesss(UsersRepository $repository)
    {
        try {
            $currentRole = $this->getUser()->getRoles();
        } catch (\Throwable $th) {
            return $this->redirectToRoute('homepage');
        }
        if (!in_array('Patron', $currentRole)) {
            return $this->redirectToRoute('homepage');
        }
        $userList = $repository->findCivil('Civil');
        return $this->render('/account/userAccess.html.twig', [
            'usersList' => $userList
        ]);
    }

    /**
     * @Route("/grantAccess/{id}", name="grantAccess")
     */
    public function grantAccess(Users $user, ObjectManager $manager, RoleRepository $roleRepository)
    {
        try {
            $currentRole = $this->getUser()->getRoles();
        } catch (\Throwable $th) {
            return $this->redirectToRoute('homepage');
        }
        if (!in_array('Patron', $currentRole)) {
            return $this->redirectToRoute('homepage');
        }

        $lowRole = $this->getDoctrine()->getManager()->getRepository(Role::class)->findOneBy(['title'=>'employe']);
        $oldRole = $this->getDoctrine()->getManager()->getRepository(Role::class)->findOneBy(['title'=>'Civil']);
        $user->addUserRole($lowRole);
        $user->removeUserRole($oldRole);
        $manager->persist($user);
        $manager->flush();
        $this->addFlash(
            'success',
            'Acces ajouter au compte'
        );
        return $this->redirectToRoute('userAccess');
    }

    /**
     * @Route("/delAccess/{id}", name="delAccess")
     */
    public function delAccess(Users $user, ObjectManager $manager)
    {
        try {
            $currentRole = $this->getUser()->getRoles();
        } catch (\Throwable $th) {
            return $this->redirectToRoute('homepage');
        }
        if (!in_array('Patron', $currentRole)) {
            return $this->redirectToRoute('homepage');
        }

        $manager->remove($user);
        $manager->flush();
        $this->addFlash(
            'success',
            'Demande de compte supprimer'
        );
        return $this->redirectToRoute('userAccess');
    }

    /**
     * @Route("/gradeControl", name="gradeAccess")
     * @param RoleRepository $repo
     * @param Request $request
     * @param ObjectManager $manager
     * @return Response
     */
    public function gradeAccess(Request $request, ObjectManager $manager, RoleRepository $repo): Response
    {
        try {
            $currentRole = $this->getUser()->getRoles();
        } catch (\Throwable $th) {
            return $this->redirectToRoute('homepage');
        }
        if (!in_array('Patron', $currentRole)) {
            return $this->redirectToRoute('homepage');
        }

        $menuAccess = $repo->findPatron();
        $grade = new Role();
        $form = $this->createForm(RoleAddType::class, $grade);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($grade);
            $manager->flush();
            $this->addFlash(
                'success',
                'Grade bien crée'
            );
            $this->redirectToRoute('gradeAccess');
        }
        $gradeList = $repo->findPublicRole();
        return $this->render('/account/gradeAccess.html.twig', [
            'gradeList' => $gradeList,
            'form' => $form->createView(),
            'menuAccess' => $menuAccess
        ]);
    }

    /**
     * @Route("/addRangAccess/{id}", name="addRangAccess")
     * @param Role $role
     * @param ObjectManager $manager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addRangAccess(Role $role, ObjectManager $manager)
    {
        try {
            $currentRole = $this->getUser()->getRoles();
        } catch (\Throwable $th) {
            return $this->redirectToRoute('homepage');
        }
        if (!in_array('Patron', $currentRole)) {
            return $this->redirectToRoute('homepage');
        }

        $rangPatron = $this->getDoctrine()->getManager()->getRepository(MenuAccess::class)->findOneBy(['Route'=>'Patron']);
        $role->setMenuAccess($rangPatron);
        $manager->persist($role);
        $manager->flush();
        return $this->redirectToRoute('gradeAccess');
    }

    /**
     * @Route("/removeRangAccess/{id}", name="removeRangAccess")
     * @param Role $role
     * @param ObjectManager $manager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     */
    public function removeRangAccess(Role $role, ObjectManager $manager)
    {
        try {
            $currentRole = $this->getUser()->getRoles();
        } catch (\Throwable $th) {
            return $this->redirectToRoute('homepage');
        }
        if (!in_array('Patron', $currentRole)) {
            return $this->redirectToRoute('homepage');
        }

        $rangPatron = $this->getDoctrine()->getManager()->getRepository(MenuAccess::class)->findOneBy(['Route'=>'Patron']);
        $role->setMenuAccess(null);
        $manager->persist($role);
        $manager->flush();
        return $this->redirectToRoute('gradeAccess');
    }

    /**
     * @Route("/upRang/{id}", name="upRang")
     */
    public function upRang(Role $role, ObjectManager $manager)
    {
        try {
            $currentRole = $this->getUser()->getRoles();
        } catch (\Throwable $th) {
            return $this->redirectToRoute('homepage');
        }
        if (!in_array('Patron', $currentRole)) {
            return $this->redirectToRoute('homepage');
        }

        $oldRang = $role->getRang();
        $newRang = $oldRang + 1;
        $role->setRang($newRang);
        $manager->persist($role);
        $manager->flush();
        return $this->redirectToRoute('gradeAccess');
    }

    /**
     * @Route("/downRang/{id}", name="downRang")
     * @param Role $role
     * @param ObjectManager $manager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function downRang(Role $role, ObjectManager $manager)
    {
        try {
            $currentRole = $this->getUser()->getRoles();
        } catch (\Throwable $th) {
            return $this->redirectToRoute('homepage');
        }
        if (!in_array('Patron', $currentRole)) {
            return $this->redirectToRoute('homepage');
        }

        $oldRang = $role->getRang();
        $newRang = $oldRang - 1;
        $role->setRang($newRang);
        $manager->persist($role);
        $manager->flush();
        return $this->redirectToRoute('gradeAccess');
    }

    /**
     * @Route("/delRang/{id}", name="delRang")
     * @param Role $role
     * @param ObjectManager $manager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delRang(Role $role, ObjectManager $manager)
    {
        try {
            $currentRole = $this->getUser()->getRoles();
        } catch (\Throwable $th) {
            return $this->redirectToRoute('homepage');
        }
        if (!in_array('Patron', $currentRole)) {
            return $this->redirectToRoute('homepage');
        }

        if ($role->getTitle() == 'Patron') {
            $this->addFlash('danger', 'Impossible de supprimer le role');
            return $this->redirectToRoute('gradeAccess');
        }
        $manager->remove($role);
        $manager->flush();
        return $this->redirectToRoute('gradeAccess');
    }

    /**
     * @Route("/meetList", name="meetList")
     * @param MeetRepository $repo
     * @return Response
     */
    public function meetList(MeetRepository $repo)
    {
        try {
            $currentRole = $this->getUser()->getRoles();
        } catch (\Throwable $th) {
            return $this->redirectToRoute('homepage');
        }
        if (!in_array('Employe', $currentRole)) {
            return $this->redirectToRoute('homepage');
        }

        $meelList = $repo->findAll();
        return $this->render('/account/meetList.html.twig', [
            'meetList' => $meelList
        ]);
    }

    /**
     * @Route("/delMeet/{id}", name="delMeet")
     * @param Meet $meet
     * @param ObjectManager $manager
     */
    public function delMeet(Meet $meet, ObjectManager $manager)
    {
        try {
            $currentRole = $this->getUser()->getRoles();
        } catch (\Throwable $th) {
            return $this->redirectToRoute('homepage');
        }
        if (!in_array('Employe', $currentRole)) {
            return $this->redirectToRoute('homepage');
        }

        $manager->remove($meet);
        $manager->flush();
        return $this->redirectToRoute('meetList');
    }

    /**
     * @Route("/listUser", name="listUser")
     * @param UsersRepository $repo
     * @return Response
     */
    public function listUser(UsersRepository $repo, RoleRepository $roleRepository)
    {
        try {
            $currentRole = $this->getUser()->getRoles();
        } catch (\Throwable $th) {
            return $this->redirectToRoute('homepage');
        }
        if (!in_array('Employe', $currentRole)) {
            return $this->redirectToRoute('homepage');
        }

        $roleList = $roleRepository->findPublicRole();
        $users = $repo->findCivil('Employe');
        return $this->render('account/listUser.html.twig', [
            'usersList' => $users,
            'roleList' => $roleList
        ]);
    }

    /**
     * @Route("/addRoleUser/{id}", name="addRoleUser")
     * @param Users $users
     * @param Request $request
     * @param ObjectManager $manager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addRoleUser(Users $users, Request $request, ObjectManager $manager)
    {
        try {
            $currentRole = $this->getUser()->getRoles();
        } catch (\Throwable $th) {
            return $this->redirectToRoute('homepage');
        }
        if (!in_array('Patron', $currentRole)) {
            return $this->redirectToRoute('homepage');
        }

        $getParam = $request->query->get('title');
        if ( $getParam == 'Employe' or $getParam == 'Civil') {
            $this->addFlash(
                'warning',
                'Problème dans le role ciblé'
            );
            return $this->redirectToRoute('listUser');
        }
        $roleCible = $this->getDoctrine()->getManager()->getRepository(Role::class)->findOneBy(['title' => $getParam]);
        $users->addUserRole($roleCible);
        $manager->persist($users);
        $manager->flush();
        return $this->redirectToRoute('listUser');
    }

    /**
     * @Route("/removeRoleUser/{id}", name="removeRoleUser")
     * @param Users $user
     * @param Role $role
     * @param ObjectManager $manager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|void
     */
    public function removeRoleUser(Users $user, Request $request, ObjectManager $manager)
    {
        try {
            $currentRole = $this->getUser()->getRoles();
        } catch (\Throwable $th) {
            return $this->redirectToRoute('homepage');
        }
        if (!in_array('Patron', $currentRole)) {
            return $this->redirectToRoute('homepage');
        }

        $getParam = $request->query->get('title');
        if ( $getParam == 'Employe' or $getParam == 'Civil') {
            $this->addFlash(
                'warning',
                'Problème dans le role ciblé'
            );
            return $this->redirectToRoute('listUser');
        }
        $roleCible = $this->getDoctrine()->getManager()->getRepository(Role::class)->findOneBy(['title' => $getParam]);
        $user->removeUserRole($roleCible);
        $manager->persist($user);
        $manager->flush();
        return $this->redirectToRoute('listUser');

    }

    /**
     * @Route("/removeUser/{id}", name="removeUser")
     * @param Users $user
     * @param ObjectManager $manager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeUser(Users $user, ObjectManager $manager)
    {
        try {
            $currentRole = $this->getUser()->getRoles();
        } catch (\Throwable $th) {
            return $this->redirectToRoute('homepage');
        }
        if (!in_array('Patron', $currentRole)) {
            return $this->redirectToRoute('homepage');
        }

        $manager->remove($user);
        $manager->flush();
        return $this->redirectToRoute('listUser');
    }
}

