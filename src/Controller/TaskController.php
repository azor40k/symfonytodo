<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskDoneType;
use App\Form\TaskType;
use App\Form\TaskUpdateType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class TaskController extends AbstractController
{
    /**
     * @Route("/task", name="task")
     */
    public function index(Request $request){

        $pdo = $this->getDoctrine()->getManager();
        $task = new Task();

        //Checkbox Done
        // $formd = $this->createForm(TaskDoneType::class, $task);
        // $formd-> handleRequest($request);
        // if($formd->isSubmitted() && $formd->isValid()){
        //     $pdo = $this->getDoctrine()->getManager();
        //     $pdo->persist($task);
        //     $pdo->flush();
    
        //     $this->addFlash('success', 'Progression modifié!');
        // }

        
        $form = $this->createForm(TaskType::class, $task);
        $form ->handleRequest($request);
        if($form->isSubmitted() && $form->isValid() ){
            $task->setDone(0);
            
            $pdo->persist($task);
            $pdo->flush();

            $this->addFlash('success', 'Tâche ajoutée!');
        }       

        $tasks = $pdo->getRepository(Task::class)->findAll();

        return $this->render('task/index.html.twig', [
            'tasks' => $tasks,
            // 'form_taskd_done' => $formd->createView(),   
            'form_task_new' => $form->createView(),                     
        ]);
    }
    /**
     * @Route("/task/delete/{id}", name="delete_task")
     */
    public function delete(Task $task=null){
        if($task != null){
            $pdo = $this->getDoctrine()->getManager();
            $pdo->remove($task);
            $pdo->flush();

            $this->addFlash('success', 'Tâche supprimée!');
        }
        else{$this->addFlash('danger', 'Tâche introuvable!');}

        return $this->redirectToRoute('task');
    }
    
    /**
     * @Route("/task/update/{id}", name="update_task")
     */
    public function update(Request $request, Task $task=null){

        if($task !=null){
        $form = $this->createForm(TaskUpdateType::class, $task);
        $form-> handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $pdo = $this->getDoctrine()->getManager();
            $pdo->persist($task);
            $pdo->flush();

            $this->addFlash('success', 'Tâche modifiée!');
        }

        return $this->render('task/task-update.html.twig', [
            'un_task' => $task,
            'form_task_update' => $form->createView(),
        ]);

        }
        else{
            return $this->redirectToRoute('task');
            $this->addFlash('danger', 'Tâche introuvable!');
        }
    }
    
}
