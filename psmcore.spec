Name            : psmCore
Summary         : Content management framework
Version         : 3.4.%{BUILD_NUMBER}
Release         : 1
BuildArch       : noarch
Requires        : php >= 5.4.0
Requires        : gd
Provides        : php-psm
Prefix          : /usr/local/lib/php/psmcore
%define  _rpmfilename  %%{NAME}-%%{VERSION}-%%{RELEASE}.noarch.rpm

License         : [[TBA]]
Group           : Development/PHP
Packager        : PoiXson <support@poixson.com>
URL             : http://psm.poixson.com/

%description
Content management framework for php websites. This package allows the framework to be shared between websites.



%build
# build apache include file
%{__cat} <<EOF >psmcore.conf

<Directory "/usr/local/lib/php/psmcore">
	Options -Indexes
	AllowOverride None

	# Apache 2.4
	<IfModule mod_authz_core.c>
		Require all granted
	</IfModule>
	# Apache 2.2
	<IfModule !mod_authz_core.c>
		Order Deny,Allow
		Allow from all
	</IfModule>

</Directory>
Alias /psmcore /usr/local/lib/php/psmcore

EOF
# build php include file
%{__cat} <<EOF >psmcore.ini

include_path = ".:/usr/local/lib/php"

EOF



%install
echo
echo "Install.."
# delete existing rpm's
%{__rm} -fv "%{_rpmdir}/%{name}"*.noarch.rpm
# create directories
%{__install} -d -m 0755 \
	"${RPM_BUILD_ROOT}%{prefix}/db"       \
	"${RPM_BUILD_ROOT}%{prefix}/engine"   \
	"${RPM_BUILD_ROOT}%{prefix}/portal"   \
	"${RPM_BUILD_ROOT}%{prefix}/utils"    \
	"${RPM_BUILD_ROOT}%{prefix}/website"  \
	"${RPM_BUILD_ROOT}/etc/httpd/conf.d/" \
	"${RPM_BUILD_ROOT}/etc/php.d/"        \
		|| exit 1
# copy .php files
for phpfile in \
	'core.php'                          \
	'inc.php'                           \
	'ClassLoader.php'                   \
	'config.php.original'               \
	'engine/engine.class.php'           \
	'engine/render.class.php'           \
	'engine/engine_interface.class.php' \
	'engine/engine_block.class.php'     \
	'engine/engine_head.class.php'      \
	'engine/phpclss.class.php'          \
	'engine/tpl.class.php'              \
	'portal/portal.class.php'           \
	'portal/module.class.php'           \
	'utils/utils.class.php'             \
	'utils/csrf.class.php'              \
	'utils/numbers.class.php'           \
	'utils/PassCrypt.class.php'         \
	'utils/san.class.php'               \
	'utils/vars.class.php'              \
	'website/website.class.php'         \
; do
	%{__install} -m 0555 \
		"%{sourceroot}/www/psmcore/${phpfile}" \
		"${RPM_BUILD_ROOT}%{prefix}/${phpfile}" \
			|| exit 1
done
# copy psmcore.conf apache config
%{__install} -m 644 \
	"psmcore.conf" \
	"${RPM_BUILD_ROOT}/etc/httpd/conf.d/psmcore.conf" \
		|| exit 1
# copy psmcore.ini php config
%{__install} -m 644 \
	"psmcore.ini" \
	"${RPM_BUILD_ROOT}/etc/php.d/psmcore.ini" \
		|| exit 1



%clean
if [ ! -z "%{_topdir}" ]; then
	%{__rm} -rf --preserve-root "%{_topdir}" \
		|| echo "Failed to delete build root (probably fine..)"
fi



### Files ###
%files
%defattr(-,root,root,-)
%{prefix}/core.php
%{prefix}/inc.php
%{prefix}/ClassLoader.php
%{prefix}/config.php.original
%{prefix}/engine/engine.class.php
%{prefix}/engine/render.class.php
%{prefix}/engine/engine_interface.class.php
%{prefix}/engine/engine_block.class.php
%{prefix}/engine/engine_head.class.php
%{prefix}/engine/phpclss.class.php
%{prefix}/engine/tpl.class.php
%{prefix}/portal/portal.class.php
%{prefix}/portal/module.class.php
%{prefix}/utils/utils.class.php
%{prefix}/utils/csrf.class.php
%{prefix}/utils/numbers.class.php
%{prefix}/utils/PassCrypt.class.php
%{prefix}/utils/san.class.php
%{prefix}/utils/vars.class.php
%{prefix}/website/website.class.php
/etc/httpd/conf.d/psmcore.conf
/etc/php.d/psmcore.ini

