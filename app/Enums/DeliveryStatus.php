<?php

namespace App\Enums;

enum DeliveryStatus: string
{
    case Pending = 'pending';
    case Reserved = 'reserved';
    case Sent = 'sent';
    case Deferred = 'deferred';
    case Failed = 'failed';
    case Suppressed = 'suppressed';
}
