<?php

namespace App\Controller;

use App\Entity\Departement;
use App\Entity\Professeur;
use App\Repository\DepartementRepository;
use App\Repository\ProfesseurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
     * @Route("/create-departement", name="Departements.create")
     */
    public function create()
    {
        return $this->render('departements/create.html.twig');
    }

    /**
     * @Route("/store-departement", name="Departements.store")
     */
    public function store(EntityManagerInterface $entityManager, Request $request)
    {
        $nom = $request->request->get('nom');

        $departement = new Departement();
        $departement->setNomDepartement($nom);

        $entityManager->persist($departement);
        $entityManager->flush();

        return $this->redirectToRoute('Departements.browse');
    }

    /**
     * @Route("/destroy-departement/{id}", name="Departements.destroy")
     */
    public function destroy(EntityManagerInterface $entityManager, int $id)
    {
        $departement = $entityManager->getRepository(Departement::class)->find($id);
        $entityManager->remove($departement);
        $entityManager->flush();

        return $this->redirectToRoute('Departements.browse');
    }

    /**
     * @Route("/edit-departement/{id}", name="Departements.edit")
     */
    public function edit(DepartementRepository $repository, int $id)
    {
        $departement = $repository->find($id);

        return $this->render('departements/edit.html.twig', [
            'departement' => $departement
        ]);
    }

    /**
     * @Route("/update-departement", name="Departements.update")
     */
    public function update(EntityManagerInterface $entityManager, Request $request)
    {
        $id = $request->request->get('id');
        $departement = $entityManager->getRepository(Departement::class)->find($id);

        if (!$departement) {
            throw $this->createNotFoundException(
                'No Departement found for this id: ' . $id
            );
        }

        $departement->setNomDepartement($request->request->get('nom'));
        $entityManager->flush();

        return $this->redirectToRoute('Departements.browse');
    }

    /**
     * @Route("/departement/{id}", name="Departements.show")
     */
    public function show(DepartementRepository $departementRepository, int $id)
    {
        $departement = $departementRepository->find($id);

        return $this->render('Departements/show.html.twig', [
            'departement' => $departement
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
