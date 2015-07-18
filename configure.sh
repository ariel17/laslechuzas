#!/bin/bash -x

CWD=`pwd`
SITE="$CWD/site"
CONFIG_DIR="$SITE/wp-config"
CONFIG="$SITE/wp-config.php"

case "$1" in
    vagrant)
        config="$CONFIG_DIR/$1.php"
        ;;
    *)
        echo "Usage: $0 {vagrant}" >&2
        exit 1
        ;;
esac

if [ ! -d "$CONFIG" ];
then
    rm $CONFIG
fi

ln -s $config $CONFIG

exit $?
