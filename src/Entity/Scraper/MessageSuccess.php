<?php


namespace App\Entity\Scraper;

use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity()
 * @ORM\Table(name="scraper_message_success", indexes={
 *     @ORM\Index(name="idx_message_success_queue_success", columns={"queue_name"}),
 *     @ORM\Index(name="idx_message_success_available_at_success", columns={"available_at"}),
 *     @ORM\Index(name="idx_message_success_delivered_at_success", columns={"delivered_at"})
 * })
 */
class MessageSuccess extends MessageSuperclassBase
{

}