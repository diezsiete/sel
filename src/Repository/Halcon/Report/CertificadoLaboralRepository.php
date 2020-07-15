<?php
namespace App\Repository\Halcon\Report;

use App\Entity\Halcon\Report\CertificadoLaboral;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\Persistence\ManagerRegistry;

class CertificadoLaboralRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CertificadoLaboral::class);
    }

    /**
     * @noinspection PhpDocMissingThrowsInspection
     * @param mixed $ident
     * @param bool $numeroContrato
     * @return CertificadoLaboral[]|CertificadoLaboral
     */
    public function findCertificado($ident, $numeroContrato = false)
    {
        $sql = "
            SELECT v.nit_tercer AS nit_tercer,
                   {$this->ifnull("e.telefono")} AS empresa_telefono,
                   {$this->ifnull("e.direccion")} AS empresa_direccion, 
                   {$this->ifnull("e.nit")} AS empresa_nit, 
                   {$this->ifnull("e.usuario")} AS usuario, 
                   IF(e.nombre IS NULL OR e.nombre = '', co.nombre, e.nombre) AS 'Empresa Usuaria',
                   {$this->ifnull("c.nombre")} AS 'Cargo o Labor', 
                   v.no_contrat AS 'Contrato', 
                   DATE_FORMAT(v.ingreso, '%Y-%m-%d') AS 'Inicio', 
                   IF(v.retiro = '0000/00/00', NULL, DATE_FORMAT(v.retiro, '%Y-%m-%d')) AS 'Terminación', 
                   CONCAT('$ ', FORMAT(v.sueldo_mes, 0)) AS 'Salario Contratación'
            FROM `vinculacion` v
            LEFT JOIN `empresa` e ON v.usuario = e.usuario
            LEFT JOIN `compania` co ON e.compania = co.compania
            LEFT JOIN `cargo` c ON v.cargo = c.cargo
            WHERE v.nit_tercer = ?";

        $paramenters = [1 => $ident];

        if($numeroContrato){
            $sql .= " AND v.no_contrat = ? LIMIT 1";
            $paramenters[2] = $numeroContrato;
        }

        $query = $this->_em->createNativeQuery($sql, $this->getResultSetMapping());
        $query->setParameters($paramenters);

        return $numeroContrato ? $query->getOneOrNullResult() : $query->getResult();

    }

    private function getResultSetMapping()
    {
        $rsm = new ResultSetMapping;
        $rsm->addEntityResult(CertificadoLaboral::class, 'cl')
            ->addFieldResult('cl', 'nit_tercer', 'identificacion')
            ->addFieldResult('cl', 'empresa_telefono', 'empresaTelefono')
            ->addFieldResult('cl', 'direccion', 'empresaDireccion')
            ->addFieldResult('cl', 'empresa_nit', 'empresaNit')
            ->addFieldResult('cl', 'usuario', 'usuario')
            ->addFieldResult('cl', 'Empresa Usuaria', 'convenio')
            ->addFieldResult('cl', 'Cargo o Labor', 'cargo')
            ->addFieldResult('cl', 'Contrato', 'contrato')
            ->addFieldResult('cl', 'Inicio', 'fechaIngreso')
            ->addFieldResult('cl', 'Terminación', 'fechaRetiro')
            ->addFieldResult('cl', 'Salario Contratación', 'salario')
        ;
        return $rsm;
    }

    private function ifnull($field, $expr2 = 'SIN DEFINIR'){
        return "IFNULL({$field}, '{$expr2}')";
    }
}