<?php


namespace App\DataTable;


use App\DataTable\Type\ServicioEmpleados\ServicioEmpleadosDataTableType;
use App\Entity\Main\Usuario;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapterEvents;
use Omines\DataTablesBundle\DataTable;
use Omines\DataTablesBundle\DataTableFactory;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;

class SelDataTableFactory extends DataTableFactory
{
    /**
     * @param array $options
     * @return DataTable
     */
    public function create(array $options = [])
    {
        $config = $this->config;

        return (new SelDataTable($this->eventDispatcher, array_merge($config['options'] ?? [], $options), $this->instantiator))
            ->setRenderer($this->renderer)
            ->setMethod($config['method'] ?? Request::METHOD_POST)
            ->setPersistState($config['persist_state'] ?? 'fragment')
            ->setTranslationDomain($config['translation_domain'] ?? 'messages')
            ->setLanguageFromCDN($config['language_from_cdn'] ?? true)
            ->setTemplate($config['template'] ?? DataTable::DEFAULT_TEMPLATE, $config['template_parameters'] ?? [])
            ;
    }


    public function createFromType($type, array $typeOptions = [], array $options = [])
    {
        /** @var SelDataTable $dataTable */
        $dataTable = parent::createFromType($type, $typeOptions, $options);

        if(isset($typeOptions['form'])) {
            $dataTable->setForm($typeOptions['form']);
            /** @var FormBuilderInterface $builder */
            $builder = $typeOptions['form'];
            $builder->add('datatable', HiddenType::class);
        }

        $type = $this->resolveType($type);
        if($type instanceof PreQueryListenerInterface) {
            $this->eventDispatcher->addListener(ORMAdapterEvents::PRE_QUERY, [$type, 'preQueryListener']);
        }

        return $dataTable;
    }

    public function createFromServicioEmpleadosType($type, Usuario $usuario, array $options = [])
    {
        /** @var  ServicioEmpleadosDataTableType $type */
        $type = $this->resolveType($type);
        $type->setUsuario($usuario);
        return $this->createFromType($type, [], $options);
    }



    private function resolveType($type)
    {
        if (is_string($type)) {
            $name = $type;
            if (isset($this->resolvedTypes[$name])) {
                $type = $this->resolvedTypes[$name];
            } else {
                $this->resolvedTypes[$name] = $type = $this->instantiator->getType($name);
            }
        }
        return $type;
    }
}