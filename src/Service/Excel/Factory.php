<?php


namespace App\Service\Excel;


class Factory
{
    public function read(string $filePath): Read
    {
        return new Read($filePath);
    }
}