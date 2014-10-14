


SOURCE_ROOT=`pwd`
SPEC_FILE="psmcore.spec"



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



# build rpm
rpmbuild -bb \
	--define="_topdir ${BUILD_ROOT}" \
	--define="_tmppath ${BUILD_ROOT}/tmp" \
	--define="sourceroot ${SOURCE_ROOT}" \
	--define="_rpmdir ${OUTPUT_DIR}" \
	--define="BUILD_NUMBER ${BUILD_NUMBER}" \
	"${BUILD_ROOT}/SPECS/${SPEC_FILE}" \
		|| exit 1

