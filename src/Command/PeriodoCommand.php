<?php


namespace App\Command;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;

abstract class PeriodoCommand extends Command
{
    protected $optionDesdeDescription = 'fecha desde Y-m-d. [omita y se toma hoy]';
    protected $optionHastaDescription = 'fecha desde Y-m-d. [omita y se toma hoy]';

    protected function configure()
    {
        $this
            ->addOption('inicio', 'i', InputOption::VALUE_OPTIONAL, $this->optionDesdeDescription)
            ->addOption('fin', 'f', InputOption::VALUE_OPTIONAL, $this->optionHastaDescription)
            ->addOption('periodo', 'p', InputOption::VALUE_OPTIONAL,
                'Omite desde y hasta. Especifica mes en formato Y-m');
    }

    /**
     * @param InputInterface $input
     * @return \DateTime|null
     */
    protected function getInicio(InputInterface $input, $leftNull = false)
    {
        $desde = $input->getOption('inicio');
        if ($periodo = $input->getOption('periodo')) {
            $desde = \DateTime::createFromFormat('Y-m-d', "$periodo-01");
        } else {
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
     */
    protected function getFin(InputInterface $input, $leftNull = false)
    {
        $hasta = $input->getOption('fin');
        if ($periodo = $input->getOption('periodo')) {
            $hasta = \DateTime::createFromFormat('Y-m-d', \DateTime::createFromFormat("Y-m-d", "$periodo-01")->format("Y-m-t"));
        } else {
            if ($hasta) {
                $hasta = \DateTime::createFromFormat('Y-m-d', $hasta);
            } else {
                $hasta = $leftNull ? null : new \DateTime();
            }
        }
        return $hasta;
    }
}