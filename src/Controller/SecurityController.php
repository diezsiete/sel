<?php

namespace App\Controller;

use App\Entity\RestaurarClave;
use App\Entity\Usuario;
use App\Form\ProfileFormType;
use App\Form\OlvidoFormType;
use App\Form\RestaurarFormType;
use App\Repository\RestaurarClaveRepository;
use App\Security\LoginFormAuthenticator;
use App\Service\Mailer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends BaseController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('app_main');
        }
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
    }

    /**
     * @Route("/olvido", name="security_olvido")
     */
    public function olvido(Request $request, RestaurarClaveRepository $restaurarClaveRepository, Mailer $mailer)
    {
        $form = $this->createForm(OlvidoFormType::class);
        $form->handleRequest($request);
        $email = null;
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var RestaurarClave $restaurarClave */
            $restaurarClave = $form->getData();
            $email = $restaurarClave->getUsuario()->getEmail();

            if($prevRestaurarClave = $restaurarClaveRepository->findByUsuario($restaurarClave->getUsuario())) {
                $this->em()->remove($prevRestaurarClave);
                $this->em()->flush();
            }
            $this->em()->persist($restaurarClave);
            $this->em()->flush();

            $mailer->sendOlvido($restaurarClave);
        }

        return $this->render('security/olvido.html.twig', [
            'form' => $form->createView(),
            'email' => $email
        ]);
    }

    /**
     * @Route("/restaurar/{id}/{token}", name="security_restaurar")
     * @Entity("restaurarClave", expr="repository.findByUrl(id, token)")
     */
    public function restaurar(RestaurarClave $restaurarClave, Request $request,
                              GuardAuthenticatorHandler $guard, LoginFormAuthenticator $formAuthenticator)
    {
        $form = $this->createForm(RestaurarFormType::class, $restaurarClave->getUsuario());
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            /** @var Usuario $usuario */
            $usuario = $form->getData();

            $restaurarClave->setRestaurada(true);
            $this->em()->flush();

            $this->addFlash('success', "Cambio de contraseña realizado exitosamente.");

            return $guard->authenticateUserAndHandleSuccess($usuario, $request, $formAuthenticator, 'main');
        }
        return $this->render('security/restaurar.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/sel/perfil", name="app_profile", defaults={"header": "Perfil"})
     */
    public function profile(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $form = $this->createForm(ProfileFormType::class, $this->getUser());
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            /** @var Usuario $usuario */
            $usuario = $form->getData();

            if($plainPassword = $form['plainPassword']->getData()) {
                $encodedPassword = $passwordEncoder->encodePassword($usuario, $plainPassword);
                $usuario->setPassword($encodedPassword);
            }

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', "Datos actualizados exitosamente!");
        }

        return $this->render('security/profile.html.twig', [
            'profileForm' => $form->createView(),
        ]);
    }
}
