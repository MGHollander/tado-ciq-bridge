#!/usr/bin/env bash

# Print a text with the date and time prepended in blue.
#
# Available colors:
#
# default       \e[39m  Use this to return to normal.
# red           \e[31m
# green         \e[32m
# yellow        \e[33m
# blue          \e[34m
# magenta       \e[35m
# cyan          \e[36m
# light_gray    \e[37m
# dark_gray     \e[90m
# light_red     \e[91m
# light_green   \e[92m
# light_yellow  \e[93m
# light_blue    \e[94m
# light_magenta \e[95m
# light_cyan    \e[96m
# white         \e[97m

function log() {
  echo -e "\033[34m[$(date +'%F %T')]\033[0m $1\033[0m"
}

function log_error() {
    echo -e "\033[31m$1\033[0m"
}

function log_success() {
    echo -e "\033[32m$1\033[0m"
}

function log_text() {
    echo -e "$1\033[0m"
}

function log_warning() {
    echo -e "\033[33m$1\033[0m"
}
