#!/bin/bash

set -euo pipefail

function docksal::custom_command_exists() {
  local commandName=$1
  [ -f "${PWD}/.docksal/commands/${commandName}" ]
}