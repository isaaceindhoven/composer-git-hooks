#!/bin/bash
set -euo pipefail

# Look for a bin/git-hooks/{gitHook}.d directory and execute all hooks found in it.
# Arguments:
#   $1 The git hook name, e.g. pre-commit or post-commit.
# Returns:
#   0 If no custom hook dir is found or all scripts in it exited successfully.
#   1 If one of the scripts in the directory returned an error code.
function git_hooks::chain() {
    local gitHook=$1
    local customHookDir="bin/git-hooks/${gitHook}.d"

    if [ ! -d $customHookDir ]; then
        git_hooks::info "No custom hook dir $customHookDir"
        return 0
    fi

    local returnCode=0

    for customHookPath in ${customHookDir}/*; do
      git_hooks::info "Running custom git hook: ${customHookPath}"

      local hookExitCode=0
      "${customHookPath}" "$@" || hookExitCode=$?

      if [ $hookExitCode -ne 0 ]; then
        git_hooks::warning "Script ${customHookPath} returned exit code $hookExitCode"
        returnCode=1
      fi
    done

    return $returnCode
}

function git_hooks::info() {
    echo -e "\033[0;32m$1\033[0m"
}

function git_hooks::warning() {
    echo -e "\033[0;31m$1\033[0m" >&2
}
