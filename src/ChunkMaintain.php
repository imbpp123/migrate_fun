<?php

namespace App;

use App\Entity\ChunkEntity;
use App\ETL\ETLInterface;
use App\Service\ChunkService;

class ChunkMaintain
{
    private ChunkService $chunkService;

    public function __construct(ChunkService $chunkService)
    {
        $this->chunkService = $chunkService;
    }

    public function run(ETLInterface  $etl)
    {
        $chunkSize = $etl->getChunkSize();
        $chunkCount = ceil($etl->getMaxId() / $chunkSize);
        $etlName = get_class($etl);

        $affected = $this->chunkService->removeChunksIfSizeIsNotEqual($etlName, $chunkSize);

        echo "Chunks deleted: " . $affected . PHP_EOL;
        echo "Chunk size: " . $chunkSize . PHP_EOL;
        echo "Chunk count: " . $chunkCount . PHP_EOL;

        for ($i = 0; $i < $chunkCount; $i++) {
            $chunk = new ChunkEntity();
            $chunk->startId = $i * $chunkSize + 1;
            $chunk->stopId = $i * $chunkSize + $chunkSize;
            $chunk->size = $chunkSize;
            $chunk->etl = $etlName;

            if ($this->chunkService->isExists($chunk)) {
                continue;
            }

            $this->chunkService->save($chunk);
        }
    }
}
