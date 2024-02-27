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
            $files = self::retrieveFiles($request);

            $maxFilesCount = CONFIG['FILTER_FILES_MAX_COUNT'];
            if ($maxFilesCount !== 'null') {
                $maxFilesCount = max((int) $maxFilesCount, 0);
                $fileCount = count($files);

                if ($fileCount > $maxFilesCount) {
                    throw FilterExceptionFactory::getException($this, $request, null, 'Too many files uploaded (' . $fileCount . ')');
                }
            }

            $maxFileSize = CONFIG['FILTER_FILES_MAX_SIZE'];
            if ($maxFileSize !== 'null') {
                $maxFileSize = max((int) $maxFileSize, 1);

                foreach ($files as $file) {
                    $fileSize = $file['size'];
                    if ($fileSize > $maxFileSize) {
                        throw FilterExceptionFactory::getException(
                            $this,
                            $request,
                            'Too large file uploaded (' . self::byteConvert($fileSize) . ')' . (CONFIG['FILTER_FILES_DETAILED_LOG'] === 'true' ?  ' ' . self::fileJsonEncode($file) : '')
                        );
                    }
                }
            }

            $allowedExtensions = CONFIG['FILTER_FILES_ALLOWED_EXTENSIONS'];
            if ($this->isStringValidList($allowedExtensions)) {
                $fileExtensionString = trim($allowedExtensions, "[]");
                $fileExtensions = explode(',', $fileExtensionString);

                foreach ($files as $file) {
                    $fileExtension = strstr($file['name'], '.');

                    if ($fileExtension === false) {
                        throw FilterExceptionFactory::getException(
                            $this,
                            $request,
                            'File with no file extension uploaded' . (CONFIG['FILTER_FILES_DETAILED_LOG'] === 'true' ?  ' ' . self::fileJsonEncode($file) : '')
                        );
                    }

                    $fileExtension = ltrim($fileExtension, '.');

                    if (!in_array($fileExtension, $fileExtensions, true)) {
                        throw FilterExceptionFactory::getException(
                            $this,
                            $request,
                            'Not allowed file extension uploaded (' . $fileExtension .')' . (CONFIG['FILTER_FILES_DETAILED_LOG'] === 'true' ?  ' ' . self::fileJsonEncode($file) : '')
                        );
                    }
                }
            }
        }
    }

    private static function retrieveFiles(Request $request): array
    {
        $preFiles = $request->getFiles();
        $postFiles = [];

        foreach ($preFiles as $preFile) {
            if (is_array($preFile['name'])) {
                foreach ($preFile['name'] as $key => $name) {
                    $postFiles[] = [
                        'name' => $name,
                        'type' => $preFile['type'][$key],
                        'tmp_name' => $preFile['tmp_name'][$key],
                        'error' => $preFile['error'][$key],
                        'size' => $preFile['size'][$key]
                    ];
                }
            } else {
                $postFiles[] = $preFile;
            }
        }

        return $postFiles;
    }

    private static function byteConvert(int $bytes): string
    {
        $bytes = max($bytes, 1);

        $s = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
        $e = floor(log($bytes, 1024));

        return round($bytes/ (1024 ** $e), 2) . $s[$e];
    }

    private static function fileJsonEncode(array $file): string
    {
        return json_encode([
            'name' => $file['name'],
            'size' => $file['size'],
            'type' => $file['type']
        ]);
    }
}