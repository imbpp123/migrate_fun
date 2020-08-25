<?php

namespace App\Entity;

class ChunkEntity
{
    public ?int $id = null;
    public ?int $startId = null;
    public ?int $stopId = null;
    public ?int $size = null;
    public ?string $etl = null;
    public ?string $hash = null;

    public function isEmpty(): bool
    {
        return $this->id === null;
    }
}
