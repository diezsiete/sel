<?php


namespace App\Exception;


use Exception;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class UploadedFileValidationErrorsException extends Exception
{
    /**
     * @var ConstraintViolationListInterface|array
     */
    protected $errors;

    public static function create($errors, $messsage = "Validation file error(s)")
    {
        return (new static($messsage))->setErrors($errors);
    }

    /**
     * @return ConstraintViolationListInterface|array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param ConstraintViolationListInterface|array $errors
     * @return UploadedFileValidationErrorsException
     */
    public function setErrors($errors): UploadedFileValidationErrorsException
    {
        $this->errors = $errors;
        return $this;
    }


}