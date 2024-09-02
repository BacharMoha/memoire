<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InscriptionController extends AbstractController
{
    #[Route('/inscrip-tion', name: 'inscription')]
    public function inscriptions(): Response
    {
        return $this->render('inscription.html.twig');
    }
}
