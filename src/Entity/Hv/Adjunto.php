<?php

namespace App\Entity\Hv;

use App\Controller\Cv\CreateAdjuntoAction;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Service\UploaderHelper;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Hv\AdjuntoRepository")
 * @ORM\Table(name="hv_adjunto")
 * @ApiResource(
 *     iri="http://schema.org/MediaObject",
 *     collectionOperations={
 *         "post"={
 *             "controller"=CreateAdjuntoAction::class,
 *             "deserialize"=false,
 *             "openapi_context"={
 *                 "requestBody"={
 *                     "content"={
 *                         "multipart/form-data"={
 *                             "schema"={
 *                                 "type"="object",
 *                                 "properties"={
 *                                     "file"={
 *                                         "type"="string",
 *                                         "format"="binary"
 *                                     }
 *                                 }
 *                             }
 *                         }
 *                     }
 *                 }
 *             }
 *         },
 *         "get"
 *     },
 *     itemOperations={
 *         "get"
 *     }
 * )
 * @Vich\Uploadable
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
     * @ORM\OneToOne(targetEntity="App\Entity\Hv\Hv", inversedBy="adjunto")
     * @ORM\JoinColumn()
     */
    private $hv;

    /**
     * @var File|null
     * @Assert\NotNull()
     * @Vich\UploadableField(mapping="adjunto", fileNameProperty="filename", mimeType="mimeType", originalName="originalFilename")
     */
    public $file;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("main")
     */
    private $filename;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("main")
     */
    private $originalFilename;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("main")
     */
    private $mimeType;

    /**
     * @var string|null
     * @ApiProperty(iri="http://schema.org/contentUrl")
     * @Groups("main")
     */
    public $contentUrl;

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
        return UploaderHelper::HV_ADJUNTO . '/' . $this->filename;
    }

    public function getNapiId(): string
    {
        return '';
    }
}
