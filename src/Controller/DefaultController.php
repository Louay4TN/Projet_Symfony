<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\VoitureRepository;
use App\Entity\Voiture;
use App\Entity\Commande;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'app_default')]
    public function index(VoitureRepository $lr): Response
    {
        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
            'Voitures' => $lr->findAll(),
        ]);
    }
    #[Route('/item/{id}', name: 'app_item')]
    public function indexitem(VoitureRepository $lr, int $id): Response
    {
        $Voiture = $lr->find($id);
        if (!$Voiture) {
            throw $this->createNotFoundException('Produit non trouvé.');
        }
        return $this->render('default/item.html.twig', [
            'Voiture' => $Voiture, 
        ]);
    }


#[Route('/achat/{id}', name: 'app_default_achat', methods: ['POST'])]
    public function acheter(
        Voiture $Voiture,
        EntityManagerInterface $entityManager,
        Security $security
    ): Response {
        if ($Voiture->getQuantite() <= 0) {
            $this->addFlash('danger', 'Cette Voiture est indisponible.');
            return $this->redirectToRoute('app_default');
        }

        $user = $security->getUser();
        if (!$user) {
            $this->addFlash('danger', 'Vous devez être connecté pour effectuer cet achat.');
            return $this->redirectToRoute('app_login');
        }

        $commande = new Commande();
        $commande->setVoiture($Voiture);
        $commande->setQuantite(1); 
        $commande->setPrix($Voiture->getPrix());
        $commande->setDate(new \DateTime());

        $commande->setUser($user);

        $Voiture->setQuantite($Voiture->getQuantite() - 1);

        $entityManager->persist($commande);
        $entityManager->persist($Voiture);
        $entityManager->flush();

    $this->addFlash('success', 'Commande effectuée avec succès.');
    return $this->redirectToRoute('app_default', ['success' => 1]);
    }
}
