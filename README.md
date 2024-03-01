# Drupal Watcher

## Description

The Drupal Watcher script is a handy tool that monitors specified directories for file changes within a Drupal project. It automatically clears the Drupal cache using Drush whenever a file is modified, ensuring your Drupal site remains up-to-date and responsive to changes during development.

## Installation

1. **Move the Script**: Place the `drupal_watcher` script in `/usr/local/bin` to make it accessible system-wide:
    ```bash
    mv drupal_watcher /usr/local/bin/drupal_watcher
    ```

2. **Navigate to Your Drupal Project Directory**:
    ```bash
    cd /Users/user/Sites/drupal-project
    ```

3. **Run the Script**:
    ```bash
    drupal_watcher
    ```

## Requirements

- **Operating System**: macOS
- **Package Manager**: Homebrew

## Usage

The script automatically monitors the following directories for file changes:

- `docroot/modules/custom`
- `docroot/themes/custom`

It requires Drush to be installed in the `vendor/bin` directory.

## Customization

You have the flexibility to customize the directories to watch and the actions to perform by directly editing the script variables within the `drupal_watcher` file. This customization allows you to tailor the script according to your Drupal project's specific requirements and directory structure.

## Notes

- **Executable Permissions**: Before moving the script file (`drupal_watcher`) to `/usr/local/bin`, ensure it has executable permissions set:
  ```bash
  chmod +x drupal_watcher
