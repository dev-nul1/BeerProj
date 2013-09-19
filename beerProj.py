import sys
# Check needed software dependencies to nudge users to fix their setup
if sys.version_info < (2, 7):
	print "Sorry, requires Python 2.7."
	sys.exit(1)

# standard libraries
import time
import socket
import os
import urllib
import getopt
from pprint import pprint
import shutil

# load non standard packages, exit when they are not installed
try:
	import simplejson as json
except ImportError:
	print "BrewPi requires simplejson to run, please install it with 'sudo apt-get install python-simplejson"
	sys.exit(1)
try:
	from configobj import ConfigObj
except ImportError:
	print "BrewPi requires ConfigObj to run, please install it with 'sudo apt-get install python-configobj"
	sys.exit(1)

#import readCSV
import brewSocket
