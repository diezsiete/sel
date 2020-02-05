<?php

namespace App\Validator\Hv;

use App\Form\Model\HvDatosBasicosModel;
use App\Repository\Main\DptoRepository;
use App\Repository\Main\PaisRepository;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UbicacionValidator extends ConstraintValidator
{
    /**
     * @var PaisRepository
     */
    private $paisRepo;
    /**
     * @var DptoRepository
     */
    private $dptoRepo;
    /**
     * @var PropertyAccessorInterface
     */
    private $propertyAccessor;

    public function __construct(PaisRepository $paisRepo, DptoRepository $dptoRepo, PropertyAccessorInterface $propertyAccessor)
    {
        $this->paisRepo = $paisRepo;
        $this->dptoRepo = $dptoRepo;
        $this->propertyAccessor = $propertyAccessor;
    }

    /**
     * @param HvDatosBasicosModel $value
     * @param Constraint|Ubicacion $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if (null === $value || '' === $value) {
            return;
        }

        $ubicacionFields = ['ident', 'nac', 'resi'];
        foreach($ubicacionFields as $ubicacionField) {
            $failed = $this->validateUbicacion($value, $ubicacionField);
            if ($failed) {
                $violationBuilder = $this->context->buildViolation($constraint->message, [
                    '{{ field }}' => $failed === 'dpto' ? 'departamento' : $failed
                ]);
                $violationBuilder->atPath($ubicacionField . ucfirst($failed));
                $violationBuilder->addViolation();
            }
        }
    }

    /**
     * @param HvDatosBasicosModel $entity
     * @param string $ubicacionFieldPrefix
     * @return false|string
     */
    private function validateUbicacion($entity, $ubicacionFieldPrefix)
    {
        $failed = false;
        $pais = $this->propertyAccessor->getValue($entity, $ubicacionFieldPrefix . "Pais");
        if($pais) {
            $dpto = $this->propertyAccessor->getValue($entity, $ubicacionFieldPrefix . "Dpto");
            if(!$dpto && $this->paisRepo->paisHasDptos($pais)) {
                $failed = 'dpto';
            } else if($dpto) {
                // TODO validar que el departamento si pertenezca al pais seleccionado
                $ciudad = $this->propertyAccessor->getValue($entity, $ubicacionFieldPrefix . "Ciudad");
                if(!$ciudad && $this->dptoRepo->dptoHasCiudades($dpto)) {
                    $failed = 'ciudad';
                } else if($ciudad) {
                    // TODO validar que la ciudad si pertenezca al dpto seleccionado
                }
            }

        }
        return $failed;
    }
}
