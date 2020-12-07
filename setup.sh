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
mkdir -p application/smarty/compiled application/smarty/cache application/smarty/configs

# Change permissions for smarty folders
chown -Rf $user:$group application/smarty/compiled application/smarty/cache application/smarty/configs
chmod -Rf 775 application/smarty/compiled application/smarty/cache application/smarty/configs

#----------------------------------------------------------------------------------------------
# Part 2: Log files creation and permission assignation
#----------------------------------------------------------------------------------------------
echo '' > public/Logger.txt
echo '' > public/JOB_Log.txt
echo '' > public/SQL_Log.txt

chown -Rf $user:$group public/Logger.txt public/JOB_Log.txt public/SQL_Log.txt
chmod -Rf 775 public/Logger.txt public/JOB_Log.txt public/SQL_Log.txt
