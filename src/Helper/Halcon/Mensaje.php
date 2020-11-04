<?php


namespace App\Helper\Halcon;


use App\Service\Configuracion\Halcon\Halcon;
use App\Service\HttpClient;
use Symfony\Component\Mime\Part\DataPart;

/**
 * @property string[] to
 * @property string[] cc
 * @property string[] bcc
 * @property string subject
 * @property string html
 * @property DataPart[] attachments
 */
class Mensaje
{
    private $to = [];
    private $cc = [];
    private $bcc = [];
    private $subject = '';
    private $text = '';
    private $html = '';
    private $attachments = [];

    public function __get($name)
    {
        return $this->$name;
    }

    public function to(...$addresses)
    {
        return $this->addAddressesToProperty('to', $addresses);
    }

    public function cc(...$addresses)
    {
        return $this->addAddressesToProperty('cc', $addresses);
    }

    public function bcc(...$addresses)
    {
        return $this->addAddressesToProperty('bcc', $addresses);
    }

    public function subject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    public function text($body)
    {
        $this->text = $body;
        return $this;
    }

    public function html($body)
    {
        $this->html = $body;
        return $this;
    }

    /**
     * @param string[]|string[][] $paths
     * @return $this
     */
    public function attachFromPath(...$paths)
    {
        if(count($paths) === 1 && is_array($paths[0])) {
            $paths = $paths[0];
        }
        foreach($paths as $path) {
            $this->attachments[] = $path;
        }
        return $this;
    }

    private function addAddressesToProperty($property, $addresses)
    {
        foreach($addresses as $address) {
            $this->$property[] = $address;
        }
        return $this;
    }
}