<?php


namespace App\DataTable;


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

        return $dataTable;
    }
}