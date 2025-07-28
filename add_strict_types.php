<?php

$files = glob('app/**/*.php', GLOB_BRACE);

foreach ($files as $file) {
    $content = file_get_contents($file);

    // Check if the file already has strict_types declaration
    if (strpos($content, 'declare(strict_types=1)') === false) {
        // Replace the opening PHP tag with the tag plus the declaration
        $newContent = str_replace('<?php', '<?php'.PHP_EOL.PHP_EOL.'declare(strict_types=1);', $content);

        // Write the modified content back to the file
        file_put_contents($file, $newContent);

        echo "Added strict_types declaration to $file\n";
    } else {
        echo "File $file already has strict_types declaration\n";
    }
}

echo "Completed adding strict_types declarations to all PHP files in app/\n";
