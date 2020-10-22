#!/bin/bash


find ./* -type f -mtime 0 ! -path './composer*' ! -path '*/var/*' ! -path './translations/*' ! -path './developer*' ! -path '*/x*' ! -path "*.tar.gz" ! -path "*.yaml" ! -path "*.tar" ! -path "*.kate*"  ! -path "*/newimages*" ! -path "*.sh" ! -path "*/config*.*" ! -path "./vendor/*"  -print0 | tar -czvf $(date '+%Y-%m-%d')serverupload_eukhost_recent.tar.gz --null -T -

