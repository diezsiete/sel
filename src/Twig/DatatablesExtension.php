<?php /** @noinspection PhpComposerExtensionStubsInspection */


namespace App\Twig;


use App\DataTable\Column\ActionsColumn\ActionsColumn;
use Omines\DataTablesBundle\DataTable;
use Twig\TwigFunction;

class DatatablesExtension extends \Omines\DataTablesBundle\Twig\DataTablesExtension
{
    public function getFunctions()
    {
        $functions = parent::getFunctions();
        $originalCallable = $functions[0]->getCallable();

        return [
            new TwigFunction('sel_datatable_settings', function (DataTable $dataTable) use ($originalCallable) {
                $hasActions = array_reduce($dataTable->getColumns(), function ($prev, $column) {
                    return $prev ? $prev : $column instanceof ActionsColumn;
                }, false);
                return $originalCallable($dataTable) . ", " . json_encode([
                    "fixedHeader" => true,
                    "hasActions" => $hasActions
                ]);
            }, ['is_safe' => ['html']])
        ];
    }
}