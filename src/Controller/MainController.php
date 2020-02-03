<?php

namespace App\Controller;

use App\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main")
     */
    public function index()
    {
        $tasks = $this->getDoctrine()->getRepository(Task::class)->findBy([],['id'=>'DESC']);
        return $this->render('index.html.twig', ['tasks'=>$tasks]);
    }




     /**
     * @Route("/create", name="create_task", methods={"POST"})
     */
    public function create(Request $request)
    {
        $title = trim($request->request->get('title'));
        if(empty($title)){
           return $this->redirectToRoute('main');
        }

        $entityManager = $this->getDoctrine()->getManager();

        $task = new Task;
        $task->setTitle($title);

        $entityManager->persist($task);
        $entityManager->flush();

        return $this->redirectToRoute('main');

    }


     /**
     * @Route("/update/{id}", name="update_task")
     */
    public function update($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $task = $entityManager->getRepository(Task::class)->find($id);

        $task->setStatus(! $task->getStatus());

        $entityManager->flush();

        return $this->redirectToRoute('main');
    }

      /**
     * @Route("/delete/{id}", name="delete_task")
     */
    public function delete(Task $id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($id);
        $entityManager->flush();
        return $this->redirectToRoute('main');

        
    }
}
