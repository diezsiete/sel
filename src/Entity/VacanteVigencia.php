<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VacanteVigenciaRepository")
 */
class VacanteVigencia
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $nombre;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $intervalSpec;

    /**
     * @ORM\Column(type="string", length=7)
     */
    private $mysqlInterval;

    /**
     * @ORM\Column(type="smallint")
     */
    private $mysqlIntervalValue;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getIntervalSpec(): ?string
    {
        return $this->intervalSpec;
    }

    public function setIntervalSpec(string $intervalSpec): self
    {
        $this->intervalSpec = $intervalSpec;

        return $this;
    }

    public function getMysqlInterval(): ?string
    {
        return $this->mysqlInterval;
    }

    public function setMysqlInterval(string $mysqlInterval): self
    {
        $this->mysqlInterval = $mysqlInterval;

        return $this;
    }

    public function getMysqlIntervalValue(): ?int
    {
        return $this->mysqlIntervalValue;
    }

    public function setMysqlIntervalValue(int $mysqlIntervalValue): self
    {
        $this->mysqlIntervalValue = $mysqlIntervalValue;

        return $this;
    }

    public function __toString()
    {
        return $this->nombre;
    }
}
