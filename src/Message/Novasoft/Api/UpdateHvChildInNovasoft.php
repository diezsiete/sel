<?php


namespace App\Message\Novasoft\Api;


class UpdateHvChildInNovasoft implements NapiHvMessage
{
    use MessageWithSolicitud;
    /**
     * @var array
     */
    private $childNormalized;
    /**
     * @var string
     */
    private $childClass;
    private $hvId;

    public function __construct(array $childNormalized, string $childClass, $hvId)
    {
        $this->childNormalized = $childNormalized;
        $this->childClass = $childClass;
        $this->hvId = $hvId;
    }

    /**
     * @return array
     */
    public function getChildNormalized(): array
    {
        return $this->childNormalized;
    }

    /**
     * @return string
     */
    public function getChildClass(): string
    {
        return $this->childClass;
    }

    /**
     * @return mixed
     */
    public function getHvId()
    {
        return $this->hvId;
    }
}