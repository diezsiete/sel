<?php

namespace App\Controller;

use App\Form\VacanteFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class VacanteController extends AbstractController
{
    /**
     * @Route("/vacante/crear", name="vacante_crear")
     */
    public function crear(Request $request)
    {
        $form = $this->createForm(VacanteFormType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', "Datos actualizados exitosamente!");
        }

        return $this->render('vacante/crear.html.twig', [
            'vacanteForm' => $form->createView()
        ]);
    }
}
