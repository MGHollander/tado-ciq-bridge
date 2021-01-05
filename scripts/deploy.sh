#!/usr/bin/env bash
source "$(dirname "$0")/common.sh"

# Stop on any error.
set -e

BACKUP_PATH="/home/marucn1q/backups/tado-ciq-bridge/$(date +'%Y%m%d%H%M%S')"
DEPLOY_PATH="/home/marucn1q/subdomains/tado-ciq-bridge"

function usage() {
    log_warning "Usage:"
    log_text "  $(basename "$0") <env> <server> [options] [--] "
    log_text ""
    log_warning "Arguments:"
    log_text "\033[32m  env              \033[0m  Environment, for example @acc or @prod"
    log_text "\033[32m  server           \033[0m  Server to deploy to, for example user@server.tld"
    log_text ""
    log_warning "Options:"
    log_text "\033[32m  -h, --help       \033[0m  Display this help message"
    log_text "\033[32m  -f, --force      \033[0m  Force the deployment no matter the environment"
    log_text "\033[32m  -p, --port       \033[0m  Port to use during deployment"
}

# https://medium.com/@Drew_Stokes/bash-argument-parsing-54f3b81a6a8f
PARAMS=""
while (( "$#" )); do
    case "$1" in
        -h|--help)
            usage
            exit 0
            ;;
        -f|--force)
            FORCE_DEPLOY=1
            shift 1
            ;;
        -p|--port)
            if [ -n "$2" ] && [ "${2:0:1}" != "-" ]; then
                PORT=$2
                shift 2
            else
                echo "Error: Argument for $1 is missing" >&2
                exit 1
            fi
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

ENV="$1"
SERVER="$2"

if [ -z "$PORT" ]; then
  PORT=22
fi

log "\033[1m--------- Start deployment ---------"

log "Go to the project root"
cd "$(cd -P -- "$(dirname -- "$0")" && pwd -P)/.." || exit 1;

log "Check if we were given a valid environment argument"
[ "${ENV}" == "@none" ] && exit 0;
if [ "${ENV}" == "default" ] || [ "${ENV}" == "@self" ] || [ "${ENV}" == "" ]; then
  ENV="@local";
fi

log_text "The current environment is \033[1m${ENV}"

# @TODO make a reuasable statement for this check
if [ "${ENV}" == "@acc" ] || [ "${ENV}" == "@prod" ]; then
  if [ -z $FORCE_DEPLOY ]; then
    read -p "Are you sure that you want to continue this deployment to production? [y/n] " -n 1 -r
    echo # move to a new line
    if [[ ! $REPLY =~ ^[Yy]$ ]]; then
      log_error "Deployment aborted"
      exit 2
    else
      log_success "Continue deployment"
    fi
  else
   log_warning "Force deployment"
  fi
else
  echo "This environment is not recognized. Continue..."
fi

if [ "${ENV}" == "@acc" ] || [ "${ENV}" == "@prod" ]; then
  if [ -z "$SERVER" ]; then
    log_error "You have to define a server for a deployment on this environment."
    usage
    exit 1;
  fi

  log "Execute rsync"
  rsync -e "ssh -p ${PORT}" . "${SERVER}:${DEPLOY_PATH}" -ahlvz --stats --delete-after --exclude-from='.rsync-exclude' --backup-dir="${BACKUP_PATH}" || exit 1

  log_success "Successfully finished rsync"
else
  log_warning "Skip rsync"
fi

if [ "${ENV}" == "@acc" ] || [ "${ENV}" == "@prod" ]; then
    log "Run deployment commands on ${ENV}"

    ssh "${SERVER}" -p "${PORT}" /bin/bash << EOF
        cd $DEPLOY_PATH

        bash scripts/db-backup.sh $BACKUP_PATH

        php artisan down --render="errors::maintenance"
        php artisan config:cache
        php artisan route:cache
        php artisan view:cache
        php artisan migrate --force
        php artisan up
EOF
elif [ "${ENV}" == "@local" ]; then
    log "Run migrations"
    php artisan migrate --force
fi

log "\033[1m========= End of deployment ========="
