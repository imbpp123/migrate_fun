<?php

namespace App\ETL;

use App\Entity\ChunkEntity;

interface ETLInterface
{
    public function getChunkSize(): int;
    public function getMaxId(): int;
    public function getChunkToUpdate(): ChunkEntity;
}
