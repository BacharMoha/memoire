<?php

namespace App\Controller;

use App\Entity\Medicament;
use App\Entity\Pharmacie;
use App\Entity\PlanningGarde;
use App\Entity\UserOrdrePharma;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AdminController extends AbstractController
{
  
    
    #[Route('/admin/inscription', name: 'app_inscription')]
    public function inscription(): Response
    {
        return $this->render('admin/inscription.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
  
    #[Route('/create_pharma', name: 'app_create_pharma')]
    public function create(Request $request,EntityManagerInterface $entityManager): Response
    {
            $password = $request->request->get('password');
            $confirmPassword = $request->request->get('confirm_password');

            // Vérification que les mots de passe correspondent
            if ($password !== $confirmPassword) {
                $this->addFlash('error', 'Les mots de passe ne correspondent pas.');
                return $this->redirectToRoute('app_inscripComptepharma');
            }
            $pharma = new Pharmacie();
            
            $pharma->setNomPharma($request->request->get('nomphrma'));
            $pharma->setAddpharma($request->request->get('adresse'));
            $pharma->setTel($request->request->get('tel'));
            $pharma->setEmail($request->request->get('email'));
            $pharma->setDescription($request->request->get('description'));
            $pharma->setAddmaps($request->request->get('addmaps'));
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $pharma->setMdp($hashedPassword);
            $imageFile = $request->files->get('image');
            if ($imageFile) {
                $imageFilename = uniqid().'.'.$imageFile->guessExtension();
                $imageFile->move($this->getParameter('medicaments_directory'), $imageFilename);
                $pharma->setImage($imageFilename);
            } else {
                $pharma->setImage(null); // Optionnel, car la valeur par défaut est déjà null
            }
            $entityManager->persist($pharma);
            $entityManager->flush();

            return $this->redirectToRoute('app_index');
    }

    #[Route('/admin/add_medicament', name: 'app_addmedicament')]
    public function addMedicament(
        Request $request, 
        EntityManagerInterface $entityManager, 
        Security $security
    ): Response {
        // Récupérer l'utilisateur actuellement connecté
        /** @var \App\Entity\Pharmacie $pharmacie */
        $pharmacie = $security->getUser();

        if (!$pharmacie) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour ajouter un médicament.');
        }

        // Créer un nouvel objet Médicament
        $medicament = new Medicament();

        // Gérer la soumission du formulaire
        if ($request->isMethod('POST')) {
            $medicament->setNomMedoc($request->request->get('name'));
            $medicament->setDescription($request->request->get('description'));
            $medicament->setPrix($request->request->get('prix'));

            // Gestion de l'image uploadée
            $imageFile = $request->files->get('image');
            if ($imageFile) {
                $imageFilename = uniqid().'.'.$imageFile->guessExtension();
                $imageFile->move($this->getParameter('medicaments_directory'), $imageFilename);
                $medicament->setImage($imageFilename);
            }

            // Associer le médicament à la pharmacie
            $pharmacie->addMedicament($medicament);

            // Persister les données dans la base de données
            $entityManager->persist($medicament);
            $entityManager->flush();

            // Redirection après l'ajout
            return $this->redirectToRoute('app_listemedic');
        }

        return $this->render('admin/add_medicament.html.twig');
    }
    #[Route('/admin/add_heur', name: 'app_addheur')]
    public function addheur(): Response
    {
        return $this->render('admin/add_heur.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    
  


    
    #[Route('/admin/modif_medicament{id}', name: 'app_modifmedic')]

    public function edite(Request $request, EntityManagerInterface $entityManager, $id,Security $security): Response
    {
        // Récupère la pharmacie par son ID
        $medicament = $entityManager->getRepository(Medicament::class)->find($id);

        // Vérifie si la medicament existe
        if (!$medicament) {
            throw $this->createNotFoundException('La medicament demandée n\'existe pas.');
        }

        // Traitement du formulaire lorsque soumis
        if ($request->isMethod('POST')) {
            $name = $request->request->get('name');
            $description = $request->request->get('description');
            

            // Mise à jour des données de la medicament
            $medicament->setNomMedoc($name);
            $medicament->setdescription($description);
          

            // Mise à jour du mot de passe si fourni
           

            // Sauvegarde les modifications dans la base de données
            $entityManager->flush();

            return $this->redirectToRoute('app_listemedic');
        }

        // Affiche le formulaire de modification
        $pharmacie = $security->getUser();
        return $this->render('admin/modificationmedic.html.twig', [
            'pharmacie' => $pharmacie,
            'medicament' => $medicament
        ]);
    }

    #[Route('/admin/deletinfophrm/{id}', name: 'medicament_delete')]
   

    public function deletes(Request $request, EntityManagerInterface $entityManager, $id): RedirectResponse
    {
        // Assurez-vous que l'utilisateur est authentifié et a le rôle requis

        $medicament = $entityManager->getRepository(medicament::class)->find($id);

        if (!$medicament) {
            throw $this->createNotFoundException('La medicament demandée n\'existe pas.');
        }

        if ($this->isCsrfTokenValid('delete' . $id, $request->request->get('_token'))) {
            $entityManager->remove($medicament);
            $entityManager->flush();
            $this->addFlash('success', 'medicament supprimée avec succès.');
        } else {
            $this->addFlash('error', 'Token CSRF invalide.');
        }

        return $this->redirectToRoute('app_listemedic');
    }
    public function modifmedic(Security $security): Response
    {
        $pharmacie = $security->getUser();
        return $this->render('admin/modificationmedic.html.twig', [
            'pharmacie' => $pharmacie,
        ]);
    }
    #[Route('/admin/listemedic', name: 'app_listemedic')]
    public function listemedic(Security $security): Response
    {
        /** @var \App\Entity\Pharmacie $pharmacie */
    $pharmacie = $security->getUser();

    $medicaments = $pharmacie->getMedicaments();

        return $this->render('admin/listemedic.html.twig', [
            'medicaments' => $medicaments,
            'pharmacie' => $pharmacie,
        ]);
    }

    #[Route('/admin/ordrepharmac', name: 'app_ordrepharmacie')]
    public function ordrepharmac(): Response
    {
        return $this->render('admin/ordre_pharmacie.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/create_ordrepharma', name: 'app_create_ordrepharma')]
    public function creates(Request $request,EntityManagerInterface $entityManager): Response
    {
            $password = $request->request->get('password');
            $confirmPassword = $request->request->get('confirm_password');

            // Vérification que les mots de passe correspondent
            if ($password !== $confirmPassword) {
                $this->addFlash('error', 'Les mots de passe ne correspondent pas.');
                return $this->redirectToRoute('app_inscripComptepharma');
            }
            $pharma = new UserOrdrePharma();
            
            $pharma->setUsername($request->request->get('name'));
            $pharma->setEmail($request->request->get('email'));
            $pharma->setTel($request->request->get('phone'));
            $pharma->setRoles(['USER_ORDERPHARM']);
            
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $pharma->setPassword($hashedPassword);
         
            $entityManager->persist($pharma);
            $entityManager->flush();

            return $this->redirectToRoute('app_admingenerale');
    }

    #[Route('/admin/importplplng', name: 'app_importplanning')]
    public function importplplng(): Response
    {
        return $this->render('admin/import_planning.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
    #[Route('/admin/creerplang', name: 'app_creerplannig')]
    public function creerplang( EntityManagerInterface $entityManager ): Response
    {
        $pharmacie = $entityManager->getRepository(Pharmacie::class)->findAll();
        return $this->render('admin/creer_planning.html.twig', [
            'pharmacie' => $pharmacie,
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
    public function admingener(EntityManagerInterface $entityManager): Response
    {
        $pharmacies = $entityManager->getRepository(Pharmacie::class)->findAll();
        $planning = $entityManager->getRepository(PlanningGarde::class)->findAll();

        return $this->render('admin/admingenerale.html.twig', [
            'pharmacies' => $pharmacies,
            'planning' => $planning,
        ]);
    }
    
    #[Route('/admin/gererpharama', name: 'app_gerepharmacie')]
    public function gererpharma(EntityManagerInterface $entityManager): Response
    {
        $pharmacies = $entityManager->getRepository(Pharmacie::class)->findAll();
        return $this->render('admin/gerer_pharmacie.html.twig', [
            'pharmacies' => $pharmacies,
        ]);
    }
    
    #[Route('/admin/inscriptpharma', name: 'app_inscripComptepharma')]
    public function inscriptpharma(): Response
    {
        return $this->render('admin/inscription_pharmacien.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
    #[Route('/admin/modifinfophrm/{id}', name: 'app_modificationinfopharma')]

    
  
    public function edit(Request $request, EntityManagerInterface $entityManager, $id): Response
    {
        // Récupère la pharmacie par son ID
        $pharmacie = $entityManager->getRepository(Pharmacie::class)->find($id);

        // Vérifie si la pharmacie existe
        if (!$pharmacie) {
            throw $this->createNotFoundException('La pharmacie demandée n\'existe pas.');
        }

        // Traitement du formulaire lorsque soumis
        if ($request->isMethod('POST')) {
            $name = $request->request->get('name');
            $email = $request->request->get('email');
            $address = $request->request->get('address');
            $phone = $request->request->get('phone');
            $role = $request->request->get('role');
            $password = $request->request->get('password');
            $confirmPassword = $request->request->get('confirm_password');

            // Validation des mots de passe
            if ($password !== $confirmPassword) {
                $this->addFlash('error', 'Les mots de passe ne correspondent pas.');
                return $this->render('admin/modifierinfo_pharmacien.html.twig', [
                    'pharmacie' => $pharmacie,
                ]);
            }

            // Mise à jour des données de la pharmacie
            $pharmacie->setNomPharma($name);
            $pharmacie->setEmail($email);
            $pharmacie->setAddpharma($address);
            $pharmacie->setTel($phone);

            // Mise à jour du mot de passe si fourni
            if (!empty($password)) {
                // Encodage du mot de passe (utilisez un encodeur si vous en avez un)
                $encodedPassword = password_hash($password, PASSWORD_BCRYPT);
                $pharmacie->setMdp($encodedPassword);
            }

            // Sauvegarde les modifications dans la base de données
            $entityManager->flush();

            // Redirige vers la liste des pharmacies après la modification
            return $this->redirectToRoute('app_gerepharmacie');
        }

        // Affiche le formulaire de modification
        return $this->render('admin/modifierinfo_pharmacien.html.twig', [
            'pharmacie' => $pharmacie,
        ]);
    }
    #[Route('/admin/deletinfophrm/{id}', name: 'pharmacie_delete')]
   

    public function delete(Request $request, EntityManagerInterface $entityManager, $id): RedirectResponse
    {
        // Assurez-vous que l'utilisateur est authentifié et a le rôle requis

        $pharmacie = $entityManager->getRepository(Pharmacie::class)->find($id);

        if (!$pharmacie) {
            throw $this->createNotFoundException('La pharmacie demandée n\'existe pas.');
        }

        if ($this->isCsrfTokenValid('delete' . $id, $request->request->get('_token'))) {
            $entityManager->remove($pharmacie);
            $entityManager->flush();
            $this->addFlash('success', 'Pharmacie supprimée avec succès.');
        } else {
            $this->addFlash('error', 'Token CSRF invalide.');
        }

        return $this->redirectToRoute('app_gerepharmacie');
    }


    #[Route('/admin/listplannings', name: 'app_voirplanning')]
    public function listplannings(EntityManagerInterface $entityManager): Response
    {
        $planning = $entityManager->getRepository(PlanningGarde::class)->findAll();

        return $this->render('admin/voir_planning.html.twig', [
            'planning' => $planning,
        ]);
    }
    #[Route('/admine', name: 'pharmacie_dashboard')]
    public function indexs()
    {
        $pharmacie = $this->getUser(); // Récupère l'utilisateur connecté (le pharmacien)
        
        
        return $this->render('admin/pharmacien.html.twig', [
            'pharmacie' => $pharmacie,
        ]);
    }

    #[Route('/planning', name: 'app_planning')]
    public function planning(Request $request,EntityManagerInterface $entityManager): Response
    {
            

            // Vérification que les mots de passe correspondent
            $pharmacie = $entityManager->getRepository(Pharmacie::class)->find($request->request->get('pharmacy'));
          
            $planning = new PlanningGarde();
            $planning->setIdPharmacie($pharmacie);
            $planning->setDateDebut(new \DateTime($request->request->get('start_date')));
            $planning->setDateFin(new \DateTime($request->request->get('end_date')));


            $entityManager->persist($planning);
            $entityManager->flush();

            return $this->redirectToRoute('pharmacie_dashboard');
    }
 
    #[Route('/admin/inscriptordreph', name: 'app_inscripordre')]
    public function inscriptordreph(): Response
    {
        return $this->render('admin/inscription_ordrepharamacie.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
 
    #[Route('/admin/gererordr', name: 'app_gererordre')]
    public function gererordr(EntityManagerInterface $entityManager): Response
    {
        $ordrepharma = $entityManager->getRepository(UserOrdrePharma::class)->findAll();

        return $this->render('admin/gerer_ordreph.html.twig', [
            'ordrepharma' => $ordrepharma,
        ]);
    }
 
    #[Route('/admin/modifordr{id}', name: 'app_modifierordre')]
    public function modifordr(Request $request, EntityManagerInterface $entityManager,$id): Response
    {
        $ordre_pharmacie = $entityManager->getRepository(UserOrdrePharma::class)->find($id);

        // Vérifie si la ordre_pharmacie existe
        if (!$ordre_pharmacie) {
            throw $this->createNotFoundException('La ordre_pharmacie demandée n\'existe pas.');
        }

        // Traitement du formulaire lorsque soumis
        if ($request->isMethod('POST')) {
            $name = $request->request->get('name');
            $email = $request->request->get('email');
            $phone = $request->request->get('phone');
            $password = $request->request->get('password');
            $confirmPassword = $request->request->get('confirm_password');

            // Validation des mots de passe
            if ($password !== $confirmPassword) {
                $this->addFlash('error', 'Les mots de passe ne correspondent pas.');
                return $this->render('admin/modifierordreph.html.twig', [
                    'ordre_pharmacie' => $ordre_pharmacie,
                ]);
            }

            // Mise à jour des données de la ordre_pharmacie
            $ordre_pharmacie->setUsername($name);
            $ordre_pharmacie->setEmail($email);
            $ordre_pharmacie->setTel($phone);

            // Mise à jour du mot de passe si fourni
            if (!empty($password)) {
                // Encodage du mot de passe (utilisez un encodeur si vous en avez un)
                $encodedPassword = password_hash($password, PASSWORD_BCRYPT);
                $ordre_pharmacie->setPassword($encodedPassword);
            }

            // Sauvegarde les modifications dans la base de données
            $entityManager->flush();

            // Redirige vers la liste des ordre_pharmacies après la modification
            return $this->redirectToRoute('app_gererordre');
        }
        return $this->render('admin/modifierordreph.html.twig', [
            'ordre_pharmacie' => $ordre_pharmacie,
        ]);
    }
 
    #[Route('/admin/supordrephrm/{id}', name: 'ordrepharma_delete')]
   

    public function supprimer(Request $request, EntityManagerInterface $entityManager, $id): RedirectResponse
    {
        // Assurez-vous que l'utilisateur est authentifié et a le rôle requis

        $ordrepharma = $entityManager->getRepository(UserOrdrePharma::class)->find($id);

        if (!$ordrepharma) {
            throw $this->createNotFoundException('La ordrepharma demandée n\'existe pas.');
        }

        if ($this->isCsrfTokenValid('delete' . $id, $request->request->get('_token'))) {
            $entityManager->remove($ordrepharma);
            $entityManager->flush();
            $this->addFlash('success', 'ordrepharma supprimée avec succès.');
        } else {
            $this->addFlash('error', 'Token CSRF invalide.');
        }

        return $this->redirectToRoute('app_gererordre');
    }
    #[Route('/admin/modifnoitif', name: 'app_modifiernotif')]
    public function modifnoitif(): Response
    {
        return $this->render('admin/modification_notification.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
 
    
}
