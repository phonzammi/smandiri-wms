<?php

namespace App\Enums;

enum OutgoingTransactionStatus: string
{
    case DRAFT = 'draft';
    case READY = 'ready';
    case DELIVERING = 'delivering';
    case DELIVERED = 'delivered';
    case CANCELED = 'canceled';

    public function isDraft(): bool
    {
        return $this === self::DRAFT;
    }

    public function isCanceled(): bool
    {
        return $this === self::CANCELED;
    }

    public function getLabelText(): string
    {
        return match ($this) {
            self::DRAFT => 'Belum Siap',
            self::READY => 'Siap Diantar',
            self::DELIVERING => 'Sedang Diantar',
            self::DELIVERED => 'Sampai Diantar',
            self::CANCELED => 'Dibatalkan',
        };
    }

    public function getLabelColor(): string
    {
        return match ($this) {
            self::DRAFT => 'warning',
            self::READY => 'info',
            self::DELIVERING => 'primary',
            self::DELIVERED => 'success',
            self::CANCELED => 'danger',
        };
    }

    public function getLabelBadge(): string
    {
        return sprintf('<span class="badge badge-pill badge-%s">%s</span>', $this->getLabelColor(), $this->getLabelText());
    }
}
