<?php

    namespace App\Controller;

    use App\Entity\Post;
    use App\Entity\User;
    use App\Services\CartService;
    use Doctrine\Persistence\ManagerRegistry;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\Routing\Annotation\Route;

    #[Route(path:'/cart')]
    class CartController extends AbstractController{
        private $doctrine;
        private $repository;
        private $cart;
        //Le inyectamos CartService como una dependencia
        public  function __construct(ManagerRegistry $doctrine, CartService $cart){
            $this->doctrine = $doctrine;
            $this->repository = $doctrine->getRepository(Post::class);
            $this->cart = $cart;
        }

        #[Route('/post/add/{id}', name: 'cart_add', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
        public function cart_add(int $id): Response{
            $post = $this->repository->find($id);
            if (!$post)
                return new JsonResponse("[]", Response::HTTP_NOT_FOUND);

            $this->cart->add($id, 1);
            
            $data = [
                "id"=> $post->getId(),
                "content" => $post->getContent(),
                "publishedAt" => $post->getPublishedAt(),
                "author" => $post->getAuthor()->getName(),
                "numLikes"=> $post->getNumLikes()
            ];

            return new JsonResponse($data, Response::HTTP_OK);

        }

        #[Route('/post/delete/{id}', name: 'cart_delete', methods: ['POST'], requirements: ['id' => '\d+'])]
        public function cart_delete(int $id, CartService $cart): Response{
            $post = $this->repository->find($id);
            if (!$post)
                return new JsonResponse("[]", Response::HTTP_NOT_FOUND);

            $cart->delete($id);
            
            $data = [
                "totalCart" => count($cart->getCart())
            ];

            return new JsonResponse($data, Response::HTTP_OK);
        }

        #[Route('/post/pay', name: 'cart_delete', methods: ['POST', 'GET'])]
        public function cart_pay(CartService $cart, ManagerRegistry $doctrine, Request $request): Response{
            
            $entityManager = $doctrine->getManager();  

            $posts = $cart->getCart();
            foreach ($posts as $id => $post) {
                $post->setAuthor($this->getUser());
                $entityManager->persist($post);
                $cart->delete($id);
            }

            $entityManager->flush();

            $data = [
                "totalCart" => count($cart->getCart())
            ];

            return new JsonResponse($data, Response::HTTP_OK);
        }

        #[Route('/show', name:'cart_show')]
        public function cartShow(): Response {
            $posts = $this->repository->getFromCart($this->cart);
            //hay que añadir la cantidad de cada producto
            $items = [];
            $totalCart = 0;
            $pricePerPost = 5;
            foreach($posts as $post){
                $item = [
                    "id"=> $post->getId(),
                    "content" => $post->getContent(),
                    "publishedAt" => $post->getPublishedAt(),
                    "author" => $post->getAuthor()->getName(),
                    "numLikes"=> $post->getNumLikes()
                ];

                $totalCart += $pricePerPost;
                $items[] = $item;
            }

            return $this->render('cart/index.html.twig', ['items' => $items, 'totalCart' => $totalCart]);
        }

        #[Route('/totalItems', name: 'cart_totalItems', methods: ['POST', 'GET'])]
        public function totalItems(CartService $cart): Response{
            $data = [
                "totalCart" => count($cart->getCart())
            ];

            return new JsonResponse($data, Response::HTTP_OK);

        }

        
    }
?>
