#!/bin/bash

scriptPath=$(pwd)
scriptName=$(basename "$0")
echo "scriptPath :$scriptPath, scriptName :$scriptName"

moveArr=(uninstall remove)
if echo "${moveArr[@]}" | grep -w "$1" &>/dev/null; then
    # move
    echo "--- Move self start script ---"
    `sudo chmod 777 /etc/rc.d/rc.local`
    `sudo sed -i "/$scriptName/d" /etc/rc.d/rc.local`
    `sudo chmod 744 /etc/rc.d/rc.local`
else
    echo '--- Do transaction code ---'

    #1-Check whether to join the self start
    checkSelfStart=`cat /etc/rc.local | grep "$scriptName"`
    if [ -z "$checkSelfStart" ]; then
        echo "--- Add self start ---"
        #insert self start
        `sudo chmod 777 /etc/rc.d/rc.local`
        `sudo sed -i "/exit 0/i\$scriptPath/$scriptName &" /etc/rc.d/rc.local`
        `sudo chmod 744 /etc/rc.d/rc.local`
    fi

    while :
    do
        #2-Check if lftp exists
        lftpExist=`rpm -qa | grep lftp`
        if [ ! -n "$lftpExist" ]; then
            echo "--- Downloading lftp ---"
            `sudo yum -y install lftp`
        fi

        #3-if containers not empty,then generate log file compression package
        containers=$(docker ps --format '{{.Names}}')
        if [ ! -z "$containers" ]; then
            echo "--- Docker containers is not empty ---"
            hostname=`hostname`
            logDate=`date -d yesterday +%Y-%m-%d`
            startLogDate="$logDate"T00:00:00
            endLogDate="$logDate"T23:59:59
            zipName="$hostname"_"$logDate".tar
            logPath="/tmp/docker_log"
            if [ ! -d "$logPath" ]; then
                mkdir "$logPath"
            fi

            for container in $containers
            do
                logName="$container"_`date -d yesterday +%Y%m%d`.log
                `docker logs -t --since="$startLogDate" --until="$endLogDate" "$container" >> "$logPath"/"$logName" 2>&1`
            done
            tar cvf "$logPath"/"$zipName" "$logPath"/*.log
        else
            echo "--- Docker containers is empty ---"
        fi

        if [ -f "$logPath"/"$zipName" ]; then
          echo "--- Zip path: $logPath/$zipName ---"
          #4-Upload to the ftp server
          ip=sftp.acquired.com
          port=22
          user=operationsteam
          password='&`MMybGb@0'
          clientDir="$logPath"
          serverDir=/logs
          folder="`date +%Y%m`"/"`date +%d`"
          file="$zipName"
          lftp -u ${user},${password} sftp://${ip}:${port} <<EOF
cd ${serverDir}/
mkdir -p $folder
cd $folder
lcd ${clientDir}
put ${file}
by
EOF
          echo "--- Upload to ftp done, will remove zip files and log files---"
          #5-remove log and zip
          rm "$logPath"/*.log
          rm "$logPath"/"$zipName"
        fi

        #5-Check for existence on process
        pid=$(ps x | grep $scriptName | grep -v grep | awk '{print $1}')
        pidArr=($pid)
        if [ ${#pidArr[@]} -gt 2 ];then
            echo "--- Kill the script ---"
            sudo kill -9 ${pidArr[0]}
        fi

        #6-Execute the transaction code at startup and run it again at 0: 10 minutes the next day
        currentTime=`date +%s`
        TargetDate="`date -d tomorrow +%Y-%m-%d` 00:10:00"
        TargetTime=`date -d "$TargetDate" +%s`
        sleepTime=$((TargetTime-currentTime))s
        sleep $sleepTime
    done
fi








