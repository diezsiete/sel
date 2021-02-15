<?php

namespace App\Entity\Hv;

use App\Service\UploaderHelper;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Hv\AdjuntoRepository")
 * @ORM\Table(name="hv_adjunto")
 */
class Adjunto implements HvEntity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups("main")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Hv\Hv", inversedBy="adjunto", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $hv;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"main", "selr:migrate"})
     */
    private $filename;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"main", "selr:migrate"})
     */
    private $originalFilename;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"main", "selr:migrate"})
     */
    private $mimeType;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHv(): ?Hv
    {
        return $this->hv;
    }

    public function setHv(Hv $hv): self
    {
        $this->hv = $hv;

        return $this;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(string $filename): self
    {
        $this->filename = $filename;

        return $this;
    }

    public function getOriginalFilename(): ?string
    {
        return $this->originalFilename;
    }

    public function setOriginalFilename(string $originalFilename): self
    {
        $this->originalFilename = $originalFilename;

        return $this;
    }

    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    public function setMimeType(string $mimeType): self
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    public function getFilePath():string
    {
        return UploaderHelper::HV_ADJUNTO . '/' . $this->getFilename();
    }

    public function getNapiId(): string
    {
        return '';
    }
}
