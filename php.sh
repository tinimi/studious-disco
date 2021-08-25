#!/usr/bin/bash
docker run --rm --volume $PWD:/app -w /app --user $(id -u):$(id -g) my_app "$@"