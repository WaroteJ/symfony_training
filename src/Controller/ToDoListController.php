<?php


namespace App\Controller;
use App\Entity\Todolist;
use App\Entity\Task;

use App\Entity\User;
use mysql_xdevapi\Exception;
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
            ->findLists($user->getId());

        return $this->render('todolist\showlists.html.twig',[
            'lists'=>$lists,
        ]);
    }

    /**
     * @Route("/toDoList/ajaxAddList",name="_app_addList",methods={"POST"})
     */
    public function AddList(Request $request, UserInterface $user){
        $response=null;
        try {
            $entityManager = $this->getDoctrine()->getManager();
            $listName = $request->request->get('listName');

            $list = new Todolist();

            $userId = $this->getDoctrine()
                ->getRepository(User::class)
                ->findOneBy(['id' => $user->getId()]);

            $list->setUser($userId);
            $list->setName($listName);

            $entityManager->persist($list);
            $entityManager->flush();

            $response = new Response(json_encode(array(
                'listId' => $list->getId(),
                'response' => "200"
            )));
        } catch (\Exception $e){
            $response = new Response(json_encode(array(
                'response' => "404"
            )));
        }
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/toDoList/ajaxRemoveList",name="_app_removeList",methods={"PUT"})
     */
    public function RemoveList(Request $request, UserInterface $user){
        try {
            $entityManager = $this->getDoctrine()->getManager();

            $listId = $request->request->get('listId');
            $list = $entityManager->getRepository(Todolist::class)->find($listId);
            if ($list->getUser()->getId() == $user->getId()) {
                $list->setDeleted(true);
                $entityManager->flush();
            }
            $response = new Response(json_encode(array(
                'response' => "200"
            )));
        }catch (\Exception $e){
            $response = new Response(json_encode(array(
                'response' => "404"
            )));
        }
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }




    // A list display and its ajax requests
    /**
     * @Route("/toDoList/{id}",name="app_toDoList")
     */
    public function toDoList($id, UserInterface $user){
        $tasks=$this->getDoctrine()
            ->getRepository(task::class)
            ->findTasks($id);
        $list=$this->getDoctrine()
            ->getRepository(todolist::class)
            ->findCurrentList($id,$user->getId());
        if(!$list){
            throw  $this->createNotFoundException('Cette liste n\'existe pas ou ne vous appartient pas');
        }
        $listName=$list->getName();
        return $this->render('todolist\list.html.twig',[
            'tasks'=>$tasks,
            'listName'=>$listName,
        ]);
    }
    /**
     * @Route("/toDoList/{id}/ajaxP",name="_ajax_post",methods={"POST"})
     */
    public function AddTask($id, Request $request){
        try {
            $entityManager = $this->getDoctrine()->getManager();
            $text = $request->request->get('text');

            $task = new Task();
            $todolist = $this->getDoctrine()
                ->getRepository(todolist::class)
                ->findOneBy(['id' => $id]);

            $task->setContent($text);
            $task->setTodolist($todolist);
            $entityManager->persist($task);
            $entityManager->flush();

            $task->setOrdre($task->getId());
            $entityManager->persist($task);
            $entityManager->flush();

            $response = new Response(json_encode(array(
                'response'=>'200',
                'taskId' => $task->getId()
            )));
        }catch (Exception $e){
            $response = new Response(json_encode(array(
                'response'=>'404'
            )));
        }
        $response->headers->set('Content-Type','application/json');
        return $response;
    }
    /**
     * @Route("/toDoList/{id}/ajaxD",name="_ajax_del",methods={"PUT"})
     */
    public function DelTask($id, Request $request, UserInterface $user){
        try{
            $entityManager = $this->getDoctrine()->getManager();
            $taskId=$request->request->get('taskId');
            $task=$entityManager->getRepository(Task::class)->find($taskId);
            if($task->getTodolist()->getUser()->getId()==$user->getId()) {
                $task->setDeleted(true);
                $entityManager->flush();
            }
            $response = new Response(json_encode(array(
                'response'=>'200',
            )));
        }catch (Exception $e){
            $response = new Response(json_encode(array(
                'response'=>'404'
            )));
        }
        $response->headers->set('Content-Type','application/json');
        return $response;
    }
    /**
     * @Route("/toDoList/{id}/ajaxPutChecked",name="_ajax_check",methods={"PUT"})
     */
    public function CheckTask($id, Request $request, UserInterface $user){
        try{
            $entityManager = $this->getDoctrine()->getManager();

            $taskId=$request->request->get('taskId');
            $taskState=$request->request->get('taskState');
            $task=$entityManager->getRepository(Task::class)->find($taskId);
            if($task->getTodolist()->getUser()->getId()==$user->getId()) {
                $task->setChecked($taskState);
                $entityManager->flush();
            }
            $response = new Response(json_encode(array(
                'response'=>'200',
            )));
        }catch (Exception $e){
            $response = new Response(json_encode(array(
            'response'=>'404'
            )));
        }
        $response->headers->set('Content-Type','application/json');
        return $response;
    }

    /**
     * @Route("/toDoList/{id}/ajaxOrderUpdate",name="_ajax_orderUpdate",methods={"PUT"})
     */
    public function OrderTask($id, Request $request, UserInterface $user){
        try{
            $entityManager = $this->getDoctrine()->getManager();
            $firstTaskId=$request->request->get('firstTaskId');
            $secondTaskId=$request->request->get('secondTaskId');
            $firstTaskOrder=$request->request->get('firstTaskOrder');
            $secondTaskOrder=$request->request->get('secondTaskOrder');

            $firstTask=$entityManager->getRepository(Task::class)->findOneBy(['id'=>$firstTaskId]);
            $secondTask=$entityManager->getRepository(Task::class)->findOneBy(['id'=>$secondTaskId]);

            if(($firstTask->getTodolist()->getUser()->getId()==$user->getId())
                && ($secondTask->getTodolist()->getUser()->getId()==$user->getId())) {
                    $firstTask->setOrdre($secondTaskOrder);
                    $secondTask->setOrdre($firstTaskOrder);
                    $entityManager->flush();
            }
            $response = new Response(json_encode(array(
                'response'=>'200',
            )));
        }catch (Exception $e){
            $response = new Response(json_encode(array(
            'response'=>'404'
        )));
        }
        $response->headers->set('Content-Type','application/json');
        return $response;
    }
}