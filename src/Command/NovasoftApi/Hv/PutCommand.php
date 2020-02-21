<?php

namespace App\Command\NovasoftApi\Hv;

use Exception;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class PutCommand extends NapiHvCommand
{
    protected static $defaultName = 'sel:napi:hv:put';

    protected function configure()
    {
        parent::configure();
        $this->setDescription('generar un llamado put para una hv especifica');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $hv = $this->findHv();
            $response = $this->hvClient->put($hv);
            $this->io->write(print_r($response, 1));
        } catch (ExceptionInterface | TransportExceptionInterface | Exception $e) {
            $this->io->error('CODE: ' . $e->getCode() . "\n" . $e->getMessage());
        }
    }


}