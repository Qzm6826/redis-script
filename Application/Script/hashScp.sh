#!/bin/sh

host=$1
pwds=$2
db=$3
key=$4
hKeys=$5

redis-cli -h ${host} -p 6379 <<END
auth ${pwds}
select ${db}
hdel ${key} ${hKeys}
exit
END
exit 0
