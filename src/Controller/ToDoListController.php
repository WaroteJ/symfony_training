<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
    public function showLists(){

    }
    /**
     * @Route("/toDoList/{id}",name="app_toDoList")
     */
    public function toDoList($id){

    }
}