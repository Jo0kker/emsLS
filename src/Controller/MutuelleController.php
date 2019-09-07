<?php

namespace App\Controller;

use App\Entity\Mutuelle;
use App\Form\MutuelleType;
use App\Repository\MutuelleRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MutuelleController extends AbstractController
{
    /**
     * @Route("/mutuelle", name="mutuelle")
     */
    public function index(MutuelleRepository $repo)
    {
        $mutuelleList = $repo->findBuyMutuelle();
        return $this->render('mutuelle/index.html.twig', [
            'mutuelleList' => $mutuelleList,
        ]);
    }

    /**
     * @Route("/adminMutuelle", name="adminMutuelle")
     * @param MutuelleRepository $repo
     * @param ObjectManager $manager
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function adminMutuelle(MutuelleRepository $repo, ObjectManager $manager, Request $request)
    {
        try {
            $currentRole = $this->getUser()->getRoles();
        } catch (\Throwable $th) {
            return $this->redirectToRoute('homepage');
        }
        if (!in_array('Patron', $currentRole)) {
            return $this->redirectToRoute('homepage');
        }

        $mutuelleList = $repo->findAll();
        $mutuelle = new Mutuelle();
        $form = $this->createForm(MutuelleType::class, $mutuelle);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($mutuelle);
            $manager->flush();
            return $this->redirectToRoute('adminMutuelle');
        }

        return $this->render('mutuelle/adminMutuelle.html.twig', [
            'form' => $form->createView(),
            'mutuelleList' => $mutuelleList
        ]);
    }


    /**
     * @param Mutuelle $mutuelle
     * @param ObjectManager $manager
     * @Route("/removeMutuelle/{id}", name="removeMutuelle")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeMutuelle(Mutuelle $mutuelle, ObjectManager $manager)
    {
        try {
            $currentRole = $this->getUser()->getRoles();
        } catch (\Throwable $th) {
            return $this->redirectToRoute('homepage');
        }
        if (!in_array('Patron', $currentRole)) {
            return $this->redirectToRoute('homepage');
        }

        $manager->remove($mutuelle);
        $manager->flush();
        return $this->redirectToRoute('adminMutuelle');
    }
}
