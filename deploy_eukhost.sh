#!/bin/bash

FILE="$(date '+%Y-%m-%d')serverupload_eukhost.tar"

DIR="src"

tar cf "$FILE" src

tar --append --file="$FILE"  "public"
tar --append --file="$FILE"  "config"
tar --append --file="$FILE"  "templates"


gzip "$FILE"
