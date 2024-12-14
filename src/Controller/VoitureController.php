<?php

namespace App\Controller;

use App\Entity\Voiture;
use App\Entity\Commande;
use App\Form\VoitureType;
use App\Repository\VoitureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/Voiture')]
class VoitureController extends AbstractController
{
    private $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    #[Route('/', name: 'app_Voiture_index', methods: ['GET'])]
    public function index(VoitureRepository $VoitureRepository): Response
    {
        return $this->render('Voiture/index.html.twig', [
            'Voitures' => $VoitureRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_Voiture_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $Voiture = new Voiture();
        $form = $this->createForm(VoitureType::class, $Voiture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('photo')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $this->slugger->slug($originalFilename); 
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'), 
                        $newFilename
                    );
                } catch (FileException $e) {
                }

                $Voiture->setPhoto($newFilename);
            }

            $entityManager->persist($Voiture);
            $entityManager->flush();

            return $this->redirectToRoute('app_Voiture_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('Voiture/new.html.twig', [
            'Voiture' => $Voiture,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_Voiture_show', methods: ['GET'])]
    public function show(Voiture $Voiture): Response
    {
        return $this->render('Voiture/show.html.twig', [
            'Voiture' => $Voiture,
        ]);
    }
    
    #[Route('/{id}/edit', name: 'app_Voiture_edit', methods: ['GET', 'POST'])]
public function edit(Request $request, Voiture $Voiture, EntityManagerInterface $entityManager): Response
{
    $form = $this->createForm(VoitureType::class, $Voiture);
    $form->handleRequest($request);

    // Stocker l'ancienne photo
    $originalPhoto = $Voiture->getPhoto();

    if ($form->isSubmitted() && $form->isValid()) {
        // Récupérer le fichier photo soumis dans le formulaire
        $imageFile = $form->get('photo')->getData();

        if ($imageFile) {
            if ($originalPhoto) {
                $oldImagePath = $this->getParameter('images_directory') . '/' . $originalPhoto;
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $this->slugger->slug($originalFilename);
            $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

            try {
                $imageFile->move(
                    $this->getParameter('images_directory'),
                    $newFilename
                );
            } catch (FileException $e) {
            }

            $Voiture->setPhoto($newFilename);
        } else {
            $Voiture->setPhoto($originalPhoto);
        }

        $entityManager->flush();

        return $this->redirectToRoute('app_Voiture_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('Voiture/edit.html.twig', [
        'Voiture' => $Voiture,
        'form' => $form,
    ]);
}


    #[Route('/{id}', name: 'app_Voiture_delete', methods: ['POST'])]
public function delete(Request $request, Voiture $Voiture, EntityManagerInterface $entityManager): Response
{
    if ($this->isCsrfTokenValid('delete'.$Voiture->getId(), $request->request->get('_token'))) {
        // Find and remove all commandes related to this Voiture
        $commandes = $entityManager->getRepository(Commande::class)->findBy(['Voiture' => $Voiture]);
        foreach ($commandes as $commande) {
            $entityManager->remove($commande);
        }

        // Now remove the Voiture
        $entityManager->remove($Voiture);
        $entityManager->flush();
    }

    return $this->redirectToRoute('app_Voiture_index', [], Response::HTTP_SEE_OTHER);
}

}
