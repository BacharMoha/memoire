<?php
// src/Controller/MedicamentController.php
namespace App\Controller;

use App\Entity\Medicament;
use App\Entity\Pharmacie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MedicamentController extends AbstractController
{
    #[Route('/recherche-medicament', name: 'recherche_medicament')]
  
    public function rechercheMedicament(Request $request,EntityManagerInterface $entityManager): Response
    {
     
        // Récupérer tous les médicaments avec leurs pharmacies
        $nom = $request->query->get('nom', '');

        if ($nom) {
            // Requête DQL pour rechercher le médicament par son nom
            $query = $entityManager->createQuery(
                'SELECT m, p
                 FROM App\Entity\Medicament m
                 LEFT JOIN m.pharmacies p
                 WHERE m.NomMedoc LIKE :nom'
            )->setParameter('nom', '%' . $nom . '%');
            
            $medicaments = $query->getResult();
        } else {
            // Requête DQL pour obtenir un médicament pour chaque pharmacie
            $query = $entityManager->createQuery(
                'SELECT m, p
                 FROM App\Entity\Medicament m
                 LEFT JOIN m.pharmacies p
                 WHERE m IN (
                     SELECT m1
                     FROM App\Entity\Medicament m1
                     LEFT JOIN m1.pharmacies p1
                     GROUP BY p1.id
                 )'
            );

            $medicaments = $query->getResult();

            // Grouper les médicaments par pharmacie
            $medicamentPerPharmacie = [];
            foreach ($medicaments as $medicament) {
                foreach ($medicament->getPharmacies() as $pharmacie) {
                    if (!isset($medicamentPerPharmacie[$pharmacie->getId()])) {
                        $medicamentPerPharmacie[$pharmacie->getId()] = $medicament;
                        break; // On arrête après avoir trouvé un médicament pour la pharmacie
                    }
                }
            }

            $medicaments = array_values($medicamentPerPharmacie);
        }
        return $this->render('recherche_medicament.html.twig', [
            'medicaments' => $medicaments,
            'nom' => $nom
        ]);
    
    }
    #[Route('/medicament/{id}/toggle-disponibilite', name: 'toggle_disponibilite')]
    public function toggleDisponibilite(Medicament $medicament, EntityManagerInterface $entityManager): Response
    {
        $medicament->setDisponible(!$medicament->isDisponible());
        $entityManager->flush();

        return $this->redirectToRoute('app_listemedic');
    }

    
}

