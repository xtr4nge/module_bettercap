#!/bin/bash

apt-get install build-essential ruby-dev libpcap-dev

#git clone https://github.com/evilsocket/bettercap
git clone https://github.com/xtr4nge/bettercap
cd bettercap
gem build bettercap.gemspec
gem install bettercap*.gem

echo "..DONE.."
exit
