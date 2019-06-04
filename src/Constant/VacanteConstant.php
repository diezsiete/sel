<?php


namespace App\Constant;


class VacanteConstant
{
    const NIVEL = [
        1 => 'Alta Gerencia',
        2 => 'Gerencia Media',
        3 => 'Profesional',
        4 => 'Asistencial'
    ];
    const SUBNIVEL = [
        1 => [
            11 => 'Presidente',
            12 => 'Vicepresidente',
            13 => 'Gerente Senior',
        ],
        2 => [
            14 => 'Gerente',
            15 => 'Coordinador supervisor o especialista'
        ],
        3 => [
            16 => 'Profesional Senior',
            17 => 'Profesional',
        ],
        4 => [
            18 => 'Profesional Junior',
            19 => 'Auxiliar, asistencial y otros',
        ],
    ];

    const CONTRATO_TIPO = [
        1 => 'Definido',
        2 => 'Contrato de aprendizaje',
        3 => 'Indefinido',
        4 => 'Por obra o labor',
        5 => 'Prestación de servicios',
        6 => 'Otro'
    ];

    const INTENSIDAD_HORARIA = [
        1 => 'Por Hora',
        2 => 'Semanal',
        3 => 'Mensual',
    ];

    const RANGO_SALARIO = [
        1 => 'Menos de $1',
        2 => '$1 a $1,5',
        3 => '$1,5 a $2',
        4 => '$2 a $2,5',
        5 => '$2,5 a $3',
        6 => '$3 a $3,5',
        7 => '$3,5 a $4',
        8 => '$4 a $4,5',
        9 => '$4,5 a $5,5',
        10 => '$5,5 a $6',
        11 => '$6 a $8',
        12 => '$8 a $10',
        13 => '$10 a $12,5',
        14 => '$12,5 a $15',
        15 => '$15 a $18',
        16 => '$18 a $21',
        17 => 'Más de $21',
    ];

    const EXPERIENCIA = [
        1 => 'Sin experiencia',
        2 => 'Menos de un año',
        3 => '1',
        4 => '2',
        5 => '3',
        6 => '4',
        7 => '5',
        8 => '6',
        9 => '7',
        10 => '8',
        11 => '9',
        12 => '10',
        13 => '11',
        14 => '12',
        15 => '13',
        16 => '14',
        17 => '15',
        18 => 'Más de 15'
    ];

    const IDIOMA_CODIGO = [
        '01' => 'INGLÉS',
        '02' => 'ESPAÑOL',
        '03' => 'FRANCÉS',
        '04' => 'ITALIANO',
        '05' => 'ALEMÁN',
        '06' => 'CHINO',
        '07' => 'HOLANDÉS',
        '08' => 'JAPONÉS',
        '09' => 'MANDARÍN',
        '10' => 'PORTUGUÉS'
    ];

    const IDIOMA_DESTREZA = [
        2 => 'BAJO',
        3 => 'MEDIO',
        4 => 'ALTO',
        5 => 'SUPERIOR'
    ];

    const GENERO = [
        1 => 'FEMENINO',
        2 => 'MASCULINO',
        3 => 'OTRO'
    ];
    const EMPRESA = [
        1 => 'PTA',
        2 => 'SERVILABOR'
    ];
}