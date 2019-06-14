<?php


namespace App\Entity;


interface HvEntity
{
    public function getId(): ?int;

    public function getHv(): ?Hv;
}