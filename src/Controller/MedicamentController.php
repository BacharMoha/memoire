<?php
// src/Controller/MedicamentController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MedicamentController extends AbstractController
{
    #[Route('/recherche-medicament', name: 'recherche_medicament')]
    public function rechercheMedicament(): Response
    {
        return $this->render('recherche_medicament.html.twig');
    }
}

