#!/bin/bash

FILE="$(date '+%Y-%m-%d')serverupload_config.tar"

DIR="src"

tar cf "$FILE" config




gzip "$FILE"
