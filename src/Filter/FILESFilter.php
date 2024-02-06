<?php

namespace App\Filter;

use App\Abstracts\AbstractFilter;
use App\Entity\Request;
use App\Exception\FilterException;

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
                    throw new FilterException($this);
                }
            }

            $maxFileSize = CONFIG['FILTER_FILES_MAX_SIZE'];
            if ($maxFileSize !== 'null') {
                $maxFileSize = max((int) $maxFileSize, 1);

                foreach ($files as $file) {
                    if ($file['size'] > $maxFileSize) {
                        throw new FilterException($this);
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
                        throw new FilterException($this);
                    }

                    if (in_array(ltrim($fileExtension, '.'), $fileExtensions, true)) {
                        throw new FilterException($this);
                    }
                }
            }
        }
    }
}