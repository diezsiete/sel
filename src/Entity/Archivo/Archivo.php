<?php

namespace App\Entity\Archivo;

use App\Entity\Main\Usuario;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Archivo\ArchivoRepository")
 */
class Archivo
{
    use TimestampableEntity;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups("api")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("api")
     */
    private $filename;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("api")
     * @Assert\NotBlank(message="Ingrese nombre de archivo")
     */
    private $originalFilename;

    /**
     * @ORM\Column(type="string", length=140, nullable=true)
     * @Groups("api")
     */
    private $mimeType;

    /**
     * @ORM\Column(type="integer")
     * @Groups("api")
     */
    private $size = 0;

    /**
     * @ORM\Column(type="string", length=5, nullable=true)
     * @Groups("api")
     */
    private $extension;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Main\Usuario")
     * @ORM\JoinColumn(nullable=false)
     */
    private $owner;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getOwner(): ?Usuario
    {
        return $this->owner;
    }

    public function setOwner(?Usuario $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function getSize(): ?int
    {
        return $this->size;
    }

    public function setSize(int $size): self
    {
        $this->size = $size;

        return $this;
    }

    public function getExtension(): ?string
    {
        return $this->extension;
    }

    public function setExtension(?string $extension): self
    {
        $this->extension = $extension;

        return $this;
    }

    /**
     * @return DateTime
     * @Groups("api")
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return DateTime
     * @Groups("api")
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @return DateTime
     * @Groups("api")
     */
    public function getCreatedAtFull()
    {
        return $this->createdAt;
    }

    /**
     * @return DateTime
     * @Groups("api")
     */
    public function getUpdatedAtFull()
    {
        return $this->updatedAt;
    }
}
