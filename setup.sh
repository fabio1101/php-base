#!/bin/bash

#----------------------------------------------------------------------------------------------
# Shell script to setup the project folders/permissions
#----------------------------------------------------------------------------------------------

# Default users to change ownership
user="$(whoami)"
group="$(groups)"

#
while getopts u:g: o; do
  case $o in
    (u) user=$OPTARG;;
    (g) group=$OPTARG;;
  esac
done
shift $(($OPTIND - 1))

#----------------------------------------------------------------------------------------------
# Part 1: Smarty folders creation and permission assignation
#----------------------------------------------------------------------------------------------

# Create smarty directories if they doesnt exists
mkdir -p application/smarty_compiled

# Change permissions for smarty folders
chown -Rf $user:$group application/smarty_compiled
chmod -f 775 application/smarty_compiled