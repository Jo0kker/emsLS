<?php

namespace App\Controller;

use App\Entity\Clients;
use App\Entity\Intervention;
use App\Form\AddClientType;
use App\Form\AddInterventionType;
use App\Repository\ClientsRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BddController extends AbstractController
{
    /**
     * @Route("/registre", name="bdd_index")
     */
    public function index(ClientsRepository $repoClients)
    {
        $clientList = $repoClients->findAll();

        return $this->render('bdd/index.html.twig', [
            'clientList' => $clientList
        ]);
    }

    /**
     * @Route("/ajoutRegistre", name="addBdd")
     * @param Request $request
     * @param ObjectManager $manager
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addBdd(Request $request, ObjectManager $manager)
    {
        $client = new Clients();
        $form = $this->createForm(AddClientType::class, $client);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($client);
            $manager->flush();
            $this->addFlash(
                'success',
                'Dossier ajouter au registre'
            );
            return $this->redirectToRoute('bdd_index');
        }

        return $this->render('bdd/addBdd.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/show/{id}", name="show_bdd")
     * @param Clients $clients
     * @param Request $request
     * @param ObjectManager $
     */
    public function showBdd(Clients $clients, Request $request, ObjectManager $manager)
    {
        $medic = $this->getUser();
        $inter = new Intervention();
        $form = $this->createForm(AddInterventionType::class, $inter);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $inter
                ->setUser($medic)
                ->setClient($clients);
            if ($inter->getSoinCover() == false) {
                // le client prend en charge la factuer
                if (empty($inter->getPrix())) {
                    $this->addFlash(
                        'warning',
                        'Merci de renseigner le prix'
                    );
                    return $this->redirectToRoute('show_bdd', ['id' => $clients->getId() ]);
                }
            }else{
                $inter->setPrix(0);
                //prise en charge de la facture par la mutuelle
                $interClient = $clients->getNbInter();
                if ($interClient == 0) {
                    //il ne reste plus de charge au client
                    $this->addFlash(
                        'danger',
                        'La mutuelle ne couvre plus d\'intervention pour cette personne'
                    );
                    return $this->redirectToRoute('show_bdd', ['id' => $clients->getId() ]);
                }else{
                    //il lui reste des charges, on en enleve une
                    $clients->setNbInter($interClient - 1);
                }
            }
            $manager->persist($inter);
            $manager->persist($clients);
            $manager->flush();
            return $this->redirectToRoute('show_bdd', ['id' => $clients->getId() ]);
        }
        return $this->render('bdd/showBdd.html.twig', [
            'client' => $clients,
            'form' => $form->createView()
        ]);
    }
}
