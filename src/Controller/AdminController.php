<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
    #[Route('/admin/inscription', name: 'app_inscription')]
    public function inscription(): Response
    {
        return $this->render('admin/inscription.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
    #[Route('/admin/pharmacien', name: 'app_pharmacien')]
    public function pharmacien(): Response
    {
        return $this->render('admin/pharmacien.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
    #[Route('/admin/add_medicament', name: 'app_addmedicament')]
    public function addmedic(): Response
    {
        return $this->render('admin/add_medicament.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
    #[Route('/admin/add_heur', name: 'app_addheur')]
    public function addheur(): Response
    {
        return $this->render('admin/add_heur.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
    #[Route('/admin/modif_medicament', name: 'app_modifmedic')]
    public function modifmedic(): Response
    {
        return $this->render('admin/modificationmedic.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
    #[Route('/admin/listemedic', name: 'app_listemedic')]
    public function listemedic(): Response
    {
        return $this->render('admin/listemedic.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/admin/ordrepharmac', name: 'app_ordrepharmacie')]
    public function ordrepharmac(): Response
    {
        return $this->render('admin/ordre_pharmacie.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
    #[Route('/admin/importplplng', name: 'app_importplanning')]
    public function importplplng(): Response
    {
        return $this->render('admin/import_planning.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
    #[Route('/admin/creerplang', name: 'app_creerplannig')]
    public function creerplang(): Response
    {
        return $this->render('admin/creer_planning.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
    #[Route('/admin/modifiplanning', name: 'app_modifplanning')]
    public function modifiplanning(): Response
    {
        return $this->render('admin/modificationplanning.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
    
    #[Route('/admin/notif', name: 'app_notifications')]
    public function notif(): Response
    {
        return $this->render('admin/creer_notification.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
    
    #[Route('/admin/gerenotifi', name: 'app_gerernotifications')]
    public function gerenotifi(): Response
    {
        return $this->render('admin/gerer_notification.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
    
    #[Route('/admin/admingener', name: 'app_admingenerale')]
    public function admingener(): Response
    {
        return $this->render('admin/admingenerale.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
    
    #[Route('/admin/gererpharama', name: 'app_gerepharmacie')]
    public function gererpharma(): Response
    {
        return $this->render('admin/gerer_pharmacie.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
    
    #[Route('/admin/inscriptpharma', name: 'app_inscripComptepharma')]
    public function inscriptpharma(): Response
    {
        return $this->render('admin/inscription_pharmacien.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
    
    #[Route('/admin/modifinfophrm', name: 'app_modificationinfopharma')]
    public function modifinfophrm(): Response
    {
        return $this->render('admin/modifierinfo_pharmacien.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
 
    #[Route('/admin/listplannings', name: 'app_voirplanning')]
    public function listplannings(): Response
    {
        return $this->render('admin/voir_planning.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
 
    #[Route('/admin/inscriptordreph', name: 'app_inscripordre')]
    public function inscriptordreph(): Response
    {
        return $this->render('admin/inscription_ordrepharamacie.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
 
    #[Route('/admin/gererordr', name: 'app_gererordre')]
    public function gererordr(): Response
    {
        return $this->render('admin/gerer_ordreph.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
 
    #[Route('/admin/modifordr', name: 'app_modifierordre')]
    public function modifordr(): Response
    {
        return $this->render('admin/modifierordreph.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
 
    
}
