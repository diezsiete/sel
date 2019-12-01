<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Serializer\Scraper;


use App\Message\Scraper\UpdateHvInNovasoft;
use App\Message\Scraper\UpdateHvInNovasoftSuccess;
use App\Messenger\HvIdStamp;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Stamp\RedeliveryStamp;
use Symfony\Component\Messenger\Transport\Serialization\PhpSerializer;

/**
 * @author Samuel Roze <samuel.roze@gmail.com>
 *
 * @experimental in 4.3
 */
class HvMessageSerializer extends PhpSerializer
{
    public function encode(Envelope $envelope): array
    {
        /** @var UpdateHvInNovasoft|UpdateHvInNovasoftSuccess|object $message */
        $message = $envelope->getMessage();
        if(($message instanceof UpdateHvInNovasoft || $message instanceof UpdateHvInNovasoftSuccess)
            && (null === $envelope->last(HvIdStamp::class) || $envelope->last(RedeliveryStamp::class))){
            $envelope = $envelope->with(new HvIdStamp($message->getHvId()));

            $encode = parent::encode($envelope);
            $encode['headers']['hvId'] = $message->getHvId();
            return $encode;
        }

        return parent::encode($envelope);
    }
}
