<?php

namespace App\Controller;

use App\Entity\Vacante;
use App\Repository\VacanteRepository;
use App\Service\Hv\HvWizard\HvWizard;
use App\Service\Hv\HvWizard\HvWizardRoutesAplicar;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\TwigBundle\Loader\NativeFilesystemLoader;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

/**
 * @IsGranted("ASPIRANTES_MODULE", statusCode=404, message="Resource not found")
 */
class VacanteController extends AbstractController
{
    use TargetPathTrait;

    /**
     * @Route("/ofertas", name="vacante_listado")
     */
    public function listado(VacanteRepository $vacanteRepository, Request $request)
    {
        $search = $request->get('s');
        $categoria = $request->get('c');
        $categoriaId = $request->get('cid');

        $categorias = [
            'profesion' => ['nombre' => 'Profesión'],
            'area' => ['nombre' => 'Área'],
            'cargo' => ['nombre' => 'Cargo'],
            'ciudad' => ['nombre' => 'Ciudad'],
        ];

        //security check
        if($categoria && !in_array($categoria, array_keys($categorias))) {
            $categoria = $categoriaId = null;
        }

        $vacantes = $vacanteRepository->findPublicada($search, $categoria, $categoriaId);

        foreach($categorias as $categoria => &$data) {
            $data['tipos'] = $vacanteRepository->getCategoriaPublicada($categoria);
        }

        return $this->render('vacante/listado.html.twig', [
            'vacantes' => $vacantes,
            'categorias' => $categorias,
            'search' => $search
        ]);
    }

    /**
     * @Route("/ofertas/{slug}", name="vacante_detalle")
     */
    public function detalle(Vacante $vacante)
    {
        return $this->render('vacante/detalle.html.twig', [
            'vacante' => $vacante
        ]);
    }

    /**
     * @Route("/ofertas/{slug}/aplicar/{wizard}/{step}", name="vacante_aplicar", defaults={"wizard": null, "step": "basicos"})
     */
    public function aplicar(Vacante $vacante, AuthenticationUtils $authenticationUtils, $wizard, $step,
                            ContainerBagInterface $bag, HvWizard $hvWizard, SessionInterface $session, EntityManagerInterface $em)
    {
        $usuario = $this->getUser();
        $puedeAplicar = true;
        if(!$usuario) {
            $puedeAplicar = false;
        } else {
            $routeInvalida = $hvWizard->getValidator()->getRoutesInvalidas(true);
            if($routeInvalida && $routeInvalida->key !== HvWizardRoutesAplicar::ROUTE_APLICAR_KEY) {
                $puedeAplicar = false;
                // usuario registrado sin hv completo datos basicos, ahora tiene hv, wizard ya no es registro si no completar
                $changeWizard = isset($routeInvalida->parameters['wizard']) && $routeInvalida->parameters['wizard'] !== $wizard;
                if(!$wizard || $changeWizard) {
                    return $this->redirectToRoute($routeInvalida->route, $routeInvalida->parameters);
                }
            }
        }

        if(!$puedeAplicar) {
            $this->aplicarPrependOverwriteTemplates($bag);
            // registro o completar
            if($wizard) {
                // al registrarse que redireccione de nuevo a esta pagina
                $this->saveTargetPath($session, 'main', $this->generateUrl('vacante_aplicar', ['slug' => $vacante->getSlug()]));
                return $this->forward(RegistroController::class . '::' . $step);
            }
            // login
            else {
                $error = $authenticationUtils->getLastAuthenticationError();
                $lastUsername = $authenticationUtils->getLastUsername();
                return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
            }
        } else {
            // borramos mensaje de registro exitoso
            $this->container->get('session')->getFlashBag()->clear();
            $hvWizard->clearSession();

            if($vacante->getAplicante($usuario)) {
                $this->addFlash('warning', 'No se puede aplicar a la misma vacante dos veces!');
            } else {
                $vacante->addAplicante($usuario);
                $em->flush();
                $this->addFlash('info', "Aplicación a vacante exitosa");
            }
        }

        return $this->redirectToRoute('vacante_detalle', ['slug' => $vacante->getSlug()]);
    }

    private function aplicarPrependOverwriteTemplates(ContainerBagInterface $bag)
    {
        /** @var NativeFilesystemLoader $x */
        $x = $this->container->get('twig')->getLoader();
        $x->prependPath(__DIR__ . '/../../templates/vacante/aplicar');
        $x->prependPath(__DIR__ . '/../../templates/' . $bag->get('empresa') . '/app/vacante/aplicar');
    }
}
