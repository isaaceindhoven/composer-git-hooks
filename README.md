# ISAAC Composer Git Hooks
Set up the `.git/hooks` folder to run scripts found in the `bin/git-hooks/{hookName}.d` folders of the project.

How it works:
1. Include the package as a dev dependency
2. On composer install/update, all git hooks (.git/hooks/{pre-commit,post-commit,...}) will be symlinked to this module's `scripts/chain-hook`.
3. The `chain-hook` script will run the scripts found in the project's `bin/git-hooks/{hookName}.d` folder.

## Prerequisites
- A composer-managed project

## Install
Add the ISAAC repository to composer.

```bash
composer config repositories.isaac composer https://composer-packages.hq.isaac.nl/repository
```

Add the package as a dev dependency.

```bash
composer require --dev isaac/composer-git-hooks
```

### Running the script manually

This package is a composer plugin and will install the githooks automatically on `composer install` and `composer update`. Should you need it however, you can run the installer manually by adding it as a composer script and executing it.

Add the following to `composer.json`:
```json
"scripts": {
    ...
    "install-git-hooks": "ISAAC\\ComposerGitHooks\\ComposerPlugin::process"
    ...
},
```

Run it:
```shell script
composer run-script install-git-hooks
```

## Usage
Add project specific git-hooks to `bin/git-hooks/{hookName}.d`. For example:
- `bin/git-hooks/pre-commit.d/phpstan`
- `bin/git-hooks/pre-commit.d/phpcs`

All scripts (for in this case `pre-commit`) should give a 0 exit code for the whole hook to succeed.

See this module's `standard-hooks` directory for example hooks. When using these scripts as-is it is recommended to symlink them. Run this from the project root:
```shell script
ln -s ../../../vendor/isaac/composer-git-hooks/standard-hooks/pre-commit.d/phpcs bin/git-hooks/pre-commit.d/phpcs
```

## Contribute
Create a merge request.
This package makes use of the `composer` plugin interface. See the [composer documentation](https://getcomposer.org/doc/articles/plugins.md).
