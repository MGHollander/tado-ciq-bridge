#!/usr/bin/env bash
source "$(dirname "$0")/common.sh"

# Stop on any error.
set -e

SCRIPT_NAME=`basename $0`

function usage() {
    log_warning "Usage:"
    log_text "  ${SCRIPT_NAME} <env> [options] [--] "
    log_text ""
    log_warning "Arguments:"
    log_text "\033[32m  env              \033[0m  Environment, for example @acc or @prod"
    log_text ""
    log_warning "Options:"
    log_text "\033[32m  -h, --help       \033[0m  Display this help message"
}

# https://medium.com/@Drew_Stokes/bash-argument-parsing-54f3b81a6a8f
PARAMS=""
while (( "$#" )); do
    case "$1" in
        -h|--help)
            usage
            exit 0
            ;;
        --) # end argument parsing
            shift
            break
            ;;
        -*|--*=) # unsupported flags
            log_error "Error: Unsupported flag $1" >&2
            echo ""
            usage
            exit 1
            ;;
        *) # preserve positional arguments
            PARAMS="$PARAMS $1"
            shift
            ;;
    esac
done
# set positional arguments in their proper place
eval set -- "$PARAMS"

log "\033[1m--------- Performing build ---------"

log "Go to the projects root directory"
cd "$(cd -P -- "$(dirname -- "$0")" && pwd -P)/.." || exit 1;

log "Check if we were given a valid environment argument"
ENV="$1"
[ "${ENV}" == "@none" ] && exit 0;
if [ "${ENV}" == "default" ] || [ "${ENV}" == "@self" ] || [ "${ENV}" == "" ]; then
  ENV="@local";
fi

log_text "The current environment is \033[1m${ENV}"

if ! hash composer; then
    log_error "Composer is not installed. Please install Composer before you continue." && exit 1
fi

log "Install composer packages"
if [ "${ENV}" == "@prod" ]; then
    composer install --optimize-autoloader --prefer-dist --no-dev --no-interaction --no-scripts --no-progress --no-suggest  || exit 1
else
    composer install --no-interaction  --no-progress --no-suggest || exit 1
fi

if ! hash npm; then
    log_error "NPM is not installed. Please install NPM before you continue." && exit 1
fi

log "Install NPM packages"
npm install || exit 1

log "Run NPM scripts"
if [ "${ENV}" == "@prod" ] || [ "${ENV}" == "@acc" ]; then
    npm run prod || exit 1
else
    npm run dev || exit 1
fi

log "\033[1m========= End of build ========="
