#!/bin/bash

# Usage: ./runme.sh [host:port]
# Default: 127.0.0.1:8000
ADDR=${1:-127.0.0.1:8000}

/opt/homebrew/opt/php@8.2/bin/php -S "$ADDR" router.php
