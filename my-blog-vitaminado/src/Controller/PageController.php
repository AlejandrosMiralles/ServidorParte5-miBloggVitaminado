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

        return $this->render('pages/fullWidth.html.twig', [
            'controller_name' => 'PageController',
            'posts' => $posts ,
            'postForm' => $postForm->createView()
        ]);
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

    #[Route('page/singlePost/{postId}', name: 'singlePost')]
    public function singlePost(ManagerRegistry $doctrine, Request $request, String $postId): Response{
        $registryPost = $doctrine->getRepository(Post::class);
        $post = $registryPost->findOneBy(["id"=>$postId]);

        $registryComment = $doctrine->getRepository(Comment::class);
        $comments = $registryComment->findBy(["postCommented"=>$post->getId()]);

        $comment = new Comment();
        $commentForm = $this->createForm(CommentFormType::class, $comment);
        $commentForm->handleRequest($request);

        //Submiting commentForm's data
        if ($commentForm->isSubmitted() && $commentForm->isValid() ) {

            $comment = $commentForm->getData();
            $comment->setUser($this->getUser());
            $comment->setPostCommented($post);
            $entityManager = $doctrine->getManager();    
            $entityManager->persist($comment);
            $entityManager->flush();
        }

        return $this->render('pages/singlePost.html.twig', [
            'controller_name' => 'PageController',
            'post' => $post,
            'comments' => $comments,
            'commentForm' => $commentForm->createView()
        ]);
    }

    #[Route('/page/singlePost/{id}/like', name: 'singlePost_like')]
    public function singlePostLike(ManagerRegistry $doctrine, $id): Response{
        $repository = $doctrine->getRepository(Post::class);
        $post = $repository->findOneBy(["id"=>$id]);
        if ($post){
            $post->increaseNumLikes();
            $entityManager = $doctrine->getManager();    
            $entityManager->persist($post);
            $entityManager->flush();
        }
        return $this->redirectToRoute('singlePost', ['postId'=>$id]);

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
