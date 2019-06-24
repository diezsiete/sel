<?php

namespace App\Controller;

use App\Entity\Vacante;
use App\Repository\VacanteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class VacanteController extends AbstractController
{
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

    }
}
