<?php

namespace App\Validator\Hv;

use App\Repository\Hv\HvChildRepository;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class HvChildValidator extends ConstraintValidator
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var PropertyAccessorInterface
     */
    private $propertyAccessor;

    public function __construct(EntityManagerInterface $em, PropertyAccessorInterface $propertyAccessor)
    {
        $this->em = $em;
        $this->propertyAccessor = $propertyAccessor;
    }

    /**
     * @param \App\Entity\Estudio $value
     * @param Constraint|HvChild $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        $siblings = $this->getRepository($value)->findSiblings($value->getHv(), $value);


        if($constraint->rules) {
            foreach($constraint->rules as $rule) {
                foreach($siblings as $sibling) {
                    if (!$this->validateRule($rule['uniqueFields'], $rule['message'], $value, $sibling)) {
                        break;
                    }
                }
            }
        } else {
            foreach($siblings as $sibling) {
                if(!$this->validateRule($constraint->uniqueFields, $constraint->message, $value, $sibling)) {
                    break;
                }
            }
        }
    }

    /**
     * @param $entity
     * @return HvChildRepository|ObjectRepository
     */
    private function getRepository($entity)
    {
        return $this->em->getRepository(get_class($entity));
    }

    private function validateRule($accessors, $message, $entity, $sibling)
    {
        $equals = 0;
        foreach($accessors as $accessor) {
            if(preg_match('/(^.+)\((.*)\)/', $accessor, $matches)) {
                $siblingValue = call_user_func($matches[1], $this->propertyAccessor->getValue($sibling, $matches[2]));
                $entityValue = call_user_func($matches[1], $this->propertyAccessor->getValue($entity, $matches[2]));
            } else {
                $siblingValue = $this->propertyAccessor->getValue($sibling, $accessor);
                $entityValue = $this->propertyAccessor->getValue($entity, $accessor);
            }
            if ($siblingValue === $entityValue) {
                $equals++;
            }
        }

        if($equals === count($accessors)) {
            $this->context
                ->buildViolation($message)
                ->addViolation();
            return false;
        }
        return true;
    }
}
