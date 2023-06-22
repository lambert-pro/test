#!/bin/bash
scriptPath=$(pwd)
scriptName=$(basename "$0")
echo "scriptPath :$scriptPath, scriptName :$scriptName"

moveArr=(uninstall remove)
if echo "${moveArr[@]}" | grep -w "$1" &>/dev/null; then
    # move
    echo "--- Found text '$scriptName' ---"
    `sudo chmod 777 /etc/rc.d/rc.local`
    `sudo sed -i "/$scriptName/d" /etc/rc.d/rc.local`
    `sudo chmod 744 /etc/rc.d/rc.local`
else
    # transaction code
    echo '--- Do transaction code ---'

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
      echo "testtesttest---"
      sleep 10s
    done
fi
