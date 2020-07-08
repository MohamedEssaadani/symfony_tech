<?php

namespace App\Controller;

use App\Entity\Cours;
use App\Entity\Professeur;
use App\Entity\Departement;
use PhpParser\Node\Expr\Cast\String_;
use App\Repository\ProfesseurRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\DepartementRepository;
use Symfony\Component\HttpFoundation\Request;
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
     * @Route("/create-professeur", name="Professeurs.create")
     */
    public function create(DepartementRepository $repository)
    {
        $departements = $repository->findAll();

        return $this->render('professeurs/create.html.twig', [
            'controller_name' => 'ProfesseursController',
            'departements' => $departements
        ]);
    }

    /**
     * @Route("/store-professeur", name="Professeurs.store")
     */
    public function store(EntityManagerInterface $em, Request $request)
    {
        //create new professeur object & set his values from request object
        $professeur = new Professeur();
        $professeur->setNom($request->request->get("nom"));
        $professeur->setPrenom($request->request->get("prenom"));
        $professeur->setCin($request->request->get("cin"));
        $professeur->setAdresse($request->request->get("adresse"));
        $professeur->setTelephone($request->request->get("telephone"));
        $professeur->setEmail($request->request->get("email"));
        //dateRecrutement property accept date type so, create date variable contain date
        // value from string value coming from form
        $date = \DateTime::createFromFormat('Y-m-d', $request->request->get("date_recrutement"));
        $professeur->setDateRecrutement($date);
        //get departement object by request id in order to set it to departement property of Professor
        $departement = $em->find(Departement::class, $request->request->get("departement"));
        $professeur->setDepartement($departement);

        // save professor eventually 
        $em->persist($professeur);

        // save changes to database
        $em->flush();

        //redirect to professors list 
        return $this->redirectToRoute('Professeurs.browse');
    }

    /**
     * @Route("/edit-professeur/{id}", name="Professeurs.edit")
     */

    public function edit(
        ProfesseurRepository $repository,
        DepartementRepository $departementRepository,
        int $id
    ) {
        $professeur = $repository->find($id);
        $departements = $departementRepository->findAll();

        if (!$professeur) {
            throw $this->createNotFoundException(
                'No Professor found for this id: ' . $id
            );
        }

        return $this->render('professeurs/edit.html.twig', [
            'controller_name' => 'ProfesseursController',
            'professeur' => $professeur,
            'departements' => $departements
        ]);
    }

    /**
     * @Route("/update-professeur", name="Professeurs.update")
     */
    public function update(Request $request, EntityManagerInterface $entityManager)
    {
        $id = $request->request->get('id');

        $professeur = $entityManager->find(Professeur::class, $id);


        if (!$professeur) {
            throw $this->createNotFoundException(
                'No Professor found for this id: ' . $id
            );
        }

        $professeur->setNom($request->request->get("nom"));
        $professeur->setPrenom($request->request->get("prenom"));
        $professeur->setCin($request->request->get("cin"));
        $professeur->setAdresse($request->request->get("adresse"));
        $professeur->setTelephone($request->request->get("telephone"));
        $professeur->setEmail($request->request->get("email"));
        //dateRecrutement property accept date type so, create date variable contain date value from string value coming from form
        $date = \DateTime::createFromFormat('Y-m-d', $request->request->get("date_recrutement"));
        $professeur->setDateRecrutement($date);
        //get departement object by request id in order to set it to departement property of Professor
        $departement = $entityManager->find(Departement::class, $request->request->get("departement"));
        $professeur->setDepartement($departement);

        $entityManager->flush();

        return $this->redirectToRoute('Professeurs.browse');
    }


    /**
     * @Route("/show-professeur/{id}", name="Professeurs.show")
     */
    public function show(int $id, ProfesseurRepository $repository)
    {
        $professeur = $repository->find($id);

        if (!$professeur) {
            throw $this->createNotFoundException(
                'No Professor found for this id: ' . $id
            );
        }
        return $this->render('professeurs/show.html.twig', [
            'controller_name' => 'ProfesseursController',
            'professeur' => $professeur
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


    /**
     * @Route("/affect-cour", name="Professors.GetAffect")
     */
    public function getAffect(EntityManagerInterface $entityManager)
    {
        //get course 
        $cours = $entityManager->getRepository(Cours::class)->findAll();

        //get professor
        $professeurs = $entityManager->getRepository(Professeur::class)->findAll();


        return $this->render('professeurs/affect.html.twig', [
            'professeurs' => $professeurs,
            'cours' => $cours
        ]);
    }

    /**
     * @Route("/affect-cour-post", name="Professors.PostAffect")
     */
    public function postAffect(Request $request, EntityManagerInterface $entityManager)
    {
        //get selected courses
        $cours = $request->request->get("cours");

        //get selected professor 
        $professeur = $entityManager->getRepository(Professeur::class)->find($request->request->get("professor"));

        //loop trough selected professors & add them to selected course
        foreach ($cours as $cour) {
            //get cour
            $c = $entityManager->getRepository(Cours::class)
                ->find($cour);
            //add cour to the professor
            $professeur->addCour($c);

            //add Professor to course
            $c->addProfesseur($professeur);
        }



        //save changes
        $entityManager->flush();

        return $this->redirectToRoute('Professeurs.browse');
    }

    //this function is for showing courses of selected professor
    //it will accept as parameter id of professor & get his courses 
    /**
     * @Route("/professor-courses/{id}", name="Professors.Courses")
     */
    public function getCourses(int $id, ProfesseurRepository $repository)
    {
        //get professor that has id
        $professor = $repository->find($id);
        //get his courses
        $courses = $professor->getCour();

        return $this->render('professeurs/courses.html.twig', [
            'courses' => $courses,
            'professor' => $professor
        ]);
    }
}
