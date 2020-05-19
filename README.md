# ISAAC Composer Git Hooks
Set up the Git Repository to use the 

## Prerequisites
- A composer-managed project

## Install
Add the repository to composer.

```bash
composer config repositories.isaac/composer-git-hooks vcs git@gitlab.isaac.local:php-module/isaac-composer-git-hooks.git
```

Add the package as a dev dependency.

```bash
composer require --dev "isaac/composer-git-hooks:^1.0"
```

## Usage

## Contribute
Create a merge request.
This package makes use of the `composer` plugin interface. See the [composer documentation](https://getcomposer.org/doc/articles/plugins.md).
