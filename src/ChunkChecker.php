<?php

namespace App;

use App\ETL\ETLInterface;
use App\Factory\DbFactory;
use App\Service\ChunkService;

class ChunkChecker
{
    private DbFactory $dbFactory;
    private ChunkService $chunkService;

    public function __construct(ChunkService $chunkService)
    {
        $this->chunkService = $chunkService;
    }

    public function run(ETLInterface $etl): void
    {
        $etlName = get_class($etl);

        // get chunk to update
        $chunk = $etl->getChunkToCheck($etlName);

        // check chunk hash
        if (!$etl->isChunkHashOutdated($chunk)) {
            return;
        }

        $chunk->status = 1;

        $this->chunkService->save($chunk);
    }

    public function setDbFactory(DbFactory $dbFactory): void
    {
        $this->dbFactory = $dbFactory;
    }
}
