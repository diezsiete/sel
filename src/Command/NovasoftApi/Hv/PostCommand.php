<?php

namespace App\Command\NovasoftApi\Hv;

use Exception;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class PostCommand extends NapiHvCommand
{
    protected static $defaultName = 'sel:napi:hv:post';

    protected function configure()
    {
        parent::configure();
        $this->setDescription('generar un llamado post para una hv especifica');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $hv = $this->findHv();
            $response = $this->hvClient->post($hv);
            $this->io->write(print_r($response, 1));
        } catch (ExceptionInterface | TransportExceptionInterface | Exception $e) {
            $this->io->error('CODE: ' . $e->getCode() . "\n" . $e->getMessage());
        }
    }


}