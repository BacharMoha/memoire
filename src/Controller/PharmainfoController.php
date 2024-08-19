<?php
// src/Controller/PharmacieGardeController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PharmainfoController extends AbstractController
{
    #[Route('/info-phamacie', name: 'info_pharmacie')]
    public function infoPharmacie(): Response
    {
        return $this->render('info_pharmacie.html.twig');
    }
}
