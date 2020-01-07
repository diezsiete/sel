<?php

namespace App\Controller\Admin;

use App\DataTable\Type\UsuarioDataTableType;
use App\Entity\Main\Usuario;
use App\Form\ProfileFormType;
use Omines\DataTablesBundle\DataTableFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UsuarioController extends AbstractController
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

        return $this->render('admin/usuario/list.html.twig', ['datatable' => $table]);
    }

    /**
     * @Route("/sel/admin/usuarios/editar/{id}", name="admin_usuarios_editar")
     */
    public function editarUsuario(Usuario $usuario, Request $request)
    {
        $form = $this->createForm(ProfileFormType::class, $usuario);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', "Datos actualizados exitosamente!");
        }

        return $this->render('admin/usuario/editar.html.twig', [
            'profileForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/sel/admin/usuarios/crear/", name="admin_usuario_crear")
     */
    public function crearUsuario(Request $request)
    {
        $form = $this->createForm(ProfileFormType::class);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            /** @var Usuario $usuario */
            $usuario = $form->getData();
            $this->getDoctrine()->getManager()->persist($usuario);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', "Usuario creado exitosamente!");
            return $this->redirectToRoute('admin_usuarios');
        }

        return $this->render('admin/usuario/crear.html.twig', [
            'profileForm' => $form->createView(),
        ]);
    }
}
