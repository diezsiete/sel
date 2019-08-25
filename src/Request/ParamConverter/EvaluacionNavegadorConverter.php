<?php


namespace App\Request\ParamConverter;


use App\Entity\Evaluacion\Diapositiva;
use App\Entity\Evaluacion\Evaluacion;
use App\Entity\Evaluacion\Modulo;
use App\Entity\Evaluacion\Pregunta\Pregunta;
use App\Entity\Evaluacion\Progreso;
use App\Repository\Evaluacion\ProgresoRepository;
use App\Service\Evaluacion\Navegador;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Security;

class EvaluacionNavegadorConverter implements ParamConverterInterface
{
    /**
     * @var Security
     */
    private $security;
    /**
     * @var RouterInterface
     */
    private $router;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * CatalogCategoryParamConverter constructor.
     *
     * @param Registry|null $registry
     */
    public function __construct(EntityManagerInterface $em, Security $security, RouterInterface $router)
    {
        $this->security = $security;
        $this->router = $router;
        $this->em = $em;
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
        $evaluacionSlug = $this->getRequiredAttribute($request, 'evaluacionSlug');

        /** @var ProgresoRepository $progresoRepository */
        $progresoRepository = $this->getRepo(Progreso::class);
        $evaluacion = $this->findBySlug(Evaluacion::class, $evaluacionSlug);

        $navegador = new Navegador($progresoRepository, $evaluacion, $this->security, $this->router, $this->em);


        if($diapositivaSlug = $request->attributes->get('diapositivaSlug')) {
            $navegador->setRouteDiapositiva(
                $this->findBySlug(Modulo::class, $this->getRequiredAttribute($request, 'moduloSlug')),
                $this->findBySlug(Diapositiva::class, $diapositivaSlug)
            );
        } else if ($preguntaId = $request->attributes->get('preguntaId')) {
            $modulo = $this->findBySlug(Modulo::class, $this->getRequiredAttribute($request, 'moduloSlug'));
            $pregunta = $this->findById(Pregunta::class, $preguntaId);

            if($preguntaDiapositivaSlug = $request->attributes->get('preguntaDiapositivaSlug')) {
                $preguntaDiapositiva = $this->findBySlug(Diapositiva::class, $preguntaDiapositivaSlug);
                $navegador->setRoutePreguntaDiapositiva($modulo, $pregunta, $preguntaDiapositiva);
            }
            else {
                $navegador->setRoutePregunta($modulo, $pregunta);
            }
        }

        $request->attributes->set($configuration->getName(), $navegador);
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
        return $className !== null && $className === Navegador::class;
    }

    private function findBySlug($fullClassName, $slug)
    {
        return $this->checkNotNull($this->getRepo($fullClassName)->findOneBy(['slug' => $slug]));
    }

    private function findById($fullClassName, $id)
    {
        return $this->checkNotNull($this->getRepo($fullClassName)->find($id));
    }

    private function getRepo($fullClassName)
    {
        return $this->em->getRepository($fullClassName);
    }

    private function getRequiredAttribute(Request $request, $attribute)
    {
        $value = $request->attributes->get($attribute);
        return $this->checkNotNull($value, $attribute . " es obligatorio");
    }

    private function checkNotNull($val, $message = "objetos requeridos faltantes")
    {
        if(is_array($val)) {
            foreach($val as $v) {
                $this->checkNotNull($v);
            }
        } else {
            if (!$val) {
                throw new NotFoundHttpException($message);
            }
        }
        return $val;
    }


}