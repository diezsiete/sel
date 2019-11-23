<?php


namespace App\Entity\Scraper;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="scraper_message", indexes={
 *     @ORM\Index(name="idx_message_queue_success", columns={"queue_name"}),
 *     @ORM\Index(name="idx_message_available_at_success", columns={"available_at"}),
 *     @ORM\Index(name="idx_message_delivered_at_success", columns={"delivered_at"})
 * })
 */
class Message extends MessageSuperclassBase
{

}