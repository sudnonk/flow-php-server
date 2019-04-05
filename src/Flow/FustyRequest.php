<?php

namespace Flow;

use Psr\Http\Message\UploadedFileInterface;

/**
 * Class FustyRequest
 *
 * Imitates single file request as a single chunk file upload
 *
 * @package Flow
 */
class FustyRequest extends Request
{
    private $isFusty = false;

    /**
     * FustyRequest constructor.
     * @param array|null                 $params
     * @param UploadedFileInterface|null $file
     */
    public function __construct(array $params = null, UploadedFileInterface $file = null)
    {
        parent::__construct($params, $file);

        $this->isFusty = $this->getTotalSize() === null && $this->getFileName() && $this->getFile();

        if ($this->isFusty) {
            $this->params['flowTotalSize'] = !is_null($this->file->getSize()) ? $this->file->getSize() : 0;
            $this->params['flowTotalChunks'] = 1;
            $this->params['flowChunkNumber'] = 1;
            $this->params['flowChunkSize'] = $this->params['flowTotalSize'];
            $this->params['flowCurrentChunkSize'] = $this->params['flowTotalSize'];
        }
    }

    /**
     * Checks if request is formed by fusty flow
     * @return bool
     */
    public function isFustyFlowRequest(): bool
    {
        return $this->isFusty;
    }
}
