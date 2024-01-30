<?php

namespace App\Service;

use App\Abstracts\AbstractFileLoader;

class JailLoader extends AbstractFileLoader
{
    const DEFAULT_JAILFILE_PATH = __DIR__ . '/../../jail.php';

    public static function load(): array
    {
        $jails = [];

        // Allows php files to be used to use the benefits of OPCache
        if (substr(self::DEFAULT_JAILFILE_PATH, -4) === '.php') {
            $jails = include(self::DEFAULT_JAILFILE_PATH);
            return is_array($jails) ? array_filter($jails) : [];
        }

        return $jails;
    }

    public static function save(array $jails): bool
    {
        $data = <<<END
<?php
return [
END;
        $data .= PHP_EOL;

        foreach ($jails as $key => $value) {
            $data .= "\t" . '\'' . $key . '\' => \'' . $value . '\',' . PHP_EOL;
        }

        return file_put_contents(self::DEFAULT_JAILFILE_PATH, $data . '];');
    }
}