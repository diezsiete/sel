<?php


namespace App\Command\Helpers;


use App\Command\Helpers\TraitableCommand\Annotation\Configure;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;

trait RangoPeriodoOption
{
    protected $optionInicioDescription = 'fecha desde Y-m-d. [omita y se toma hoy]';
    protected $optionFinDescription = 'fecha desde Y-m-d. [omita y se toma hoy]';


    /**
     * @Configure
     */
    public function addOptionRango()
    {
        $this->addOption('inicio', 'i', InputOption::VALUE_REQUIRED, $this->optionInicioDescription);
        $this->addOption('fin', 'f', InputOption::VALUE_REQUIRED, $this->optionFinDescription);
    }


    /**
     * @param InputInterface $input
     * @return \DateTime|null
     * @throws \Exception
     */
    protected function getInicio(InputInterface $input, $leftNull = false)
    {
        $desde = $this->getPeriodo($input, false);

        if(!$desde) {
            $desde = $input->getOption('inicio');
            if ($desde) {
                $desde = \DateTime::createFromFormat('Y-m-d', $desde);
            } else {
                $desde = $leftNull ? null : new \DateTime();
            }
        }
        return $desde;
    }

    /**
     * @param InputInterface $input
     * @return \DateTime|null
     * @throws \Exception
     */
    protected function getFin(InputInterface $input, $leftNull = false)
    {
        $rangoPeriodo = $this->getRangoFromPeriodo($input, false);
        if ($rangoPeriodo) {
            $hasta = $rangoPeriodo->end;
        } else {
            $hasta = $input->getOption('fin');
            if ($hasta) {
                $hasta = \DateTime::createFromFormat('Y-m-d', $hasta);
            } else {
                $hasta = $leftNull ? null : new \DateTime();
            }
        }
        return $hasta;
    }

    public abstract function getPeriodo(InputInterface $input, $required = true);
    public abstract function getRangoFromPeriodo(InputInterface $input, $required = true);
}