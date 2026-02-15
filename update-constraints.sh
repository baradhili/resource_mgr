#!/bin/bash

# Script to convert * constraints to actual locked versions
# Run this AFTER running composer install/update to populate composer.lock

if [ ! -f "composer.lock" ]; then
    echo "composer.lock not found. Run composer install first."
    exit 1
fi

# Create backup
cp composer.json composer.json.backup

echo "Creating updated composer.json with actual versions..."

# Extract locked versions and create new composer.json
php << 'EOF'
<?php
$lock = json_decode(file_get_contents('composer.lock'), true);
$composer = json_decode(file_get_contents('composer.json'), true);

// Get installed versions from lock file
$installed = [];
foreach ($lock['packages'] ?? [] as $package) {
    $installed[$package['name']] = $package['version'];
}
foreach ($lock['packages-dev'] ?? [] as $package) {
    $installed[$package['name']] = $package['version'];
}

// Update require section
if (isset($composer['require'])) {
    foreach ($composer['require'] as $name => $constraint) {
        if ($constraint === '*' && isset($installed[$name])) {
            $version = ltrim($installed[$name], 'v');
            // Convert to major version caret constraint
            if (preg_match('/^(\d+)\./', $version, $matches)) {
                $major = $matches[1];
                $composer['require'][$name] = "^{$major}.0";
            } else {
                $composer['require'][$name] = $version;
            }
        }
    }
}

// Update require-dev section
if (isset($composer['require-dev'])) {
    foreach ($composer['require-dev'] as $name => $constraint) {
        if ($constraint === '*' && isset($installed[$name])) {
            $version = ltrim($installed[$name], 'v');
            // Convert to major version caret constraint
            if (preg_match('/^(\d+)\./', $version, $matches)) {
                $major = $matches[1];
                $composer['require-dev'][$name] = "^{$major}.0";
            } else {
                $composer['require-dev'][$name] = $version;
            }
        }
    }
}

file_put_contents('composer.json', json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
echo "composer.json updated successfully!\n";
?>
EOF
