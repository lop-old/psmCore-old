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



%install
echo
echo "Install.."
# delete existing rpm's
%{__rm} -fv "%{_rpmdir}/%{name}"*.noarch.rpm
# create directories
%{__install} -d -m 0755 \
	"${RPM_BUILD_ROOT}%{prefix}" \
		|| exit 1
# copy .php files
for phpfile in \
	index.php \
; do
	%{__install} -m 0755 \
		"%{sourceroot}/www/psmcore/${phpfile}" \
		"${RPM_BUILD_ROOT}%{prefix}/${phpfile}" \
			|| exit 1
done



%clean
if [ ! -z "%{_topdir}" ]; then
	%{__rm} -rf --preserve-root "%{_topdir}" \
		|| echo "Failed to delete build root (probably fine..)"
fi



### Files ###
%files
%defattr(555,root,root,755)
%{prefix}/index.php

