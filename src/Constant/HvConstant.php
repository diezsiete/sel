<?php


namespace App\Constant;


class HvConstant
{
    const IDENTIFICACION_TIPO = [
        //'0' => 'No Aplica',
        '01' => 'Cédula de Ciudadania',
        '02' => 'Cédula de Extranjería',
        '03' => 'Tarjeta de Identidad',
        '04' => 'Número Unico de Identificación Personal',
        '05' => 'Registro Civil',
        '06' => 'Pasaporte',
        '10' => 'Número de Identificación Tributaria',
        '21' => 'Tarjeta de Extranjería',
        '22' => 'Tipo documento Extranjero',
        '23' => 'Documento definido información exogena',
    ];
    const ESTADO_CIVIL = [
        1 => 'SOLTERO',
        2 => 'CASADO',
        3 => 'DIVORCIADO',
        4 => 'VIUDO',
        5 => 'UNION LIBRE'
    ];
    const GRUPO_SANGUINEO = [
        "A" => "A", "B" => "B", "AB" => "AB", "O" => "O"
    ];
    const FACTOR_RH = [
        "+" => "+", "-" => "-"
    ];
    const NACIONALIDAD = [
        1 => "Colombiano", 2 => "Doble", 3 => "Extranjero"
    ];

    const NIVEL_ACADEMICO = [
        '01' => 'PREESCOLAR',
        '02' => 'BASICA PRIMARIA',
        '03' => 'BASICA SECUNDARIA',
        '04' => 'MEDIA ACADEMICA O CLASICA',
        '05' => 'MEDIA TECNICAS',
        '06' => 'NORMALISTA',
        '07' => 'TECNICA PROFESIONAL',
        '08' => 'TECNOLOGICO',
        '09' => 'PROFESIONAL',
        '10' => 'ESPECIALISTA',
        '11' => 'MAESTRIA',
        '12' => 'DOCTORADO',
        '13' => 'NINGUNO'
    ];

    const PARENTESCO = ['01' => 'NIETO', '02' => 'HIJO', '04' => 'HERMANO', '05' => 'TIO', '06' => 'SOBRINO',
        '07' => 'PRIMO', '08' => 'CUÑADO', '09' => 'YERNO/NUERA', '10' => 'SUEGRO', '11' => 'CONYUGE', '12' => 'ABUELO',
        '13' => 'PADRE/MADRE'
    ];
    const OCUPACION = ['1' => 'EMPLEADO', '2' => 'ESTUDIANTE', '3' => 'HOGAR', '4' => 'DESEMPLEADO',
        '5' => 'PENSIONADO', '6' => 'INDEPENDIENTE', '7' => 'OTRAS'
    ];

    const REFERENCIA_TIPO = [1 => 'PERSONAL', 2 => 'FAMILIAR', 3 => 'LABORAL'];

    const VIVIENDA_TIP = [1 => 'CASA', 2 => 'APARTAMENTO', 3 => 'CASALOTE', 4 => 'HABITACIÓN', 5 => 'FINCA',
        6 => 'INQUILINATO', 7 => 'VEREDA'
    ];
    const TENEDOR = [1 => 'PROPIA', 2 => 'ARRIENDO', 3 => 'CREDITO HIPOTECARIO', 4 => 'CEDIDA', 5 => 'FAMILIAR'];
}