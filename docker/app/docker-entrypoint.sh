#!/bin/sh

bin/console doctrine:migrations:migrate --no-interaction && echo "DB migration success"
bin/console cache:clear && echo "Cache clearing success"
bin/console doctrine:fixtures:load --no-interaction && echo "Fixtures load success"

php-fpm

exec docker-entrypoint "$@"