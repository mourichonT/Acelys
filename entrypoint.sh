#!/bin/bash
php-fpm8 -F &
php bin/console messenger:consume async