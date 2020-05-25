<?php

namespace App\Controller;

use App\Entity\Departement;
use App\Entity\Professeur;
use App\Repository\ProfesseurRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\DepartementRepository;
use PhpParser\Node\Expr\Cast\String_;
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
        //dateRecrutement property accept date type so, create date variable contain date value from string value coming from form
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
