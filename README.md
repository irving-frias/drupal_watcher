# Drupal Watcher

## Description

This script monitors specified directories for file changes and clears the Drupal cache using Drush whenever a file is modified.

## Installation

1. Move the `drupal_watcher` script to `/usr/local/bin` to make it accessible system-wide:
    ```bash
    mv drupal_watcher /usr/local/bin/drupal_watcher
    ```

2. Navigate to your Drupal project directory:
    ```bash
    cd /Users/user/Sites/drupal-project
    ```

3. Run the script:
    ```bash
    drupal_watcher
    ```

## Requirements

- macOS
- Homebrew

## Usage

The script automatically monitors the following directories for file changes:

- `docroot/modules/custom`
- `docroot/themes/custom`

It requires Drush to be installed in the `vendor/bin` directory.

## Customization

You can customize the directories to watch and the actions to perform by editing the script variables directly in the `drupal_watcher` file.

## Notes

- Ensure that the script file (`drupal_watcher`) has executable permissions set before moving it to `/usr/local/bin`:
  ```bash
  chmod +x drupal_watcher
