<?php

namespace App\Controller;

use App\Entity\Professeur;
use App\Repository\ProfesseurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfesseursController extends AbstractController
{
    /**
     * @Route("/professeurs", name="Professeurs.browse")
     */
    public function index(ProfesseurRepository $repository)
    {
        $professeurs = $repository->findAll();


        return $this->render('professeurs/browse.html.twig', [
            'controller_name' => 'ProfesseursController',
            'professeurs' => $professeurs
        ]);
    }

    /**
     * @Route("/destroy-professeur/{id}", name="Professeurs.destroy")
     */

    public function destroy(EntityManagerInterface $em, int $id)
    {
        $professeur = $em->getRepository(Professeur::class)->find($id);
        $em->remove($professeur);
        $em->flush();

        return $this->redirectToRoute('Professeurs.browse');
    }
}
