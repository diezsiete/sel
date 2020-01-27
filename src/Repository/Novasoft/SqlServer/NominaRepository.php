<?php


namespace App\Repository\Novasoft\SqlServer;


use App\Service\Novasoft\SqlServer\Connection;
use DateTime;
use DateTimeInterface;
use Exception;
use PDO;

class NominaRepository
{
    /**
     * @var Connection
     */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param string $ident
     * @param DateTimeInterface|null $fechaInicio
     * @param DateTimeInterface|null $fechaFin
     * @throws Exception
     */
    public function findByIdentAndPeriodo($ident, $fechaInicio = null, $fechaFin = null)
    {
        $tsql = "
        EXEC [PTA].[dbo].[rs_rhh_RepNom204a]
            @cod_cia = ?,
            @CodCco = ?,
            @CodSuc = ?,
            @cod_cla1 = ?,
            @cod_cla2 = ?,
            @cod_cla3 = ?,
            @FecIni = ?,
            @CodEmp = ?,
            @TipLiq = ?,
            @Origen = ?,
            @CodConv = ?,
            @FecFin = ?,
            @Ind_Fec = ?";

        if(is_object($fechaInicio)) {
            $fechaInicio = $fechaInicio->format('m/d/Y');
        } else if(!$fechaInicio) {
            $fechaInicio = "2/1/2017";
        }
        if(is_object($fechaFin)) {
            $fechaFin = $fechaFin->format('m/d/Y');
        }else if(!$fechaFin) {
            $fechaFin = (new DateTime())->format('m/t/Y');
        }

        $params = ["%", "%", "%", "%", "%", "%", $fechaInicio, $ident, "%", "H", "%", $fechaFin, 1];
        $statement = $this->connection->execute($tsql, $params);

        $noms = [];
        while($row = $statement->fetch(PDO::FETCH_ASSOC))  {
            $noms[] = [
                'fecliq' => $row['fecliq'],
                'feccte' => $row['feccte'],
                'monto' => $row['monto'],
                'salario' => $row['salario']
            ];
        }
        dump($noms);
    }
}