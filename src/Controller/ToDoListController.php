<?php


namespace App\Controller;
use App\Entity\Todolist;
use App\Entity\Task;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;


class ToDoListController extends AbstractController
{
    /**
     * @Route("/",name="app_homepage")
     */
    public function homepage(){
        return $this->render("base.html.twig");
    }

    // Lists display and ajax requests used on this pages
    /**
     * @Route("/toDoList",name="app_showLists")
     */
    public function showLists(UserInterface $user){
        $lists=$this->getDoctrine()
            ->getRepository(Todolist::class)
            ->findWhereNotDeleted($user->getId());


     /*   if(!$lists){
            throw $this->createNotFoundException(
                'No list found'
            );
        }*/
        return $this->render('todolist\showlists.html.twig',[
            'lists'=>$lists,
        ]);
    }

    /**
     * @Route("/toDoList/ajaxAddList",name="_app_addList",methods={"POST"})
     */
    public function ajaxAddList(Request $request, UserInterface $user){
        $entityManager = $this->getDoctrine()->getManager();

        $name=$request->request->get('name');

        $list=new Todolist();

        $userId=$this->getDoctrine()
            ->getRepository(User::class)
            ->findOneBy(['id'=>$user->getId()]);

        $list->setIdUser($userId);
        $list->setName($name);

        $entityManager->persist($list);
        $entityManager->flush();

        $response=new Response(json_encode(array(
            'id'=>$list->getId()
        )));
        $response->headers->set('Content-Type','application/json');
        return $response;
    }

    /**
     * @Route("/toDoList/ajaxRemoveList",name="_app_removeList",methods={"PUT"})
     */
    public function ajaxRemoveList(Request $request){
        $entityManager = $this->getDoctrine()->getManager();

        $id=$request->request->get('id');
        $list=$entityManager->getRepository(Todolist::class)->find($id);

        $list->setDeleted(true);
        $entityManager->flush();

        return new Response();
    }




    // A list display and its ajax requests
    /**
     * @Route("/toDoList/{id}",name="app_toDoList")
     */
    public function toDoList($id, UserInterface $user){
        $tasks=$this->getDoctrine()
            ->getRepository(task::class)
            ->findWhereNotDeleted($id);
        $list=$this->getDoctrine()
            ->getRepository(todolist::class)
            ->findOneByIdAndUser($id,$user->getId());
        if(!$list){
            throw  $this->createNotFoundException('Cette liste n\'existe pas ou ne vous appartient pas');
        }
        $name=$list->getName();
        return $this->render('todolist\list.html.twig',[
           'tasks'=>$tasks,
            'name'=>$name,
        ]);
    }
    /**
     * @Route("/toDoList/{id}/ajaxP",name="_ajax_post",methods={"POST"})
     */
    public function ajaxAddEvent($id, Request $request){
        $entityManager = $this->getDoctrine()->getManager();

        $text=$request->request->get('element');

        $task=new Task();
        $todolist=$this->getDoctrine()
            ->getRepository(todolist::class)
            ->findOneBy(['id'=>$id]);
        $id=$this->getDoctrine()
            ->getRepository(task::class)
            ->findLast();

        $task->setContent($text);
        $task->setChecked(false);
        $task->setDeleted(false);
        $task->setOrdre($id->getId()+1);
        $task->setIdTodolist($todolist);
        $entityManager->persist($task);
        $entityManager->flush();

        $response=new Response(json_encode(array(
            'id'=>$id->getId()+1
        )));
        $response->headers->set('Content-Type','application/json');
        return $response;
    }
    /**
     * @Route("/toDoList/{id}/ajaxD",name="_ajax_del",methods={"PUT"})
     */
    public function ajaxDel($id, Request $request){
        $entityManager = $this->getDoctrine()->getManager();

        $id_task=$request->request->get('element');
        $task=$entityManager->getRepository(Task::class)->find($id_task);

        $task->setDeleted(true);
        $entityManager->flush();

        return new Response();
    }
    /**
     * @Route("/toDoList/{id}/ajaxPutChecked",name="_ajax_check",methods={"PUT"})
     */
    public function ajaxCheck($id, Request $request){
        $entityManager = $this->getDoctrine()->getManager();

        $id_task=$request->request->get('element');
        $state_task=$request->request->get('state');
        $task=$entityManager->getRepository(Task::class)->find($id_task);

        $task->setChecked($state_task);
        $entityManager->flush();

        return new Response();
    }

    /**
     * @Route("/toDoList/{id}/ajaxOrderUpdate",name="_ajax_orderUpdate",methods={"PUT"})
     */
    public function ajaxOrder($id, Request $request){
        $entityManager = $this->getDoctrine()->getManager();

        $firstId=$request->request->get('firstId');
        $secondId=$request->request->get('secondId');
        $firstOrder=$request->request->get('firstOrder');
        $secondOrder=$request->request->get('secondOrder');
        $task=$entityManager->getRepository(Task::class)->findOneBy(['id'=>$firstId]);
        $task->setOrdre($secondOrder);
        $secondTask=$entityManager->getRepository(Task::class)->findOneBy(['id'=>$secondId]);
        $secondTask->setOrdre($firstOrder);
        $entityManager->flush();

        return new Response();
    }
}