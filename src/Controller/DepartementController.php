<?php

namespace App\Controller;

use App\Repository\DepartementRepository;
use App\Repository\ProfesseurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DepartementController extends AbstractController
{
    /**
     * @Route("/departements", name="Departements.browse")
     */
    public function index(DepartementRepository $repository)
    {
        $departements = $repository->findAll();

        return $this->render('departements/browse.html.twig', [
            'controller_name' => 'DepartementController',
            'departements' => $departements
        ]);
    }

    /**
     * @Route("/departements/{id}/professeurs", name="Departements.professeurs")
     */

    public function professeurs(int $id, ProfesseurRepository $professeurRepository, DepartementRepository $departementRepository)
    {
        $professeurs = $professeurRepository->findBy(['departement' => $id]);
        $departement = $departementRepository->find($id);

        return $this->render('departements/browse-professeurs.html.twig', [
            'professeurs' => $professeurs,
            'departement' => $departement
        ]);
    }
}
