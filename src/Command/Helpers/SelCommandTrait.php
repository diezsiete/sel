<?php


namespace App\Command\Helpers;


use App\Entity\Usuario;
use App\Service\Configuracion\Configuracion;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\OutputInterface;

trait SelCommandTrait
{
    /**
     * @var null|Usuario
     */
    private $superAdmin = null;

    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var Configuracion
     */
    protected $configuracion;

    /**
     * @required
     */
    public function setEntityManager(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @required
     */
    public function setConfiguracion(Configuracion $configuracion)
    {
        $this->configuracion = $configuracion;
    }

    /**
     * @return Usuario|null
     * @throws NonUniqueResultException
     */
    public function getSuperAdmin($cache = true)
    {
        if(!$this->superAdmin || !$cache) {
            $this->superAdmin = $this->em->getRepository(Usuario::class)->superAdmin();
        }
        return  $this->superAdmin;
    }

    public function runCommand(OutputInterface $output, $command, $arguments = [], $options = [])
    {
        $command = $this->getApplication()->find($command);
        $commandArgs = [
            'command' => $command
        ];
        foreach($arguments as $argName => $argValue) {
            $commandArgs[$argName] = $argValue;
        }
        foreach($options as $optionName => $optionValue) {
            if(substr($optionName, 0, 1) === '-') {
                $commandArgs[$optionName] = $optionValue;
            } else {
                $optionName = strlen($optionName) === 1 ? "-" . $optionName : "--" . $optionName;
                $commandArgs[$optionName] = $optionValue;
            }
        }

        $commandInput = new ArrayInput($commandArgs);
        return $command->run($commandInput, $output);
    }
}