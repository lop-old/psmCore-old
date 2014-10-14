# ==================================================
# psmCore - Workspace setup script
#
# This script downloads and prepares the psmCore
# source code. The project uses multiple repositories
# with symlinks between them. Once this script has
# completed, the project will be ready to deploy.
# The script can be used safely on an existing
# workspace without damage, but will rather update
# the existing files. This is useful for recompiling.
#
# http://psm.poixson.com
# https://github.com/PoiXson/psmCore
# ==================================================
# setup_workspace.sh




clear



if [[ `pwd` == */psmCore.git ]]; then
	echo
	echo "Cannot run this script within the repo!"
	echo "Move up to the parent directory and run from there."
	echo
	exit 1
fi



# load workspace_utils.sh script
if [ -e workspace_utils.sh ]; then
	source ./workspace_utils.sh
elif [ -e /usr/local/bin/pxn/workspace_utils.sh ]; then
	source /usr/local/bin/pxn/workspace_utils.sh
else
	wget https://raw.githubusercontent.com/PoiXson/shellscripts/master/pxn/workspace_utils.sh \
		|| exit 1
	source ./workspace_utils.sh
fi



# CHECKOUT
title "Cloning Repos"
CheckoutRepo  psmCore.git  "${REPO_PREFIX}/psmCore.git"  || exit 1
newline
newline



# SYMLINKS
# title "Creating Symbolic Links"



newline
echo "Finished workspace setup!"
newline
newline
ls -lh
newline
newline

