#!/bin/bash
cd ..
EMAIL="$(grep 'email = *' .git/config | sed 's/email = //1')"
NAME="$(grep 'name = *' .git/config | sed 's/name = //1')"
git config user.email "${EMAIL:1}"
git config user.name "${NAME:1}"
if [ $# -eq 0 ]; then
	echo "No arguments supplied"
else
	if [ "$1" == "pull" ]; then
		git pull
		echo "converting"
		find ./webroot/ ./src/ -type f -exec sed -i 's/WQIS\//wqis\//g' {} \; #convert all instances of WQIS/ to wqis/ for Seth's weird-ass machine (only file paths, don't care about any other uses)
	elif [ "$1" == "push" ]; then
		echo "converting"
		find ./webroot/ ./src/ -type f -exec sed -i 's/wqis\//WQIS\//g' {} \; #convert back for the rest of us
		git add *
		git add -u
		git add -A
		if [ $# -eq 1 ]; then
			echo "committing with no message"
			git commit
		else
			echo "commit with message $2"
			git commit -m "$2"
		fi
		git push
	else
		echo "invalid arguments $@"
	fi
fi