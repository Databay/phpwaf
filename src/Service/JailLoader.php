<?php

namespace App\Service;

use App\Abstracts\AbstractFileLoader;

class JailLoader extends AbstractFileLoader
{
    const DEFAULT_JAILFILE_PATH = __DIR__ . '/../../jail.php';

    private static $jails = null;

    public static function load(): array
    {
        // Singleton
        if (isset(self::$jails)) {
            return self::$jails;
        }

        $jails = [];

        // Allows php files to be used to use the benefits of OPCache
        if (substr(self::DEFAULT_JAILFILE_PATH, -4) === '.php') {
            $jails = include(self::DEFAULT_JAILFILE_PATH);
            return is_array($jails) ? array_filter($jails) : [];
        }

        return self::$jails = $jails;
    }

    public static function save(array $jails): bool
    {
        $data = '<?php' . PHP_EOL . 'return [' . PHP_EOL;

        foreach ($jails as $key => $value) {
            $data .= "\t" . '\'' . $key . '\' => \'' . $value . '\',' . PHP_EOL;
        }

        return file_put_contents(self::DEFAULT_JAILFILE_PATH, $data . '];') && self::$jails = $jails;
    }
}