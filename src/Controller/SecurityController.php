<?php

namespace App\Controller;

use App\Entity\Usuario;
use App\Form\ProfileFormType;
use App\Service\Configuracion\Configuracion;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
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
