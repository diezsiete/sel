<?php


namespace App\Entity\Scraper;

use App\Entity\Hv;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass()
 */
class MessageSuperclassBase
{
    /**
     * @var int
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    protected $body;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    protected $headers;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    protected $queueName;

    /**
     * @var DateTimeInterface
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;

    /**
     * @var DateTimeInterface
     * @ORM\Column(type="datetime")
     */
    protected $availableAt;

    /**
     * @var DateTimeInterface|null
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $deliveredAt;

    /**
     * @var Hv|null
     * @ORM\ManyToOne(targetEntity="App\Entity\Hv")
     * @ORM\JoinColumn()
     */
    protected $hv;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @param string $body
     * @return $this
     */
    public function setBody(string $body)
    {
        $this->body = $body;
        return $this;
    }

    /**
     * @return string
     */
    public function getHeaders(): string
    {
        return $this->headers;
    }

    /**
     * @param string $headers
     * @return $this
     */
    public function setHeaders(string $headers)
    {
        $this->headers = $headers;
        return $this;
    }

    /**
     * @return string
     */
    public function getQueueName(): string
    {
        return $this->queueName;
    }

    /**
     * @param string $queueName
     * @return $this
     */
    public function setQueueName(string $queueName)
    {
        $this->queueName = $queueName;
        return $this;
    }

    /**
     * @return DateTimeInterface
     */
    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @param DateTimeInterface $createdAt
     * @return $this
     */
    public function setCreatedAt(DateTimeInterface $createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return DateTimeInterface
     */
    public function getAvailableAt(): DateTimeInterface
    {
        return $this->availableAt;
    }

    /**
     * @param DateTimeInterface $availableAt
     * @return $this
     */
    public function setAvailableAt(DateTimeInterface $availableAt)
    {
        $this->availableAt = $availableAt;
        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getDeliveredAt(): ?DateTimeInterface
    {
        return $this->deliveredAt;
    }

    /**
     * @param DateTimeInterface|null $deliveredAt
     * @return $this
     */
    public function setDeliveredAt(?DateTimeInterface $deliveredAt)
    {
        $this->deliveredAt = $deliveredAt;
        return $this;
    }

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