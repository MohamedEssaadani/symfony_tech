<?php

namespace App\Controller;

use App\Repository\ProfesseurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProfesseursController extends AbstractController
{
    /**
     * @Route("/professeurs", name="professeurs")
     */
    public function index(ProfesseurRepository $repository)
    {
        $professeurs = $repository->findAll();


        return $this->render('professeurs/browse.html.twig', [
            'controller_name' => 'ProfesseursController',
            'professeurs' => $professeurs
        ]);
    }
}
