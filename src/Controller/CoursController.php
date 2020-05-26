<?php

namespace App\Controller;

use App\Entity\Cours;
use App\Repository\CoursRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CoursController extends AbstractController
{
    /**
     * @Route("/cours", name="Cours.browse")
     */
    public function index(CoursRepository $coursRepository)
    {
        $cours = $coursRepository->findAll();

        return $this->render('cours/browse.html.twig', [
            'controller_name' => 'CoursController',
            'cours' => $cours
        ]);
    }

    /**
     * @Route("/create-cour", name="Cours.create")
     */
    public function create()
    {
        return $this->render('cours/create.html.twig');
    }

    /**
     * @Route("/store-cour", name="Cours.store")
     */
    public function store(Request $request, EntityManagerInterface $entityManager)
    {

        $cour = new Cours();
        $cour->setInitule($request->request->get('initule'));
        $entityManager->persist($cour);
        $entityManager->flush();

        return $this->redirectToRoute('Cours.browse');
    }

    /**
     * @Route("/cours/{id}", name="Cours.show")
     */
    public function show(int $id, CoursRepository $coursRepository)
    {
        $cour = $coursRepository->find($id);

        return $this->render('cours/show.html.twig', [
            'cour' => $cour
        ]);
    }

    /**
     * @Route("/edit-cour/{id}", name="Cours.edit")
     */
    public function edit(int $id, CoursRepository $coursRepository)
    {
        $cour = $coursRepository->find($id);

        return $this->render('cours/edit.html.twig', [
            'cour' => $cour
        ]);
    }

    /**
     * @Route("/update-cour", name="Cours.update")
     */
    public function update(Request $request, EntityManagerInterface $entityManager)
    {
        $id = $request->request->get("id");
        $cour = $entityManager->getRepository(Cours::class)->find($id);
        $cour->setInitule($request->request->get("initule"));
        $entityManager->flush();


        return $this->redirectToRoute('Cours.browse');
    }


    /**
     * @Route("/destroy-cour/{id}", name="Cours.destroy")
     */
    public function destroy(int $id, EntityManagerInterface $entityManager)
    {
        $cour = $entityManager->getRepository(Cours::class)->find($id);
        $entityManager->remove($cour);
        $entityManager->flush();

        return $this->redirectToRoute('Cours.browse');
    }


    /**
     * @Route("/cour/{id}/professors", name="Cours.professors")
     */
    public function professeurs(int $id, EntityManagerInterface $entityManager)
    {
        //get course first
        $cour = $entityManager->getRepository(Cours::class)->find($id);

        //get professors of this course by accessing the professors property of course
        $professeurs = $cour->getProfesseur();


        return $this->render('cours/professeurs.html.twig', [
            'professeurs' => $professeurs
        ]);
    }
}
