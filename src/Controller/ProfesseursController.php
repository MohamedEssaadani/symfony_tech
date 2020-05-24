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

        dd($professeurs);

        return $this->render('professeurs/browse.html.twig', [
            'controller_name' => 'ProfesseursController',
        ]);
    }
}
