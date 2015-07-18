#!/bin/bash

# Forcely destroys the current Vagrant instance.

VAGRANT="/usr/bin/env vagrant"

$VAGRANT destroy --force
