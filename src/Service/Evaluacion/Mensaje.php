<?php


namespace App\Service\Evaluacion;


class Mensaje
{
    /**
     * @var Navegador
     */
    private $navegador;
    /**
     * @var Evaluador
     */
    private $evaluador;

    private $messageTypes = [
        'preguntaDiapositiva' => [
            'message' => '<b>Fallaste en la pregunta.</b> Reviza de nuevo las diapositivas para reforzar los conceptos',
            'type' => 'warning',
        ],
        'preguntaRepeticion' => [
            'message' => '<b>Intentalo de nuevo</b> Responde correctamente la pregunta',
            'type' => 'info'
        ],
        'moduloRepeticion' => [
            'message' => '<b>Fallaste en algunas respuestas.</b> Reviza de nuevo las diapositivas para reforzar los conceptos',
            'type' => 'warning'
        ],
        'success' => [
            'message' => '<b>Muy bien!.</b> Haz respondido correctamente.',
            'type' => 'success'
        ],
        'pregunta' => [
            'message' => '',
            'type' => 'info'
        ]
    ];

    private $currentMessageType = null;

    public function __construct(Navegador $navegador, Evaluador $evaluador)
    {
        $this->navegador = $navegador;
        $this->evaluador = $evaluador;
    }

    public function test()
    {
        return $this->navegador->getProgreso()->getId();
    }

    public function hasFlashMessage()
    {
        return !!$this->findTypeOfMessage();
    }

    public function flashMessageType()
    {
        return $this->findTypeOfMessage('type');
    }

    public function flashMessage()
    {
        return $this->findTypeOfMessage('message');
    }

    private function findTypeOfMessage($key = null)
    {
        if($this->currentMessageType === null) {
            $this->currentMessageType = false;
            if($this->navegador->getEvaluacion()->isPreguntasEnabled() && $this->navegador->getProgreso()->isPreguntasEnabled()) {
                if ($this->navegador->getPreguntaDiapositiva()) {
                    $this->currentMessageType = 'preguntaDiapositiva';
                } else if ($this->navegador->getPregunta()) {
                    if($this->evaluador->isPreguntaRepeticion() && !$this->evaluador->evaluarRespuesta()) {
                        if($this->navegador->getPregunta()->hasDiapositivas() || $this->evaluador->isModuloRepeticion()) {
                            $this->currentMessageType = 'preguntaRepeticion';
                        }
                    } else {
                        $this->currentMessageType = 'pregunta';
                    }
                } else if ($this->evaluador->isModuloRepeticion()) {
                    $this->currentMessageType = 'moduloRepeticion';
                }
            }
        }
        if($key && $this->currentMessageType) {
            if($this->currentMessageType === 'pregunta' && $key === 'message') {
                return $this->navegador->getPregunta()->getMensajeAyuda();
            } else {
                return $this->messageTypes[$this->currentMessageType][$key];
            }
        }
        return $this->currentMessageType;
    }
}