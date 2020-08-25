<?php

namespace App\ETL;

use App\Entity\ChunkEntity;

class ExampleETL implements ETLInterface
{
    public function getChunkSize(): int
    {
        return 10000;
    }

    public function getMaxId(): int
    {
        return 'select max(id) from table1';
    }

    public function getChunkToUpdate(): ChunkEntity
    {
        /*
         * SELECT chunk FROM updater_chunk table WHERE max(chunk.updated_at) AND chunk.needs_update = false LIMIT 1;
         */
        return new ChunkEntity();
    }
}
