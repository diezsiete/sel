<?php


namespace App\Command\Mailer;


use App\Command\Helpers\SearchByConvenioOrEmpleado;
use App\Command\Helpers\TraitableCommand\TraitableCommand;

class ImageMasivoCommand extends TraitableCommand
{
    use SearchByConvenioOrEmpleado;
}