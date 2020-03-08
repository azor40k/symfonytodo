<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Entity\Task;
use App\Form\TaskDoneType;
use App\Form\UtilisateurType;
use App\Form\TaskUpdateType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class UtilisateurController extends AbstractController
{
    /**
     * @Route("/", name="utilisateur")
     */
    public function index(Request $request){

        $pdo = $this->getDoctrine()->getManager();

        $utilisateur = new Utilisateur();
        //$nbr = new Task();
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form ->handleRequest($request);
        if($form->isSubmitted() && $form->isValid() ){
            $utilisateur->setDate(new \DateTime);

            $pdo->persist($utilisateur);
            $pdo->flush();

            $this->addFlash('success', 'Utilisateur ajouté!');
        }

        $utilisateurs = $pdo->getRepository(Utilisateur::class)->findAll();
        
        //$nbr = $pdo->getRepository(Task::class)->findBy(['utilisateur' => $nbr]);

        return $this->render('utilisateur/index.html.twig', [
            'utilisateurs' => $utilisateurs,
            //'nbr' => $nbr,
            'form_utilisateur_new' => $form->createView(),            
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete_utilisateur")
     */
    public function delete(Utilisateur $utilisateur=null){
        if($utilisateur != null){
            $pdo = $this->getDoctrine()->getManager();
            $pdo->remove($utilisateur);
            $pdo->flush();

            $this->addFlash('success', 'Utilisateur supprimé!');
        }
        else{$this->addFlash('danger', 'Utilisateur introuvable!');}

        return $this->redirectToRoute('utilisateur');
    }
    
    /**
     * @Route("/update/{id}", name="update_utilisateur")
     */
    public function update(Request $request, Utilisateur $utilisateur=null){

        if($utilisateur !=null){
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form-> handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $pdo = $this->getDoctrine()->getManager();
            $pdo->persist($utilisateur);
            $pdo->flush();

            $this->addFlash('success', 'Utilisateur modifié!');
        }

        return $this->render('utilisateur/utilisateur-update.html.twig', [
            'un_utilisateur' => $utilisateur,
            'form_utilisateur_update' => $form->createView(),
        ]);

        }
        else{
            return $this->redirectToRoute('utilisateur');
            $this->addFlash('danger', 'Utilisateur introuvable!');
        }
    }

    /**
     * @Route("/usertask/{id}", name="task_utilisateur")
     */
    public function usertasks(Request $request, Utilisateur $utilisateur=null){

        if($utilisateur !=null){
            $pdo = $this->getDoctrine()->getManager();

            $usertasks = $pdo->getRepository(Task::class)->findBy(['utilisateur' => $utilisateur]);

            return $this->render('utilisateur/utilisateur-task.html.twig', [
                'un_utilisateur' => $utilisateur,
                'usertasks' => $usertasks,
        ]);
        }
        else{
            return $this->redirectToRoute('utilisateur');
            $this->addFlash('danger', 'Utilisateur introuvable!');
        }
    }

    /**
     * @Route("/task/delete/{id}", name="delete_task")
     */
    public function usertaskdelete(Task $task=null){
        if($task != null){
            $pdo = $this->getDoctrine()->getManager();
            $pdo->remove($task);
            $pdo->flush();

            $this->addFlash('success', 'Tâche supprimée!');
        }
        else{$this->addFlash('danger', 'Tâche introuvable!');}

        return $this->redirectToRoute('utilisateur');
    }
    
    /**
     * @Route("/usertasks/update/{id}", name="update_usertask")
     */
    public function usertasksupdate(Request $request, Task $task=null){

        if($task !=null){
        $form = $this->createForm(TaskUpdateType::class, $task);
        $form-> handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $pdo = $this->getDoctrine()->getManager();
            $pdo->persist($task);
            $pdo->flush();

            $this->addFlash('success', 'Tâche modifiée!');
        }

        return $this->render('/task/task-update.html.twig', [
            'un_task' => $task,
            'form_task_update' => $form->createView(),
        ]);

        }
    }
}
