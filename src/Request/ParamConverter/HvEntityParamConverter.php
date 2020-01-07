<?php


namespace App\Request\ParamConverter;


use App\Constant\HvConstant;
use App\Entity\Hv\HvEntity;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\Common\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;

class HvEntityParamConverter implements ParamConverterInterface
{
    /**
     * @var Registry
     */
    protected $registry;
    /**
     * CatalogCategoryParamConverter constructor.
     *
     * @param Registry|null $registry
     */
    public function __construct(ManagerRegistry $registry = null)
    {
        $this->registry = $registry;
    }

    /**
     * Stores the object in the request.
     *
     * @param ParamConverter $configuration Contains the name, class and options of the object
     *
     * @return bool True if the object has been successfully set, else false
     */
    public function apply(Request $request, ParamConverter $configuration)
    {
        $entity = null;
        $id = $request->attributes->get('id');
        $entity = $request->attributes->get('entity');
        $className = $this->getEntityClass($entity);
        if ($className === null) {
            throw new NotFoundHttpException(
                sprintf('%s class not found by the @%s annotation.', $entity, $this->getAnnotationName($configuration)));
        }
        $fullClassName = "App\Entity\\".$className;
        if($id) {
            $repository = $this->registry
                ->getManagerForClass($fullClassName)
                ->getRepository($fullClassName);
            $entity = $repository->find($id);
            if(!$entity) {
                $message = sprintf('%s object not found by the @%s annotation.', $className, $this->getAnnotationName($configuration));
                throw new NotFoundHttpException($message);
            }
        } else {
            $entity = new $fullClassName();
        }

        $request->attributes->set($configuration->getName(), $entity);
        $request->attributes->set('formType', "App\Form\\" . $className . "FormType");

        return true;
    }

    /**
     * Checks if the object is supported.
     *
     * @return bool True if the object is supported, else false
     */
    public function supports(ParamConverter $configuration)
    {
        $className = $configuration->getClass();
        return $className !== null && $className === HvEntity::class;
    }

    private function getEntityClass($entityName)
    {
        $className = (new CamelCaseToSnakeCaseNameConverter(null, false))
            ->denormalize($entityName);
        return !in_array($className, HvConstant::HV_ENTITY) ? null : $className;
    }

    private function getAnnotationName(ParamConverter $configuration)
    {
        $r = new \ReflectionClass($configuration);
        return $r->getShortName();
    }
}