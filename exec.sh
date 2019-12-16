#!/bin/bash

base_path=$(pwd)/
container_name='laravel-app'
container_path='/var/www/html/'

if [[ -a $1 ]]; then
    src=${1#$base_path}
    # echo copy $src "/var/www/html/$src"
    docker cp $src "$container_name:$container_path$src"
fi
