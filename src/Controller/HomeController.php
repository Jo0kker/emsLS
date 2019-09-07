<?php

namespace App\Controller;

use App\Entity\Meet;
use App\Form\MeetType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     * @param Meet $meet
     * @param Request $request
     * @param ObjectManager $manager
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request, ObjectManager $manager)
    {
        $meet = new Meet();
        $form = $this->createForm(MeetType::class, $meet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $meet->setCreatedAt(new \DateTime());
            $manager->persist($meet);
            $manager->flush();
            $this->addFlash(
                'success',
                'Demande de rendez-vous envoye'
            );
            return $this->redirectToRoute('homepage');
        }

        return $this->render('home/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
