<?php

namespace App\Filter;

use App\Abstracts\AbstractFilter;
use App\Entity\Request;
use App\Exception\FilterException;
use App\Factory\FilterExceptionFactory;

class FILESFilter extends AbstractFilter
{
    /**
     * @throws FilterException
     */
    public function apply(Request $request)
    {
        if ($this->isFilterActive()) {
            $files = $request->getFiles();

            $maxFilesCount = CONFIG['FILTER_FILES_MAX_COUNT'];
            if ($maxFilesCount !== 'null') {
                $maxFilesCount = max((int) $maxFilesCount, 0);

                if (count($files) > $maxFilesCount) {
                    throw FilterExceptionFactory::getException($this, $request, 'Too many files uploaded');
                }
            }

            $maxFileSize = CONFIG['FILTER_FILES_MAX_SIZE'];
            if ($maxFileSize !== 'null') {
                $maxFileSize = max((int) $maxFileSize, 1);

                foreach ($files as $file) {
                    if ($file['size'] > $maxFileSize) {
                        throw FilterExceptionFactory::getException($this, $request, 'Too large file uploaded');
                    }
                }
            }

            $blockedExtensions = CONFIG['FILTER_FILES_BLOCKED_EXTENSIONS'];
            if ($this->isStringValidList($blockedExtensions)) {
                $fileExtensionString = trim($blockedExtensions, "[]");
                $fileExtensions = explode(',', $fileExtensionString);

                foreach ($files as $file) {
                    $fileExtension = strstr($file['name'], '.');

                    if ($fileExtension === false) {
                        return;
                    }

                    if (in_array(ltrim($fileExtension, '.'), $fileExtensions, true)) {
                        throw FilterExceptionFactory::getException($this, $request, 'Not allowed file extension uploaded');
                    }
                }
            }
        }
    }
}