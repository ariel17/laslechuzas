#!/bin/bash

###############################################################################
# update_test_data.sh
# Author: Ariel Gerardo Ríos (ariel.rios@movile.com)
# Date: 16/06/2015
# Purpose: Dumps the content from the test MySQL server to be tracked.
###############################################################################


TMP=".tmp.sql"
DUMP="provision/mysql/development-data.sql"
USER="root"
PASSWORD="myroot"
PORT="33306"
HOST="127.0.0.1"
DATABASE="laslechuzas"
MYSQLDUMP="/usr/bin/env mysqldump -u $USER -p$PASSWORD --port=$PORT --host=$HOST"

echo ">> Collecting test data from database $DATABASE";

$MYSQLDUMP -e --compact $DATABASE > $TMP
status=$?

if [ $status -eq 0 ]; 
then
    cp $TMP $DUMP
    echo "... Done :)"
fi

rm $TMP
exit $status;
