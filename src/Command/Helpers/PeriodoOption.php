<?php


namespace App\Command\Helpers;


use App\Command\Helpers\TraitableCommand\Annotation\Configure;
use DateTime;
use DateTimeInterface;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;

trait PeriodoOption
{
    /**
     * @var DateTimeInterface
     */
    private $periodo = null;

    protected $periodoDescription = 'Especifique mes en formato Y-m';

    /**
     * @Configure
     */
    public function addOptionPeriodo()
    {
        $this->addOption('periodo', 'p', InputOption::VALUE_OPTIONAL, $this->periodoDescription, false);
    }


    public function getPeriodo(InputInterface $input, $required = true)
    {
        if($this->periodo === null) {
            $periodo = $input->getOption('periodo');
            if($periodo) {
                $this->periodo = DateTime::createFromFormat('Y-m-d', "$periodo-01");
            }else if($periodo !== false) {
                $this->periodo = DateTime::createFromFormat('Y-m-d', (new DateTime())->format('Y-m') . '-01');
            }
            else if($required) {
                throw new RuntimeException(sprintf('The "--%s" option is required.', 'periodo'));
            }
        }
        return $this->periodo;
    }

    public function getRangoFromPeriodo(InputInterface $input, $required = true)
    {
        $periodo = $this->getPeriodo($input, $required);
        $end = null;
        if($periodo) {
            $end = DateTime::createFromFormat('Y-m-d', $periodo->format('Y-m-t'));
            //$periodo = DateTime::createFromFormat('Y-m-d', (new DateTime())->format('Y-m') . '-01');
        }

        return (object)['start' => $periodo, 'end' => $end];
    }

}