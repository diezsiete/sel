<?php


namespace App\Annotation\NapiClient;


/**
 * @Annotation
 * @Target({"CLASS"})
 * @Attributes(
 *     @Attribute("collectionOperations", type="array"),
 *     @Attribute("itemOperations", type="array")
 * )
 */
class NapiResource
{
    /**
     * @var array
     */
    public $itemOperations;

    /**
     * @var array
     */
    public $collectionOperations;

    public function __construct(array $values = [])
    {
        foreach ($values as $key => $value) {
            $key = (string) $key;
            $this->{$key} = $value;
        }
    }


}