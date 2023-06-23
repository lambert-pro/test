#!/bin/bash
#脚本判断lftp是否存在，不存在就下载

lftpExist=`rpm -qa | grep lftp`

if [ ! -n "$lftpExist" ]; then
  `sudo yum -y install lftp`
fi


executionTime=`date +%H`
#Run the transaction code at 0
if [ $executionTime -eq 06 ]; then
  echo 2123
fi