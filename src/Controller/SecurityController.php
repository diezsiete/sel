<?php

namespace App\Controller;

use App\Entity\Main\RestaurarClave;
use App\Entity\Main\Usuario;
use App\Form\ProfileFormType;
use App\Form\OlvidoFormType;
use App\Form\RestaurarFormType;
use App\Repository\Main\RestaurarClaveRepository;
use App\Security\LoginFormAuthenticator;
use App\Service\Mailer;
use App\Service\Utils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Component\Form\FormError;
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
            return $this->redirectToRoute('sel_panel');
        }
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/sel/login")
     * @Route("/se/iniciar-sesion")
     * @Route("/sel/iniciar-sesion")
     * @Route("/iniciar-sesion")
     */
    public function loginLegacy()
    {
        return $this->redirectToRoute('app_login');
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
    public function olvido(Request $request, RestaurarClaveRepository $restaurarClaveRepository, Mailer $mailer, Utils $utils)
    {
        $form = $this->createForm(OlvidoFormType::class);
        $form->handleRequest($request);
        $email = null;
        $errorCantSend = "";
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var RestaurarClave $restaurarClave */
            $restaurarClave = $form->getData();
            $email = $restaurarClave->getUsuario()->getEmail();
            if($email && $utils->emailIsValid($email)) {
                if($prevRestaurarClave = $restaurarClaveRepository->findByUsuario($restaurarClave->getUsuario())) {
                    $this->em()->remove($prevRestaurarClave);
                    $this->em()->flush();
                }
                $this->em()->persist($restaurarClave);
                $this->em()->flush();

                $mailer->sendOlvido($restaurarClave);
            } else {
                //TODO automatizar este proceso que permita enviar un mensaje directamente para agilizar proceso de actualizacion de correo
                $errorCantSend = !$email
                    ? "aunque tenemos su cedula registrada, no tenemos un correo asociado con su cedula"
                    : "el correo registrado '$email' no es valido";
            }
        }

        return $this->render('security/olvido.html.twig', [
            'form' => $form->createView(),
            'email' => $email,
            'errorCantSend' => $errorCantSend
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
            /** @var \App\Entity\Main\Usuario $usuario */
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
            /** @var \App\Entity\Main\Usuario $usuario */
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
