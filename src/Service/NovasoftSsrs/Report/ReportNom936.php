<?php
/**
 * Reporte de convenios en Novasoft
 * User: guerrerojosedario
 * Date: 2018/08/15
 * Time: 9:08 PM
 */

namespace App\Service\NovasoftSsrs\Report;

use App\Service\NovasoftSsrs\Mapper\GenericMapper;
use App\Service\NovasoftSsrs\Mapper\MapperNom936;

/**
 * Class NOM936
 * @package Novasoft\helper
 */
class ReportNom936 extends Report
{
    protected function getReportPath(): string
    {
        return "/ReportesWeb/NOM/NOM936";
    }

    protected function getMapperClass(): ?string
    {
        return MapperNom936::class;
    }
}