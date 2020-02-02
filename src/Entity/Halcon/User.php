<?php

namespace App\Entity\Halcon;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Halcon\UserRepository")
 * @ORM\HasLifecycleCallbacks
 */
class User
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    private $user;

    /**
     * @ORM\Column(type="blob", nullable=true)
     */
    private $pss;

    /**
     * @ORM\Column(type="string", length=60, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=60, nullable=true)
     */
    private $email;

    private $nameParsed = null;

    private $primerNombre = "";

    private $segundoNombre = "";

    private $primerApellido = "";

    private $segundoApellido = "";

    /**
     * @ORM\Column(type="smallint")
     */
    private $novasoft;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?string
    {
        return $this->user;
    }

    public function setUser(?string $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getPss()
    {
        return $this->pss;
    }

    public function setPss($pss): self
    {
        $this->pss = $pss;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getPrimerNombre(): string
    {
        return $this->primerNombre;
    }

    /**
     * @return string
     */
    public function getSegundoNombre(): string
    {
        return $this->segundoNombre;
    }

    /**
     * @return string
     */
    public function getPrimerApellido(): string
    {
        return $this->primerApellido;
    }

    /**
     * @return string
     */
    public function getSegundoApellido(): string
    {
        return $this->segundoApellido;
    }

    public function getNombreCompleto()
    {
        return $this->primerNombre . ($this->segundoNombre ? " " . $this->segundoNombre : "") . " " . $this->primerApellido
            . ($this->segundoApellido ? " " . $this->segundoApellido : "");
    }

    /**
     * @ORM\PostLoad
     */
    public function parseName()
    {
        if($this->nameParsed === null && substr($this->name, -1) === "/") {
            $this->nameParsed = [];
            // removemos el ultimo slash del nombre, para que explode no retorne un valor adicional
            $name = substr($this->name, 0, strlen($this->name) - 1);

            $nameExplode = explode('/', $name);
            $this->primerApellido = $nameExplode[0];
            if(isset($nameExplode[1])) {
                $this->segundoApellido = $nameExplode[1];
            }

            $nombresPila = explode(" ", $nameExplode[2]);
            $this->primerNombre = $nombresPila[0];
            unset($nombresPila[0]);
            if($nombresPila) {
                $this->segundoNombre = array_reduce($nombresPila, function ($carry, $item) {
                    return $carry ? $carry . " " . $item : $carry . $item;
                }, "");
            }
        }
    }

    public function getNovasoft(): ?int
    {
        return $this->novasoft;
    }

    public function setNovasoft(int $novasoft): self
    {
        $this->novasoft = $novasoft;

        return $this;
    }
}
