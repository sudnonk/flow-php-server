<?php

namespace Flow;

use Zend\Diactoros\ServerRequest;

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
     * @param ServerRequest|null $serverRequest
     */
    public function __construct(ServerRequest $serverRequest = null)
    {
        parent::__construct($serverRequest);

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
