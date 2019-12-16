#!/bin/bash


if ! [[ $(ps -a -o command="" | grep -v grep | grep "fswatch -x -l 1 app") ]]; then
    fswatch -x -l 1 app | xargs -n1 ./exec.sh &
    echo "**** start watching ****"
fi

