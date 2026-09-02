<?php

namespace Webkul\Orders\Enums;

/**
 * Order lifecycle mirrored from the channel / Symfonia. The console is a read
 * model (doc §3b) — statuses are reflected here, never authored.
 */
enum OrderStatus: string
{
    case New = 'new';
    case Sent = 'sent';
    case Cancelled = 'cancelled';
    case Error = 'error';

    public function label(): string
    {
        return 'orders::app.statuses.'.$this->value;
    }

    /**
     * Precompiled theme label class, matching the BaseLinker colour cues ops
     * already read: new = blue, sent = green, cancelled/error = red.
     */
    public function labelClass(): string
    {
        return match ($this) {
            self::New       => 'label-info',
            self::Sent      => 'label-active',
            self::Cancelled => 'label-canceled',
            self::Error     => 'label-canceled',
        };
    }
}
