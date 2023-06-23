#!/bin/bash
#脚本检查docker容器

containers=$(docker ps --format '{{.Names}}')
if [ ! -z "$containers" ]; then
  echo "--- Docker containers is not empty ---"
else
  echo "--- Docker containers is empty ---"
fi
