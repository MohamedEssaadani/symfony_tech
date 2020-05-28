<?php

namespace App\Controller;

use App\Entity\Cours;
use App\Entity\Professeur;
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
        //get the id from the request object
        $id = $request->request->get("id");
        //get the course by id
        $cour = $entityManager->getRepository(Cours::class)->find($id);
        //change initule value of the course & save changes
        $cour->setInitule($request->request->get("initule"));
        $entityManager->flush();


        return $this->redirectToRoute('Cours.browse');
    }


    /**
     * @Route("/destroy-cour/{id}", name="Cours.destroy")
     */
    public function destroy(int $id, EntityManagerInterface $entityManager)
    {
        //get the course object by id
        $cour = $entityManager->getRepository(Cours::class)->find($id);

        //remove the course & save changes
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
            'professeurs' => $professeurs,
            'cour' => $cour
        ]);
    }

    /**
     * @Route("/affect-cour", name="Cours.GetAffect")
     */
    public function getAffect(EntityManagerInterface $entityManager)
    {
        //get course 
        $cours = $entityManager->getRepository(Cours::class)->findAll();

        //get professor
        $professeurs = $entityManager->getRepository(Professeur::class)->findAll();


        return $this->render('cours/affect.html.twig', [
            'professeurs' => $professeurs,
            'cours' => $cours
        ]);
    }

    /**
     * @Route("/affect-cour-post", name="Cours.PostAffect")
     */
    public function postAffect(Request $request, EntityManagerInterface $entityManager)
    {
        //get course 
        $cour = $entityManager->getRepository(Cours::class)->find($request->request->get("cour"));

        //get professor
        $professeur = $entityManager->getRepository(Professeur::class)->find($request->request->get("professor"));

        //add professor to the course
        $cour->addProfesseur($professeur);
        //add the course to the profssor
        $professeur->addCour($cour);

        //save changes
        $entityManager->flush();

        return $this->redirectToRoute('Cours.browse');
    }
}
