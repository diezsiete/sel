<?php


namespace App\Entity\Scraper;

use App\Entity\Hv;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Scraper\MessageHvRepository")
 * @ORM\Table(name="scraper_message_hv", indexes={
 *     @ORM\Index(name="idx_message_queue_success", columns={"queue_name"}),
 *     @ORM\Index(name="idx_message_available_at_success", columns={"available_at"}),
 *     @ORM\Index(name="idx_message_delivered_at_success", columns={"delivered_at"})
 * })
 */
class MessageHv extends MessageSuperclassBase
{
    /**
     * @var Hv|null
     * @ORM\ManyToOne(targetEntity="App\Entity\Hv")
     * @ORM\JoinColumn()
     */
    protected $hv;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    protected $log;

    /**
     * @return Hv|null
     */
    public function getHv(): ?Hv
    {
        return $this->hv;
    }

    /**
     * @param Hv|null $hv
     * @return $this
     */
    public function setHv(?Hv $hv)
    {
        $this->hv = $hv;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getLog(): ?string
    {
        return $this->log;
    }

    /**
     * @param string|null $log
     * @return MessageHv
     */
    public function setLog(?string $log): MessageHv
    {
        $this->log = $log;
        return $this;
    }
}