


# load build_utils.sh script
if [ -e build_utils.sh ]; then
	source ./build_utils.sh
elif [ -e /usr/local/bin/pxn/build_utils.sh ]; then
	source /usr/local/bin/pxn/build_utils.sh
else
	wget https://raw.githubusercontent.com/PoiXson/shellscripts/master/pxn/build_utils.sh \
		|| exit 1
	source ./build_utils.sh
fi



# replace version in pom files
sedVersion "${PWD}/pom.xml"



# build
mvn clean install
buildresult=$?



# return the pom files
mvresult=0
restorePom "${PWD}/pom.xml" || mvresult=1



# results
if [ $buildresult != 0 ]; then
	echo "Build has failed!"
	exit 1
fi
if [ $mvresult != 0 ]; then
	echo "Failed to return an original pom.xml file!"
	exit 1
fi



cp -fv "${PWD}/target/psmCore-"*.zip "${PWD}"

newline
ls -lh "${PWD}/psmCore-"*.zip
newline

