<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\Image;

class PageController extends AbstractController
{
    #[Route('/page', name: 'app_page')]
    public function index(): Response
    {
        return $this->render('pages/index.html.twig', [
            'controller_name' => 'PageController',
        ]);
    }

    #[Route('/page/basicGrid', name: 'basicGrid')]
    public function basicGrid(): Response{
        return $this->render('pages/basicGrid.html.twig', [
            'controller_name' => 'PageController',
        ]);
    }

    #[Route('/page/fontIcons', name: 'fontIcons')]
    public function fontIcons(): Response{
        return $this->render('pages/fontIcons.html.twig', [
            'controller_name' => 'PageController',
        ]);
    }

    #[Route('/page/fullWidth', name: 'fullWidth')]
    public function fullWidth(): Response{
        return $this->render('pages/fullWidth.html.twig', [
            'controller_name' => 'PageController',
        ]);
    }

    #[Route('/page/gallery', name: 'gallery')]
    public function gallery(ManagerRegistry $doctrine, Request $request): Response{
        $repository = $doctrine->getRepository(Image::class);
        $images = $repository->findAll();

        return $this->render('pages/gallery.html.twig', [
            'controller_name' => 'PageController',
            'images' => $images
        ]);
    }

    #[Route('/page/sidebarLeft', name: 'sidebarLeft')]
    public function sidebarLeft(): Response{
        return $this->render('pages/sidebarLeft.html.twig', [
            'controller_name' => 'PageController',
        ]);
    }

    #[Route('/page/sidebarRight', name: 'sidebarRight')]
    public function sidebarRight(): Response{
        return $this->render('pages/sidebarRight.html.twig', [
            'controller_name' => 'PageController',
        ]);
    }
}
