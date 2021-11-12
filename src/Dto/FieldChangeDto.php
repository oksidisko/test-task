<?php

declare(strict_types=1);

namespace App\Dto;

class FieldChangeDto
{
    public string $old;
    public string $new;
    public string $field;
    public \DateTimeImmutable $date;
    // Тут храним пользователя, сделавшего изменения. Если null - значит система
    public ?string $user = null;
}
