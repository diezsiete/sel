<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Serializer;


use App\Message\UploadToNovasoft;
use App\Messenger\HvIdStamp;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Stamp\RedeliveryStamp;
use Symfony\Component\Messenger\Transport\Serialization\PhpSerializer;

/**
 * @author Samuel Roze <samuel.roze@gmail.com>
 *
 * @experimental in 4.3
 */
class ScraperMessageSerializer extends PhpSerializer
{
    public function encode(Envelope $envelope): array
    {
        if($envelope->getMessage() instanceof UploadToNovasoft && (null === $envelope->last(HvIdStamp::class) || $envelope->last(RedeliveryStamp::class))){
            /** @var UploadToNovasoft $message */
            $message = $envelope->getMessage();
            $envelope = $envelope->with(new HvIdStamp($message->getHvId()));

            $encode = parent::encode($envelope);
            $encode['headers']['hvId'] = $message->getHvId();
            return $encode;
        }

        return parent::encode($envelope);
    }
}
