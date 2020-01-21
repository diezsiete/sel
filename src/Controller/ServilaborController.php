<?php

namespace App\Controller;

use App\Entity\Main\Post;
use App\Entity\Main\Tag;
use App\Form\CandidatoFormType;
use App\Form\ContactoFormType;
use App\Form\Model\CandidatosModel;
use App\Repository\Main\PostRepository;
use App\Repository\Main\TagRepository;
use App\Security\LoginFormAuthenticator;
use App\Service\Configuracion\Configuracion;
use App\Service\Mailer;
use App\Service\UploaderHelper;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class ServilaborController extends AbstractController
{
    /**
     * @var \App\Repository\Main\PostRepository
     */
    private $postRepo;

    public function __construct(PostRepository $postRepo)
    {
        $this->postRepo = $postRepo;
    }

    /**
     * @Route("/", name="servilabor_inicio", host="%empresa.SERVILABOR.host%")
     */
    public function inicio()
    {
        return $this->render('servilabor/inicio.html.twig');
    }

    /**
     * @Route("/nosotros", name="servilabor_nosotros", host="%empresa.SERVILABOR.host%")
     */
    public function nosotros()
    {
        return $this->render('servilabor/nosotros.html.twig');
    }

    /**
     * @Route("/servicios", name="servilabor_servicios", host="%empresa.SERVILABOR.host%")
     */
    public function servicios()
    {
        return $this->render('servilabor/servicios.html.twig');
    }

    /**
     * @Route("/servicios/{servicio}", name="servilabor_servicio", host="%empresa.SERVILABOR.host%")
     */
    public function serviciosInner($servicio)
    {
        if(!in_array($servicio, ['outsourcing', 'rpo', 'payroll'])) {
            throw $this->createNotFoundException('Pagina no encontrada');
        }

        return $this->render('servilabor/servicios-inner.html.twig', [
            'servicio' => $servicio
        ]);
    }

    /**
     * @Route("/blog", name="servilabor_blog", host="%empresa.SERVILABOR.host%")
     */
    public function blog(PostRepository $postRepo, TagRepository $tagRepo, PaginatorInterface $paginator, Request $request)
    {
        return $this->blogList($postRepo, $tagRepo, $paginator, $request);
    }

    /**
     * @Route("/blog/tag/{slug}", name="servilabor_blog_tag", host="%empresa.SERVILABOR.host%")
     */
    public function blogTag(PostRepository $postRepo, TagRepository $tagRepo, PaginatorInterface $paginator, Request $request, Tag $tag)
    {
        return $this->blogList($postRepo, $tagRepo, $paginator, $request, $tag);
    }

    private function blogList(PostRepository $postRepo, TagRepository $tagRepo, PaginatorInterface $paginator, Request $request, ?Tag $tag = null)
    {
        $qb = $postRepo->searchQueryBuilder($tag);

        $pagination = $paginator->paginate($qb, $request->get('page', 1), 5);

        return $this->render('servilabor/blog/list.html.twig', [
            'pagination' => $pagination,
            'tagSelected' => $tag,
            'tags' => $tagRepo->findAll()
        ]);
    }

    /**
     * @Route("/blog/{slug}", name="servilabor_blog_item", host="%empresa.SERVILABOR.host%")
     */
    public function blogItem(Post $post, PostRepository $repository)
    {
        $recomendados = $repository->findRandom(3, $post);
        return $this->render('servilabor/blog/item.html.twig', [
            'post' => $post,
            'recomendados' => $recomendados
        ]);
    }

    public function recentBlogs($max = 3)
    {
        $posts = $this->postRepo->findBy([], ['id' => 'DESC'], $max, 0);
        return $this->render('servilabor/blog/recent.html.twig', [
            'posts' => $posts
        ]);
    }

    /**
     * @Route("/candidatos", name="servilabor_candidatos", host="%empresa.SERVILABOR.host%")
     */
    public function candidatos()
    {
        return $this->redirectToRoute('registro_datos_basicos');
    }

    /**
     * @Route("/contacto", name="servilabor_contacto", host="%empresa.SERVILABOR.host%")
     */
    public function contacto(Request $request, Mailer $mailer)
    {
        $form = $this->createForm(ContactoFormType::class);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $mailer->sendContacto($form->getData());
            $this->addFlash('success', 'El mensaje se ha enviado exitosamente');
        }

        return $this->render('servilabor/contacto.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/paypal", name="servilabor_paypal")
     */
    public function paypal()
    {
        return $this->render('servilabor/paypal.html.twig');
    }
}
