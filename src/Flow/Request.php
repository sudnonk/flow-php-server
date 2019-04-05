<?php

namespace Flow;

use Psr\Http\Message\UploadedFileInterface;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\ServerRequestFactory;
use Zend\Diactoros\UploadedFile;

class Request implements RequestInterface
{
    /**
     * Request parameters
     *
     * @var array
     */
    protected $params;

    /**
     * File
     *
     * @var UploadedFileInterface|null
     */
    protected $file;

    /**
     * Constructor
     *
     * @param ServerRequest|null $serverRequest
     */
    public function __construct(ServerRequest $serverRequest = null)
    {
        if ($serverRequest === null) {
            $serverRequest = ServerRequestFactory::fromGlobals();
        }

        //$_REQUEST contains QueryParams and CookieParams.
        $params = array_merge($serverRequest->getQueryParams(), $serverRequest->getCookieParams());

        $uploaded_files = $serverRequest->getUploadedFiles();
        if (isset($uploaded_files['file']) && $uploaded_files['file'] instanceof UploadedFile) {
            $file = $uploaded_files['file'];
        } else {
            $file = null;
        }

        $this->params = $params;
        $this->file = $file;
    }

    /**
     * Get parameter value
     *
     * @param string $name
     *
     * @return string|int|null
     */
    public function getParam($name)
    {
        return isset($this->params[$name]) ? $this->params[$name] : null;
    }

    /**
     * Get uploaded file name
     *
     * @return string|null
     */
    public function getFileName()
    {
        return $this->getParam('flowFilename');
    }

    /**
     * Get total file size in bytes
     *
     * @return int|null
     */
    public function getTotalSize()
    {
        return $this->getParam('flowTotalSize');
    }

    /**
     * Get file unique identifier
     *
     * @return string|null
     */
    public function getIdentifier()
    {
        return $this->getParam('flowIdentifier');
    }

    /**
     * Get file relative path
     *
     * @return string|null
     */
    public function getRelativePath()
    {
        return $this->getParam('flowRelativePath');
    }

    /**
     * Get total chunks number
     *
     * @return int|null
     */
    public function getTotalChunks()
    {
        return $this->getParam('flowTotalChunks');
    }

    /**
     * Get default chunk size
     *
     * @return int|null
     */
    public function getDefaultChunkSize()
    {
        return $this->getParam('flowChunkSize');
    }

    /**
     * Get current uploaded chunk number, starts with 1
     *
     * @return int|null
     */
    public function getCurrentChunkNumber()
    {
        return $this->getParam('flowChunkNumber');
    }

    /**
     * Get current uploaded chunk size
     *
     * @return int|null
     */
    public function getCurrentChunkSize()
    {
        return $this->getParam('flowCurrentChunkSize');
    }

    /**
     * Return UploadedFile
     *
     * @return UploadedFileInterface|null
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Checks if request is formed by fusty flow
     *
     * @return bool
     */
    public function isFustyFlowRequest()
    {
        return false;
    }
}
