<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Tag;
use App\Form\ContactoFormType;
use App\Repository\PostRepository;
use App\Repository\TagRepository;
use App\Service\Configuracion\Configuracion;
use App\Service\Configuracion\Oficina;
use App\Service\Mailer;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PtaController extends AbstractController
{
    /**
     * @Route("/", name="pta_index", host="%empresa.PTA.host%")
     */
    public function index()
    {
        return $this->render('pta/index.html.twig', [
            'controller_name' => 'PtaController',
        ]);
    }

    /**
     * @Route("/nosotros", name="pta_nosotros", host="%empresa.PTA.host%")
     * @return Response
     */
    public function nosotros()
    {
        return $this->render('pta/nosotros.html.twig');
    }

    /**
     * @Route("/servicios", name="pta_servicios", host="%empresa.PTA.host%")
     */
    public function servicios()
    {
        return $this->render('pta/servicios.html.twig');
    }

    /**
     * @Route("/noticias", name="pta_noticias", host="%empresa.PTA.host%")
     */
    public function noticias(PostRepository $postRepo, TagRepository $tagRepo, PaginatorInterface $paginator, Request $request)
    {
        return $this->blogList($postRepo, $tagRepo, $paginator, $request);
    }

    /**
     * @Route("/noticias/categoria/{slug}", name="pta_noticias_categoria", host="%empresa.PTA.host%")
     */
    public function noticiasCategoria(PostRepository $postRepo, TagRepository $tagRepo, PaginatorInterface $paginator, Request $request, Tag $tag)
    {
        return $this->blogList($postRepo, $tagRepo, $paginator, $request, $tag);
    }

    private function blogList(PostRepository $postRepo, TagRepository $tagRepo, PaginatorInterface $paginator, Request $request, ?Tag $tag = null)
    {
        $qb = $postRepo->searchQueryBuilder($tag);

        $pagination = $paginator->paginate($qb, $request->get('page', 1), 5);

        return $this->render('pta/blog/list.html.twig', [
            'pagination' => $pagination,
            'tagSelected' => $tag,
            'tags' => $tagRepo->findAllOrderBySize()
        ]);
    }

    /**
     * @Route("/noticias/{slug}", name="pta_noticia", host="%empresa.PTA.host%")
     */
    public function noticia(Post $post, PostRepository $repository, TagRepository $tagRepo)
    {
        $showImage = !in_array($post->getSlug(), [
            'tips-evitar-gripe',
            '7-tips-para-evitar-estres-laboral',
            'feliz-y-sano-en-el-trabajo',
            'tips-de-seguridad-y-salud-en-el-trabajo',
            'dieta-magica'
        ]);

        $recomendados = $repository->findRandom(3, $post);
        return $this->render('pta/blog/item.html.twig', [
            'post' => $post,
            'recomendados' => $recomendados,
            'showImage' => $showImage,
            'tags' => $tagRepo->findAllOrderBySize(),
            'tagSelected' => null
        ]);
    }

    /**
     * @Route("/contacto/{oficina}", name="pta_contacto", host="%empresa.PTA.host%", defaults={"oficina": "bogota"})
     */
    public function contacto(Oficina $oficina, Request $request, Mailer $mailer, Configuracion $configuracion)
    {
        $to = $configuracion->getEmails()->getContacto();
        if(!$oficina->isPrincipal()) {
            $to = ['Mensaje a agencia ' . $oficina->getCiudad() => $oficina->getEmail()] + $to;
        }
        $form = $this->createForm(ContactoFormType::class, null, [
            'to' => $to,
        ]);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $mailer->sendContacto($form->getData());
            $this->addFlash('success', 'El mensaje se ha enviado exitosamente');
            return $this->redirectToRoute('pta_contacto', ['oficina' => $oficina->getNombre()]);
        }

        return $this->render('pta/contacto.html.twig', [
            'currentOficina' => $oficina,
            'form' => $form->createView(),
        ]);
    }
}
