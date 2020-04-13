<?php


namespace App\Service\Utils;


class Varchar
{
    /**
     * Convierte string camelCase a camel_case
     * @param $input
     * @param string $glue
     * @return string
     */
    public function toSnakeCase($input, $glue = '_'): string
    {
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $input, $matches);
        $ret = $matches[0];
        foreach ($ret as &$match) {
            $match = $match === strtoupper($match) ? strtolower($match) : lcfirst($match);
        }
        return implode($glue, $ret);
    }

    /**
     * Convierte string camel_case a CamelCase
     * @param $string
     * @param string $separator
     * @return string|string[]
     */
    public function camelize($string, $separator = '_')
    {
        return lcfirst($this->pascalize($string, $separator));
    }


    public function pascalize($string, $separator = ['_', '-'])
    {
        return str_replace(' ', '', mb_convert_case(str_replace($separator, ' ', $string), MB_CASE_TITLE));
    }

    /**
     * Dada una url de tipo /api/:id ó /api/{id}, reemplaza id por los valores en $params
     * @param $url
     * @param array $params
     * @param string $parameterSyntax
     * @return string|string[]
     */
    public function addParametersToPath($url, $params = [], $parameterSyntax = ':')
    {
        $pattern = $parameterSyntax === ':' ? ':(\w+)' : '{(\w+)}';
        if($params && preg_match_all("/$pattern/", $url, $matches)) {
            for($i = 0, $iMax = count($matches[0]); $i < $iMax; $i++) {
                $url = isset($params[$matches[1][$i]])
                    ? str_replace($matches[0][$i], $params[$matches[1][$i]], $url)
                    : str_replace($matches[0][$i], $params[$i], $url);
            }
        }
        return $url;
    }
}