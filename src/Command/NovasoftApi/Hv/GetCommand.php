<?php

namespace App\Command\NovasoftApi\Hv;

use Exception;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class GetCommand extends NapiHvCommand
{
    protected static $defaultName = 'sel:napi:hv:get';

    protected function configure()
    {
        $this->setDescription('obtener informacion de una hv via GET')
            ->addArgument('identificacion', InputArgument::REQUIRED,
                'identificacion de la hv');

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $identificacion = $input->getArgument('identificacion');
            $response = $this->hvClient->get($identificacion);
            if($response) {
                $this->io->write(print_r($response, 1));
            } else {
                $this->io->warning("usuario con identificacion '$identificacion' no encontrado");
            }
        } catch (TransportExceptionInterface | Exception $e) {
            $this->io->error('CODE: ' . $e->getCode() . "\n" . $e->getMessage());
        }
    }


}