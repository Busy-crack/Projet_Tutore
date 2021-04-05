<?php

namespace App\Controller;

use App\Entity\Liste;
use App\Entity\Task;
use App\Form\ListeFormType;
use App\Form\TaskType;
use App\Repository\ListeRepository;
use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/task")
 */
class TaskController extends AbstractController
{
    /**
     * @Route("/", name="task_index", methods={"GET","POST"})
     */

    public function index(TaskRepository $taskRepository, ListeRepository $listeRepository, Request $request): Response
    {
        $task = new Task();
        $list = new Liste();
        $form = $this->createForm(TaskType::class, $task);
        $formList = $this->createForm(ListeFormType::class, $list);
        $entityManager = $this->getDoctrine()->getManager();

        if($request->request->has("newTask")){
            $form->handleRequest($request);
            $entityManager->persist($task);
            $entityManager->flush();
        };
        if($request->request->has("newListe")){
            $formList->handleRequest($request);
            $entityManager->persist($list);
            $entityManager->flush();
        };

        return $this->render('task/index.html.twig', [
            'tasks' => $taskRepository->findAll(),
            'lists' => $listeRepository -> findAll(),
            'task' => $task,
            'form' => $form->createView(),
            'form2' => $formList->createView()
        ]);
    }

    /**
     * @Route("/new", name="task_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($task);
            $entityManager->flush();

            return $this->redirectToRoute('task_index');
        }

        return $this->render('task/new.html.twig', [
            'task' => $task,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="task_show", methods={"GET"})
     */
    public function show(Task $task): Response
    {
        return $this->render('task/show.html.twig', [
            'task' => $task,
        ]);
    }

    /**
     * @Route("/{id}", name="task_show", methods={"GET"})
     */
    public function showListe(Liste $liste): Response
    {
        return $this->render('task/show.html.twig', [
            'task' => $liste,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="task_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Task $task): Response
    {
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('task_index');
        }

        return $this->render('task/edit.html.twig', [
            'task' => $task,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="task_delete", methods={"POST"})
     */
    public function delete(Request $request, Task $task): Response
    {
        if ($this->isCsrfTokenValid('delete'.$task->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($task);
            $entityManager->flush();
        }

        return $this->redirectToRoute('task_index');
    }
}
