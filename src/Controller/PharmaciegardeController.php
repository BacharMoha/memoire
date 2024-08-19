<?php
// src/Controller/PharmacieGardeController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PharmacieGardeController extends AbstractController
{
    #[Route('/pharmacie-garde', name: 'pharmacie_garde')]
    public function pharmacieGarde(): Response
    {
        return $this->render('pharmacie_garde.html.twig');
    }
}
