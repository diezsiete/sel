<?php


namespace App\Event\EventSubscriber\Archivo;


use App\Entity\Main\Usuario;
use App\Repository\Main\UsuarioRepository;
use App\Service\File\ArchivoManager;
use Oneup\UploaderBundle\Event\PostPersistEvent;
use Oneup\UploaderBundle\Event\PreUploadEvent;
//use Oneup\UploaderBundle\Uploader\File\FilesystemFile;
use Oneup\UploaderBundle\Uploader\File\FlysystemFile;
use Oneup\UploaderBundle\UploadEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class UploadSubscriber implements EventSubscriberInterface
{

    /**
     * @var ArchivoManager
     */
    private $archivoManager;
    /**
     * @var UsuarioRepository
     */
    private $usuarioRepo;

    private $originalFiles = [];
    /**
     * @var NormalizerInterface
     */
    private $normalizer;

    public function __construct(ArchivoManager $archivoManager, UsuarioRepository $usuarioRepo, NormalizerInterface $normalizer)
    {
        $this->archivoManager = $archivoManager;
        $this->usuarioRepo = $usuarioRepo;
        $this->normalizer = $normalizer;
    }

    public function preUpload(PreUploadEvent $event): void
    {
        /** @var FlysystemFile $file */
        $file = $event->getFile();
        //$this->originalFiles[$file->getInode()] = $this->archivoManager->getOriginalFilenameWithoutExtension($file);
    }

    public function onUpload(PostPersistEvent $event)
    {
        $request = $event->getRequest();
        /** @var Usuario $owner */
        $owner = $this->usuarioRepo->find($request->get('owner'));

        /** @var FlysystemFile $file */
        $file = $event->getFile();
        //$inode = $file->getInode();
        $inode = 'No-existe';
        if(isset($this->originalFiles[$inode])) {
            $originalFilename = $this->originalFiles[$file->getInode()];
            unset($this->originalFiles[$file->getInode()]);
        } else {
            $originalFilename = $file->getPathname();
        }


        $archivo = $this->archivoManager->createArchivo(
            $file->getPathname(),
            $originalFilename,
            $file->getExtension(),
            $file->getSize(),
            $file->getExtension(), $owner);


        //if everything went fine
        $response = $event->getResponse();
        $response[] = $this->normalizer->normalize($archivo, null, ['groups' => ['api']]);
        return $response;
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents()
    {
        return [
            UploadEvents::preUpload('archivo') => ['preUpload'],
            UploadEvents::postPersist('archivo') => ['onUpload']
        ];
    }
}