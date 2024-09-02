<?php 

namespace App\Controller;

use App\Entity\Pharmacie;
use App\Entity\PlanningGarde;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PharmaciegardeController extends AbstractController
{
    #[Route('/pharmacie-garde', name: 'pharmacie_garde')]
    public function pharmacieGarde(EntityManagerInterface $entityManager): Response
    {
        // Récupérer l'heure actuelle uniquement (sans la date)
        $currentTime = new \DateTime('now', new \DateTimeZone('Indian/Comoro'));
        $currentHour = $currentTime->format('H:i:s');
        dump('Heure actuelle : ' . $currentHour);

        // Récupérer tous les plannings de garde
        $query = $entityManager->createQuery(
            'SELECT pg
            FROM App\Entity\PlanningGarde pg
            JOIN pg.idPharmacie p
            ORDER BY pg.dateDebut ASC'
        );

        $planningGardes = $query->getResult();
        dump($planningGardes);

        // Vérification manuelle des heures
        $pharmacieDeGarde = null;
        foreach ($planningGardes as $planningGarde) {
            $debut = $planningGarde->getDateDebut()->format('H:i:s');
            $fin = $planningGarde->getDateFin()->format('H:i:s');

            dump('Heure de début : ' . $debut);
            dump('Heure de fin : ' . $fin);

            if ($currentHour >= $debut && $currentHour <= $fin) {
                $pharmacieDeGarde = $planningGarde;
                break;
            }
        }

        if (!$pharmacieDeGarde) {
            return $this->render('pharmacie_garde.html.twig', [
                'message' => "Aucune pharmacie de garde n'est actuellement disponible.",
                'pharmacie' => null,
                'planning' => null
            ]);
        }

        $pharmacie = $pharmacieDeGarde->getIdPharmacie();

        return $this->render('pharmacie_garde.html.twig', [
            'pharmacie' => $pharmacie,
            'planning' => $pharmacieDeGarde,
            'message' => null
        ]);
    }
}





