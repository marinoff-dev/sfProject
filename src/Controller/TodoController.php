<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TodoController extends AbstractController
{
    #[Route('/todo', name: 'app_todo')]
    public function index(Request $request): Response
    {
        $session = $request->getSession();
        if (!$session->has('todos')) {
            $todos = [
                'achat'=>'acheter clé USB',
                'cours'=>'Finaliser mon cours',
                'correction'=>'corriger mes examens'
            ];
            $session->set('todos', $todos);
            $this->addFlash('info', "La liste des todos vient d'être initialisée");
        }

        return $this->render('todo/index.html.twig');
    }

    #[Route('/todo/add/{name}/{content}', name: 'todo.add')]
    public function addTodo(Request $request, $name, $content) 
    {
        $session = $request->getSession();
        // Vérifier si j'ai mon tableau de todo dans la section
        if ($session->has('todos')) {
            // Si oui
            // Vérifier si on a déjà un todo avec le même name
            $todos = $session->get('todos');
            if (isset($todos[$name])) 
            {
                // Si oui afficher une erreur
                $this->addFlash('error', "Le todo d'id $name existe déjà dans la liste");
            } else {
                // Si non ajouter le todo et afficher un message de succès
                $todos[$name] = $content;
                $session->set('todos', $todos);
                $this->addFlash('success', "Le todo d'id $name a été ajouté avec succès");
            }
            
           
        } else {
            // Si non, afficher une erreur et rediriger vers le contrôleur index
            $this->addFlash('error', "La liste des todos n'est pas encore initialisée");
            
        }
        return $this->redirectToRoute('app_todo');

            
    }
}
