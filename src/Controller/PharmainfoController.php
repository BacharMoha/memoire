<?php
// src/Controller/PharmacieGardeController.php
namespace App\Controller;

use App\Entity\Pharmacie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PharmainfoController extends AbstractController
{
    #[Route('/infopharmacie/{id}', name: 'app_infopharmacie')]
   
    public function show(int $id, EntityManagerInterface $entityManager): Response
    {
        // Récupérer la pharmacie par son ID
        $pharmacie = $entityManager->getRepository(Pharmacie::class)->find($id);

        if (!$pharmacie) {
            throw $this->createNotFoundException('La pharmacie demandée n\'existe pas.');
        }

        // Passer les détails de la pharmacie au template
        return $this->render('info_pharmacie.html.twig', [
            'pharmacie' => $pharmacie,
        ]);
    }
   
   
}
