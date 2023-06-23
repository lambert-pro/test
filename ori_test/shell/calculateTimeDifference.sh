#!/bin/bash
while :
do
  echo '执行业务代码'

  currentTime=`date +%s`
  TargetDate="`date -d tomorrow +%Y-%m-%d` 00:10:00"
  TargetTime=`date -d "$TargetDate" +%s`
  sleepTime=$((TargetTime-currentTime))s
  sleep $sleepTime
done

