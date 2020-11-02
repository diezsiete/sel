<?php


namespace App\Message\Novasoft\Api;


class DeleteHvChildInNovasoft implements NapiHvMessage
{
    use MessageWithSolicitud;
    /**
     * @var string
     */
    private $napiId;
    /**
     * @var string
     */
    private $childClass;
    /**
     * @var int
     */
    private $hvId;

    public function __construct(string $napiId, string $childClass, int $hvId)
    {
        $this->napiId = $napiId;
        $this->childClass = $childClass;
        $this->hvId = $hvId;
    }

    /**
     * @return string
     */
    public function getNapiId(): string
    {
        return $this->napiId;
    }

    /**
     * @return string
     */
    public function getChildClass(): string
    {
        return $this->childClass;
    }

    /**
     * @return int
     */
    public function getHvId(): int
    {
        return $this->hvId;
    }
}