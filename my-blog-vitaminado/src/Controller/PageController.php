<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    #[Route('/pages/', name: 'app_page')]
    public function index(): Response
    {
        return $this->render('pages/index.html.twig', [
            'controller_name' => 'PageController',
        ]);
    }

    #[Route('/pages/basicGrid', name: 'basicGrid')]
    public function basicGrid(): Response{
        return $this->render('pages/basicGrid.html.twig', [
            'controller_name' => 'PageController',
        ]);
    }

    #[Route('/pages/fontIcon', name: 'fontIcon')]
    public function fontIcon(): Response{
        return $this->render('pages/fontIcon.html.twig', [
            'controller_name' => 'PageController',
        ]);
    }

    #[Route('/pages/fullWidth', name: 'fullWidth')]
    public function fullWidth(): Response{
        return $this->render('pages/fullWidth.html.twig', [
            'controller_name' => 'PageController',
        ]);
    }

    #[Route('/page/gallery', name: 'gallery')]
    public function gallery(): Response{
        return $this->render('pages/gallery.html.twig', [
            'controller_name' => 'PageController',
        ]);
    }

    #[Route('/pages/sidebarLeft', name: 'sidebarLeft')]
    public function sidebarLeft(): Response{
        return $this->render('pages/sidebarLeft.html.twig', [
            'controller_name' => 'PageController',
        ]);
    }

    #[Route('/pages/sidebarRigth', name: 'sidebarRigth')]
    public function sidebarRigth(): Response{
        return $this->render('pages/sidebarRigth.html.twig', [
            'controller_name' => 'PageController',
        ]);
    }
}
