<?php


namespace App\Annotation\Serializer;

/**
 * @Annotation
 * @Target({"PROPERTY"})
 */
class NormalizeFunction
{
    public $function;
    public $groups = [];

    public function __construct($data)
    {
        $this->function = $data['value'];
        if(isset($data['groups'])) {
            $this->groups = is_array($data['groups']) ? $data['groups'] : [$data['groups']];
        }
    }
}