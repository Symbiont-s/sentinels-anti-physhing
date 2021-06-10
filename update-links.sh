#!/bin/bash
# -*- ENCODING: UTF-8 -*-
wget -O /var/www/html/reader/phishing-urls.tar.gz https://raw.githubusercontent.com/mitchellkrogza/Phishing.Database/master/ALL-phishing-domains.tar.gz

tar -xf /var/www/html/reader/phishing-urls.tar.gz -C /var/www/html/reader/
exit