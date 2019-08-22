#!/bin/sh

host=$1
pwds=$2
db=$3
keys=$4

redis-cli -h ${host} -p 6379 <<END
auth ${pwds}
select ${db}
del ${keys}
exit
END
exit 0
