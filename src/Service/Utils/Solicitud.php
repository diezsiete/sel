<?php


namespace App\Service\Utils;


use Symfony\Component\HttpFoundation\Request;

class Solicitud
{
    /**
     * @param Request $request
     * @return Request
     */
    public function jsonPostParseBody(Request $request)
    {
        if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
            $data = json_decode($request->getContent(), true);
            $request->request->replace(is_array($data) ? $data : array());
        }
        return $request;
    }
}