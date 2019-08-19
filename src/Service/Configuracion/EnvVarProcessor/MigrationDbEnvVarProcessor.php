<?php


namespace App\Service\Configuracion\EnvVarProcessor;


use Symfony\Component\DependencyInjection\EnvVarProcessorInterface;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

class MigrationDbEnvVarProcessor implements EnvVarProcessorInterface
{

    /**
     * Returns the value of the given variable as managed by the current instance.
     *
     * @param string $prefix The namespace of the variable
     * @param string $name The name of the variable within the namespace
     * @param \Closure $getEnv A closure that allows fetching more env vars
     *
     * @return mixed
     *
     * @throws RuntimeException on error
     */
    public function getEnv($prefix, $name, \Closure $getEnv)
    {
        $empresaKey = 'EMPRESA';
        $env = $getEnv($name);
        $empresa = $getEnv($empresaKey);

        return str_replace($empresaKey, strtolower($empresa), $env);
    }

    /**
     * @return string[] The PHP-types managed by getEnv(), keyed by prefixes
     */
    public static function getProvidedTypes()
    {
        return [
            'migrationdb' => 'string',
        ];
    }
}