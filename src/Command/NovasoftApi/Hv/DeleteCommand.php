<?php


namespace App\Command\NovasoftApi\Hv;


use App\Command\Helpers\TraitableCommand\TraitableCommand;
use App\Repository\Hv\HvRepository;
use App\Service\Novasoft\Api\Client\HvClient;
use Doctrine\Common\Annotations\Reader;
use Exception;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class DeleteCommand extends NapiHvCommand
{
    protected static $defaultName = 'sel:napi:hv:delete';

    protected function configure()
    {
        $this
            ->setDescription('borrar una hv de novasoft')
            ->addArgument('identificacion', InputArgument::REQUIRED, 'identificacion de la hv a borrar');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $response = $this->hvClient->delete($input->getArgument('identificacion'));
            $this->io->success($response);
        } catch (ExceptionInterface | TransportExceptionInterface | Exception $e) {
            $this->io->error('CODE: ' . $e->getCode() . "\n" . $e->getMessage());
        }
    }

}