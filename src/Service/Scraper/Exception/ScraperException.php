<?php


namespace App\Service\Scraper\Exception;



use App\Service\Scraper\Response\ResponseManager;
use Exception;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;


/**
 * Errores provenientes de scraper
 * Implementa HttpExceptionInterface para que desde messenger podamos leer el log como headers
 * Class ScraperException
 * @package App\Service\Scraper\Exception
 */
class ScraperException extends Exception implements HttpExceptionInterface
{
    protected $log;

    protected $code;

    /**
     * @param $message
     * @param $code
     * @param $log
     * @return ScraperConflictException|ScraperException|ScraperNotFoundException|ScraperTimeoutException
     */
    public static function create($message, $code, $log)
    {
        switch($code) {
            case ResponseManager::NOTFOUND:
                $exception = new ScraperNotFoundException($message);
                break;
            case ResponseManager::CONFLICT:
                $exception = new ScraperConflictException($message);
                break;
            case ResponseManager::TIMEOUT:
                $exception = new ScraperTimeoutException($message);
                break;
            default:
                $exception = new ScraperException($message);
        }
        return $exception->setCode($code)->setLog($log);
    }

    /**
     * @param $log
     * @return $this
     */
    protected function setLog($log)
    {
        $this->log = $log;
        return $this;
    }

    /**
     * @param $code
     * @return $this
     */
    protected function setCode($code)
    {
        $this->code = $code;
        return $this;
    }


    /**
     * Returns the status code.
     *
     * @return int An HTTP response status code
     */
    public function getStatusCode()
    {
        return $this->code;
    }

    /**
     * Returns response headers.
     *
     * @return array Response headers
     */
    public function getHeaders()
    {
        return explode("\n", $this->log);
    }

    public function getLog()
    {
        return $this->log;
    }
}