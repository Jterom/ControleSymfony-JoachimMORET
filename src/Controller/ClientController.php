<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

class ClientController extends AbstractController
{
    /**
     * @Route("/client", name="client")
     */
    public function index()
    {
        $repository = $this->getDoctrine()->getRepository(Client::class);

        $clients = $repository->findAll();

        return $this->render('client/index.html.twig', [
            "clients" => $clients,
        ]);
    }
    /**
     * @Route("/client/ajouter", name="ajouter")
     */
    public function ajouter(Request $request)
    {
        $client = new Client();

        $formulaire = $this->createForm(ClientType::class, $client);

        $formulaire->handleRequest($request);

        if ($formulaire->isSubmitted() && $formulaire->isValid())
        {
            $em = $this->getDoctrine()->getManager();

            $em->persist($client);

            $em->flush();

            return $this->redirectToRoute("client");
        }

        return $this->render('client/formulaire.html.twig', [
            "formulaire"=>$formulaire->createView(),
            "h1"=>"Ajouter un client ",
        ]);
    }
    /**
     * @Route("/client/modifier/{id}", name="modifier")
     */
    public function modifier(int $id, Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(Client::class);
        $client = $repository->find($id);

        //creation du formulaire
        $formulaire = $this->createForm(ClientType::class, $client);

        $formulaire->handleRequest($request);

        if ($formulaire->isSubmitted() && $formulaire->isValid())
        {
            $em = $this->getDoctrine()->getManager();

            $em->persist($client);

            $em->flush();

            return $this->redirectToRoute("client");
        }

        return $this->render('client/formulaire.html.twig', [
            "formulaire"=>$formulaire->createView(),
            "h1"=>"Modification du client <i>".$client->GetNom()."</i>",
        ]);
    }
    /**
     * @Route("/client/supprimer/{id}", name="supprimer")
     */
    public function delete(Request $request, $id)
    {

        $repository = $this->getDoctrine()->getRepository(Client::class);
        $client = $repository->find($id);

        $formulaire = $this->createFormBuilder()
            ->add("submit", SubmitType::class, ["label" =>"OK", "attr"=>["class"=>"btn btn-success"]])
            ->getForm();

        $formulaire->handleRequest($request);

        if($formulaire->isSubmitted() && $formulaire->isValid())
        {
            $em = $this->getDoctrine()->getManager();

            $em->remove($client);

            $em->flush();

            return $this->redirectToRoute("client");
        }

        return $this->render('client/formulaire.html.twig', [
            'controller_name' => 'ClientController',
            'formulaire'=> $formulaire->createView(),
            "h1" => "Supprimer le client <i>".$client->getNom()."</i>"
        ]);
    }

}
