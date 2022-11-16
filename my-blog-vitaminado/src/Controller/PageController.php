<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostFormType;
use App\Entity\Comment;
use App\Form\CommentFormType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\Image;
use phpDocumentor\Reflection\Types\Integer;

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
    public function fullWidth(ManagerRegistry $doctrine, Request $request): Response{
        $registry = $doctrine->getRepository(Post::class);
        $posts = $registry->findAll();

        $post = new Post();
        $postForm = $this->createForm(PostFormType::class, $post);
        $postForm->handleRequest($request);

        $comment = new Comment();
        $commentForm = $this->createForm(CommentFormType::class, $comment);
        $commentForm->handleRequest($request);

        return $this->render('pages/fullWidth.html.twig', [
            'controller_name' => 'PageController',
            'posts' => $posts ,
            'commentForm' => $commentForm->createView() ,
            'postForm' => $postForm->createView()
        ]);
    }

    #[Route('/page/fullWidth/{postCommentedId}', name: 'fullWidthCheckForms')]
    public function fullWidthCheckForms(ManagerRegistry $doctrine, Request $request, String $postCommentedId): Response{
        
        $post = new Post();
        $postForm = $this->createForm(PostFormType::class, $post);
        $postForm->handleRequest($request);

        $comment = new Comment();
        $commentForm = $this->createForm(CommentFormType::class, $comment);
        $commentForm->handleRequest($request);

        //Submiting postForm's data
        if ($postForm->isSubmitted() && $postForm->isValid()) {
            $post = $postForm->getData();
            $post->setAuthor($this->getUser());
            $entityManager = $doctrine->getManager();    
            $entityManager->persist($post);
            $entityManager->flush();
        }

        //Submiting commentForm's data
        if ($commentForm->isSubmitted() && $commentForm->isValid() && preg_match("/^\d+$/", $postCommentedId)) {
            $registry = $doctrine->getRepository(Post::class);

            $comment = $commentForm->getData();
            $comment->setUser($this->getUser());
            $comment->setPostCommented($registry->findOneBy(["id"=>$postCommentedId]));
            $entityManager = $doctrine->getManager();    
            $entityManager->persist($comment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('fullWidth');
    }

    #[Route('/page/fullWidth/{id}/like', name: 'fullWidth_postLike')]
    public function like(ManagerRegistry $doctrine, $id): Response{
        $repository = $doctrine->getRepository(Post::class);
        $post = $repository->findOneBy(["id"=>$id]);
        if ($post){
            $post->increaseNumLikes();
            $entityManager = $doctrine->getManager();    
            $entityManager->persist($post);
            $entityManager->flush();
        }
        return $this->redirectToRoute('fullWidth');

    }

    #[Route('/page/gallery/{page}', name: 'gallery')]
    public function gallery(ManagerRegistry $doctrine, Request $request, int $page=1): Response{
        $repository = $doctrine->getRepository(Image::class);
        $images = $repository->findAllPaginated($page);

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
