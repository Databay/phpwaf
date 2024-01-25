<?php

namespace App\Filter;

use App\Abstracts\AbstractFilter;
use App\Entity\Request;

class FILESFilter extends AbstractFilter
{
    public function apply(Request $request): bool
    {
        if ($this->isFilterActive()) {
            $files = $request->getFiles();

            $maxFilesCount = CONFIG['FILTER_FILES_MAX_COUNT'];
            if ($maxFilesCount !== 'null') {
                $maxFilesCount = max((int) $maxFilesCount, 0);

                if (count($files) > $maxFilesCount) {
                    return false;
                }
            }

            $maxFileSize = CONFIG['FILTER_FILES_MAX_SIZE'];
            if ($maxFileSize !== 'null') {
                $maxFileSize = max((int) $maxFileSize, 1);

                foreach ($files as $file) {
                    if ($file['size'] > $maxFileSize) {
                        return false;
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
                        return false;
                    }

                    if (in_array(ltrim($fileExtension, '.'), $fileExtensions, true)) {
                        return false;
                    }
                }
            }
        }

        return true;
    }
}