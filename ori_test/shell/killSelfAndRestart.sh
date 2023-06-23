#!/bin/bash

process=test.sh
pid=$(ps x | grep $process | grep -v grep | awk '{print $1}')
pidArr=($pid)
echo ${pidArr[@]}

if [ ${#pidArr[@]} -gt 2 ];then
  echo $BASHPID
  sudo kill -9 ${pidArr[0]}
fi

sleep 5
sh killSelfAndRestart.sh