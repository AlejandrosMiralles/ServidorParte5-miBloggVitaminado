<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactFormType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

class ContactController extends AbstractController{

    #[Route('/feedback', name: 'app_contact')]
    public function contact(ManagerRegistry $doctrine, Request $request): Response{
        $contact = new Contact();
        $form = $this->createForm(ContactFormType::class, $contact);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $contacto = $form->getData();    
            $entityManager = $doctrine->getManager();    
            $entityManager->persist($contacto);
            $entityManager->flush();
            return $this->render('contact/succesSend.html.twig', [
                'formType' => "feedback"
            ]);
        }
        return $this->render('contact/contact.html.twig', array(
            'form' => $form->createView()    
        ));
    }
}
