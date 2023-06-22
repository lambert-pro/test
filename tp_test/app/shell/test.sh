#!/bin/bash
hostname=`hostname`
logDate=`date -d yesterday +%Y-%m-%d`
zipName="$hostname"_"$logDate".tar
logPath="/tmp/docker_log"

tar cvf "$logPath"/"$zipName" -C "$logPath"/ .
