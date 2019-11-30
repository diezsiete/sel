<?php


namespace App\Entity\Scraper;

use App\Entity\Hv;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity()
 * @ORM\Table(name="scraper_message_hv_success", indexes={
 *     @ORM\Index(name="idx_message_success_queue_success", columns={"queue_name"}),
 *     @ORM\Index(name="idx_message_success_available_at_success", columns={"available_at"}),
 *     @ORM\Index(name="idx_message_success_delivered_at_success", columns={"delivered_at"})
 * })
 */
class MessageHvSuccess extends MessageSuperclassBase
{
    /**
     * @var Hv|null
     * @ORM\ManyToOne(targetEntity="App\Entity\Hv")
     * @ORM\JoinColumn()
     */
    protected $hv;

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
}