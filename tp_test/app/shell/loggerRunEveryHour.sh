#!/bin/bash

executionTime=`date +%H`
echo $executionTime
#Run the transaction code at 0
if [ $executionTime -eq 00 ]; then
  #1-Check if lftp exists
  lftpExist=`rpm -qa | grep lftp`
  if [ ! -n "$lftpExist" ]; then
    `sudo yum -y install lftp`
  fi

  #2-generate log file compression package
  hostname=`hostname`
  logDate=`date -d yesterday +%Y-%m-%d`
  startLogDate="$logDate"T00:00:00
  endLogDate="$logDate"T23:59:59
  zipName="$hostname"_"$logDate".zip
  logPath="/tmp/docker_log"
  if [ ! -d "$logPath" ]; then
      mkdir "$logPath"
  fi

  containers=$(docker ps --format '{{.Names}}')
  for container in $containers
  do
    logName="$container"_`date -d yesterday +%Y%m%d`.log
    `docker logs -t --since="$startLogDate" --until="$endLogDate" "$container" >> "$logPath"/"$logName" 2>&1`
  done
  zip "$logPath"/"$zipName" "$logPath"/*.log
  rm "$logPath"/*.log

  #3-Upload to the server
  ip=sftp.acquired.com
  port=22
  user=operationsteam
  password='&`MMybGb@0'
  clientDir="$logPath"
  serverDir=/logs
  folder="`date -d yesterday +%Y%m`"
  file="$zipName"

  #4-upload logs to ftp
  lftp -u ${user},${password} sftp://${ip}:${port} <<EOF
cd ${serverDir}/
mkdir -p $folder
cd $folder
lcd ${clientDir}
put ${file}
by
EOF

  rm "$logPath"/"$zipName"
fi

#5-Kill the old process before starting the new process
process=$(basename "$0")
pid=$(ps x | grep $process | grep -v grep | awk '{print $1}')
pidArr=($pid)
if [ ${#pidArr[@]} -gt 2 ];then
  sudo kill -9 ${pidArr[0]}
fi

#Run every hour
sleep 1h
sh logger.sh



