<?php

namespace App\Validator\Hv;

use App\Entity\Hv\Estudio;
use App\Entity\Hv\HvEntity;
use App\Entity\Hv\Referencia;
use App\Repository\Hv\HvChildRepository;
use App\Service\Utils\Symbol;
use Doctrine\Common\Collections\ArrayCollection;
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
    /**
     * @var Symbol
     */
    private $symbol;

    public function __construct(EntityManagerInterface $em, PropertyAccessorInterface $propertyAccessor, Symbol $symbol)
    {
        $this->em = $em;
        $this->propertyAccessor = $propertyAccessor;
        $this->symbol = $symbol;
    }

    /**
     * @param Estudio|Referencia|HvEntity $value
     * @param Constraint|HvChild $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if($constraint->atLeastOneForEach) {
            $this->validateAtLeastOneForEach($value, $constraint);
        } else {
            $this->validateUniqueFields($value, $constraint);
        }
    }

    /**
     * @param HvEntity $value
     * @param Constraint|HvChild $constraint
     */
    private function validateUniqueFields($value, Constraint $constraint)
    {
        $siblings = [];
        if($hv = $value->getHv()) {
            if ($hv->getId()) {
                $siblings = $this->getRepository($value)->findSiblings($hv, $value);
            }
            // en api que se crea hv de un solo totazo, no se compara contra bd
            else {
                $siblings = $hv->getChildsExcept($this->symbol, $value);
            }
        }
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
     * @param Referencia[]|ArrayCollection $value
     * @param Constraint|HvChild $constraint
     * @return bool
     */
    private function validateAtLeastOneForEach($value, Constraint $constraint)
    {
        $databaseValues = null;
        foreach ($value as $child) {
            $childValue = $this->propertyAccessor->getValue($child, $constraint->atLeastOneForEach);
            if($databaseValues === null) {
                $databaseValues = $this->em->getRepository(get_class($childValue))->findAll();
            }
            $databaseValues = array_filter($databaseValues, function ($databaseValue) use ($childValue) {
                return $databaseValue !== $childValue;
            });
        }

        if($databaseValues) {
            $this->context
                ->buildViolation($constraint->message . '. Faltan de tipo: ' . (array_reduce($databaseValues, function ($carry, $item) {
                        return $carry . ($carry ? ', ' : '') . $item->getNombre();
                })))
                ->addViolation();
            return false;
        }
        return true;
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
