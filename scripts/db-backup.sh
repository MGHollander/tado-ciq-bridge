#!/usr/bin/env bash
source "$(dirname "$0")/common.sh"

# Stop on any error.
set -e

function usage() {
    log_warning "Usage:"
    log_text "  $(basename "$0") <backup-dir> [--] "
    log_text ""
    log_warning "Arguments:"
    log_text "\033[32m  backup-dir      \033[0m  Directory to save the database backup"
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

BACKUP_DIR=$1

if [ -z "$BACKUP_DIR" ]; then
    log_error "You have to define a backup directory."
    usage
    exit 1;
fi

log "\033[1m--------- Start database backup ---------"

log "Go to the project root"
cd "$(cd -P -- "$(dirname -- "$0")" && pwd -P)/.." || exit 1

DB_HOST=$(awk -F "=" '/DB_HOST/ {print $2}' .env)
DB_PORT=$(awk -F "=" '/DB_PORT/ {print $2}' .env)
DB_DATABASE=$(awk -F "=" '/DB_DATABASE/ {print $2}' .env)
DB_USERNAME=$(awk -F "=" '/DB_USERNAME/ {print $2}' .env)
DB_PASSWORD=$(awk -F "=" '/DB_PASSWORD/ {print $2}' .env)

log "Create database backup directory"
mkdir -p "$BACKUP_DIR"

log "Create database backup"
DB_BACKUP_LOCATION="$BACKUP_DIR/$DB_DATABASE-$(date +'%F-%H%M%S').sql.gz"
mysqldump -h "$DB_HOST" -u "$DB_USERNAME" -P "$DB_PORT" -p"$DB_PASSWORD" "$DB_DATABASE" | gzip -c > "$DB_BACKUP_LOCATION" || exit 1

log_text "Database backup available at $DB_BACKUP_LOCATION"

log "\033[1m========= End of database backup ========="
