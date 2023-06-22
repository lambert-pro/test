#!/bin/bash
#脚本上传文件到ftp

ip=sftp.acquired.com
port=22
user=operationsteam
password='&`MMybGb@0'
clientDir=/home/qlambert
serverDir=/logs
folder="`date +%Y%m`"/"`date +%d`"
file=test.sh
lftp -u ${user},${password} sftp://${ip}:${port} <<EOF
cd ${serverDir}/
mkdir -p $folder
cd $folder
lcd ${clientDir}
put ${file}
by
EOF