<?php

namespace App\Controller;

use App\Entity\Clients;
use App\Entity\Mutuelle;
use App\Form\AddClientType;
use App\Entity\Intervention;
use App\Form\AddInterventionType;
use App\Repository\ClientsRepository;
use App\Repository\MutuelleRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BddController extends AbstractController
{
    /**
     * @Route("/registre", name="bdd_index")
     * @param ClientsRepository $repoClients
     * @return RedirectResponse|Response
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
     * @return Response
     */
    public function addBdd(Request $request, ObjectManager $manager): Response
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
     * @Route("/delRegistre/{id}", name="delBdd")
     * @param Clients $clients
     * @param ObjectManager $manager
     * @return RedirectResponse
     */
    public function delBdd(Clients $clients, ObjectManager $manager)
    {
        $inter = $this->getDoctrine()->getManager()->getRepository(Intervention::class)->findBy(['client' => $clients->getId()]);
        foreach ($inter as $int) {
        $clients->removeIntervention($int);
        }
        $manager->remove($clients);
        $manager->flush();
        return $this->redirectToRoute('bdd_index');
    }

    /**
     * @Route("/show/{id}", name="show_bdd")
     * @param Clients $clients
     * @param Request $request
     * @param ObjectManager $manager
     * @param MutuelleRepository $repoMutuelle
     * @return RedirectResponse|Response
     */
    public function showBdd(Clients $clients, Request $request, ObjectManager $manager, MutuelleRepository $repoMutuelle)
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
                }

                //il lui reste des charges, on en enleve une
                $clients->setNbInter($interClient - 1);
            }
            $manager->persist($inter);
            $manager->persist($clients);
            $manager->flush();
            return $this->redirectToRoute('show_bdd', ['id' => $clients->getId() ]);
        }
        return $this->render('bdd/showBdd.html.twig', [
            'client' => $clients,
            'form' => $form->createView(),
            'mutuelleList' => $repoMutuelle->findAll()
        ]);
    }

    /**
     * @Route("/addClientMutuelle/{id}", name="suscription")
     * @param Clients $clients
     * @param ObjectManager $manager
     * @param MutuelleRepository $mutuelleRepo
     * @param Request $request
     * @return RedirectResponse
     */
    public function addMutuelle (Clients $clients, ObjectManager $manager, MutuelleRepository $mutuelleRepo, Request $request): RedirectResponse
    {
        $getParam = $request->query->get('mutuelle');
        $mutuelleList = $mutuelleRepo->findOneBy(['nom' => $getParam]);
        if ($mutuelleList === null) {
            $this->addFlash(
                'warning',
                'Mutuelle introuvable'
            );
            dump($getParam);
            return $this->redirectToRoute('bdd_index');
        }

        $mutuelleCible = $this->getDoctrine()->getManager()->getRepository(Mutuelle::class)->findOneBy(['nom'=>$getParam]);
        $oldInter = $clients->getNbInter();
        $newInter = $oldInter + $mutuelleCible->getNbInter();
        $clients->setMutuelle($mutuelleCible);
        $clients->setNbInter($newInter);
        $manager->persist($clients);
        $manager->flush();
        return $this->redirectToRoute('show_bdd', ['id'=> $clients->getId()]);
    }

    /**
     * @Route("/modifier/{id}", name="mod_bdd")
     * @param Clients $clients
     * @param ObjectManager $manager
     * @param Request $request
     */
    public function modifBdd(Clients $clients, ObjectManager $manager, Request $request)
    {
        //récupération du client en cour de visualisation pour son idition
        // envoi du formulaire avec les info courrante de la personne pour édition
        // récupération du formulaire en enregistrement des modification
    }
}
