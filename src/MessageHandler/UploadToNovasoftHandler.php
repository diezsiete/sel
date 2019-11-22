<?php


namespace App\MessageHandler;


use App\Entity\HvEntity;
use App\Message\UploadToNovasoft;
use App\Repository\HvRepository;
use App\Service\Scraper\HvScraper;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class UploadToNovasoftHandler implements MessageHandlerInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var HvScraper
     */
    private $scraper;
    /**
     * @var HvRepository
     */
    private $hvRepository;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(HvScraper $scraper, HvRepository $hvRepository, EntityManagerInterface $em)
    {
        $this->scraper = $scraper;
        $this->hvRepository = $hvRepository;
        $this->em = $em;
    }

    public function __invoke(UploadToNovasoft $uploadToNovasoft)
    {
        $this->em->clear();
        $hvId = $uploadToNovasoft->getHvId();
        $hv = $this->hvRepository->find($hvId);
        if(!$hv) {
            // exception retry
            // return removed
            if($this->logger) {
                $this->logger->error(sprintf('Hv %s no encontrada', $hvId));
            }
            return;
        }
        if(!$uploadToNovasoft->getChildId() && !$uploadToNovasoft->getAction() !== UploadToNovasoft::ACTION_CHILD_DELETE) {
            $response = $uploadToNovasoft->getAction() === UploadToNovasoft::ACTION_INSERT ?
                $this->scraper->postHv($hv) : $this->scraper->putHv($hv);
        } else {
            if($childId = $uploadToNovasoft->getChildId()) {
                /** @var HvEntity $hvChild */
                $hvChild = $this->em->getRepository($uploadToNovasoft->getChildClass())->find($childId);
                if ($uploadToNovasoft->getAction() === UploadToNovasoft::ACTION_CHILD_INSERT) {
                    dump("INSERT");
                    $response = $this->scraper->insertChild($hvChild);
                } else {
                    dump("UPDATE");
                    $this->scraper->updateChild($hvChild);
                }
            } else {
                dump("DELETE");
                $this->scraper->deleteChild($hv, $uploadToNovasoft->getChildClass());
            }
        }
    }
}