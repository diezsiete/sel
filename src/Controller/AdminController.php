<?php

namespace App\Controller;

use App\DataTable\Type\UsuarioDataTableType;
use App\Entity\Usuario;
use App\Form\ProfileFormType;
use Omines\DataTablesBundle\DataTableFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminController extends AbstractController
{

    /**
     * @Route("/sel/admin/usuarios", name="admin_usuarios")
     */
    public function usuarios(DataTableFactory $dataTableFactory, Request $request)
    {
        $table = $dataTableFactory->createFromType(UsuarioDataTableType::class, [], ['searching' => true])
            ->handleRequest($request);

        if($table->isCallback()) {
            return $table->getResponse();
        }

        return $this->render('admin/usuarios.html.twig', ['datatable' => $table]);
    }

    /**
     * @Route("/sel/admin/usuarios/editar/{id}", name="admin_usuarios_editar")
     */
    public function editarUsuario(Usuario $usuario, Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $form = $this->createForm(ProfileFormType::class, $usuario);
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

        return $this->render('admin/editar-usuario.html.twig', [
            'profileForm' => $form->createView(),
        ]);
    }
}
