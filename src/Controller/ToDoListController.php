<?php


namespace App\Controller;
use App\Entity\Todolist;
use App\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class ToDoListController extends AbstractController
{
    /**
     * @Route("/",name="app_homepage")
     */
    public function homepage(){
        return $this->render('base.html.twig');
    }
    /**
     * @Route("/toDoList",name="app_showLists")
     */
    public function showLists(UserInterface $user){
        $lists=$this->getDoctrine()
            ->getRepository(todolist::class)
            ->findBy(['id_user'=>$user->getId()]);

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
     * @Route("/toDoList/{id}",name="app_toDoList")
     */
    public function toDoList($id){
        $tasks=$this->getDoctrine()
            ->getRepository(task::class)
            ->findBy(['id_todolist'=>$id]);
        $list=$this->getDoctrine()
            ->getRepository(todolist::class)
            ->findOneBy(['id'=>$id]);
        $name=$list->getName();
        return $this->render('todolist\list.html.twig',[
           'tasks'=>$tasks,
            'name'=>$name,
        ]);
    }
}