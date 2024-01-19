<?php

function classAutoload($directory) {
	if(is_dir($directory)) {
		$directoryContents = scandir($directory);
        $directoryContents = array_slice($directoryContents, 2); //remove . and ..

		foreach($directoryContents as $element) {
            $elementPath = $directory . '/' . $element;

			if(is_dir($elementPath)) {
                classAutoload($elementPath);
			} elseif(strpos($element, '.php') !== false) {
                include_once($elementPath);
			}
		}
	}
}

classAutoload(__DIR__ . '/src');