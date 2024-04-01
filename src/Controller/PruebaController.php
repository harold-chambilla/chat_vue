<?php

namespace App\Controller;

use App\Entity\Producto;
use Lcobucci\JWT\Token\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use App\Repository\ProductoRepository;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Doctrine\ORM\EntityManagerInterface;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Encoding\ChainedFormatter;
use Lcobucci\JWT\Signer\Key\InMemory;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PruebaController extends AbstractController
{
    #[Route('/prueba', name: 'app_prueba')]
    public function index(): Response
    {       
        $email = $this->getUser()->getEmail();
        $token = (new Builder(new JoseEncoder, ChainedFormatter::default()))
            ->withClaim('mercure', ['suscribe' => [sprintf("/%s", $email)]])
            ->getToken(
                new Sha256(),
                InMemory::plainText($this->getParameter("mercure_secret_key"))
            )
        ;
        // print_r($token);

        $encoder = new JoseEncoder();
        $tokenString = $encoder->jsonEncode($token);
        $response = $this->render('prueba/index.html.twig', [
            'controller_name' => 'PruebaController',
        ]);

        $response->headers->setCookie(
            new Cookie(
                'mercureAuthorizacion',
                $token->toString(),
                (new \DateTime())
                ->add(new \DateInterval('PT2H')),
                '/.well-known/mercure',
                null,
                false,
                true,
                false,
                'strict'
            )
        );
        return $response;
    }

    #[Route('/prueba-list', name: 'app_prueba_list', methods:['GET'])]
    public function vueList(ProductoRepository $productoRepository): JsonResponse
    {
        // $datos = ["raroooo","rata", "Excelente"]; 
        $datos = $productoRepository->findAll();
        return $this->json($datos);
    }

    #[Route('/prueba-list/create', name: 'app_prueba_create', methods:['POST'])]
    public function vueCreate(EntityManagerInterface $entityManager, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        
        $producto = new Producto();   

        // Necesario para testear API en POSTMAN
        // $producto->setNombre($request->request->get('nombre'));
        // $producto->setCategoria($request->request->get('categoria'));
        // $producto->setCantidad($request->request->get('cantidad'));
        // $producto->setPrecio($request->request->get('precio'));

        $producto->setNombre($data['nombre']);
        $producto->setCategoria($data['categoria']);
        $producto->setCantidad($data['cantidad']);
        $producto->setPrecio($data['precio']);
        $entityManager->persist($producto);
        $entityManager->flush();

        return $this->json($producto);
    }

    #[Route('/prueba-list/{id}', name: 'app_prueba_show', methods:['GET'])]
    public function vueShow(int $id, ProductoRepository $productoRepository): JsonResponse
    {
        // $datos = ["raroooo","rata", "Excelente"]; 
        // $datos = [
        //     ['id' => 1, 'name' => 'Harold'],
        //     ['id' => 2, 'name' => 'Hans']
        // ];
        $datos = $productoRepository->find(['id' => $id]);
        return $this->json($datos);
    }
    
    #[Route('/prueba-list/edit/{id}', name: 'app_prueba_edit', methods:['PUT', 'PATCH'])]
    public function vueEdit(Producto $producto, ProductoRepository $productoRepository, Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        // $datos = ["raroooo","rata", "Excelente"]; 
        // $datos = [
        //     ['id' => 1, 'name' => 'Harold'],
        //     ['id' => 2, 'name' => 'Hans']
        // ];
        $data = json_decode($request->getContent(), true);

        // $producto = $productoRepository->find(['id' => $id]);

        // $producto->setNombre($request->request->get('nombre'));
        // $producto->setCategoria($request->request->get('categoria'));
        // $producto->setCantidad($request->request->get('cantidad'));
        // $producto->setPrecio($request->request->get('precio'));

        $producto->setNombre($data['nombre']);
        $producto->setCategoria($data['categoria']);
        $producto->setCantidad($data['cantidad']);
        $producto->setPrecio($data['precio']);

        $entityManager->flush();

        return $this->json($producto);
    }

    #[Route('/prueba-list/delete/{id}', name: 'app_prueba_delete', methods:['DELETE'])]
    public function vueDelete(Producto $producto, ProductoRepository $productoRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        // $datos = $productoRepository->find(['id' => $id]);
        $entityManager->remove($producto);
        $entityManager->flush();
        return $this->json($producto);
    }

    #[Route('/prueba-list/{categoria}/cat', name: 'app_prueba_cat', methods:['GET'])]
    public function vueCat($categoria, ProductoRepository $productoRepository): JsonResponse
    {
        $datos = $productoRepository->findBy(['categoria' => $categoria]);
        return $this->json($datos);
    }
}
