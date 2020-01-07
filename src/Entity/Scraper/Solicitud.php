<?php

namespace App\Entity\Scraper;

use App\Entity\Hv\Hv;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Scraper\SolicitudRepository")
 */
class Solicitud
{

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\HV\Hv")
     * @ORM\JoinColumn(nullable=false)
     */
    private $hv;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $doneAt;

    /**
     * @ORM\Column(type="smallint")
     */
    private $estado = 0;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $log;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $data;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $messageId;

    /**
     * @ORM\Column(type="string", length=140)
     */
    private $MessageClass;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHv(): ?Hv
    {
        return $this->hv;
    }

    public function setHv(?Hv $hv): self
    {
        $this->hv = $hv;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getDoneAt(): ?\DateTimeInterface
    {
        return $this->doneAt;
    }

    public function setDoneAt(?\DateTimeInterface $doneAt): self
    {
        $this->doneAt = $doneAt;

        return $this;
    }

    public function getEstado(): ?int
    {
        return $this->estado;
    }

    public function setEstado(int $estado): self
    {
        $this->estado = $estado;

        return $this;
    }

    public function getLog(): ?string
    {
        return $this->log;
    }

    public function setLog(?string $log): self
    {
        $this->log = $log;

        return $this;
    }

    public function addLog(?string $log): self
    {
        $this->log = ($this->log ? $this->log . "\n" : "")  . $log;

        return $this;
    }

    public function getData(): ?string
    {
        return $this->data;
    }

    public function setData(?string $data): self
    {
        $this->data = $data;

        return $this;
    }

    public function getMessageId(): ?int
    {
        return $this->messageId;
    }

    public function setMessageId(?int $messageId): self
    {
        $this->messageId = $messageId;

        return $this;
    }

    public function getMessageClass(): ?string
    {
        return $this->MessageClass;
    }

    public function setMessageClass(string $MessageClass): self
    {
        $this->MessageClass = $MessageClass;

        return $this;
    }
}
