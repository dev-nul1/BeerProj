__author__ = "Philip Scheid (hello@philipscheid.com)"

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
import threading
import time
import csv
import StringIO
import gviz_api

from datetime import datetime
# load non standard packages, exit when they are not installed
try:
	import serial
except ImportError:
	print "BrewPi requires PySerial to run, please install it with 'sudo apt-get install python-serial"
	sys.exit(1)
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
#import brewSocket
#import profileTemps
COMPORT = "COM3"
BAUDRATE = 9600
#ser = serial.Serial("COM3", 9600)

def scriptPath():
	"""
	Return the path of BrewPiUtil.py. __file__ only works in modules, not in the main script.
	That is why this function is needed.
	"""
	return os.path.dirname(__file__)

description = {	"Time": ("datetime","Time"),
		"BeerTemp": 	("number",	"Beer temperature")}
dataTable = gviz_api.DataTable(description)

currentBeerName = "Test"
day = time.strftime("%Y-%m-%d")
lastDay = day
# define a JSON file to store the data table
jsonFileName = currentBeerName
# DYNAMIC jsonFileName= currentBeerName + '/' + currentBeerName + '-' + day
#if a file for today already existed, add suffix
if os.path.isfile('data/' + jsonFileName + '.json'):
	i=1
	while (os.path.isfile('data/' + jsonFileName + '-' + str(i) + '.json')):
		i=i+1
	jsonFileName = jsonFileName + '-' + str(i)
localJsonFileName = 'data/' + jsonFileName + '.json'
 
# Define a location on the webserver to copy the file to after it is written
wwwJsonFileName = 'Z:/python/data/' + jsonFileName + '.json'
 
# Define a CSV file to store the data as CSV (might be useful one day)
# dynamic csvFileName = 'data/' + currentBeerName + '/' + currentBeerName + '.csv'
csvFileName = 'data/' + currentBeerName + '.csv'

def monitor():

	ser = serial.Serial("COM3", 9600)

	while(1): #read all lines on serial interface
		line = ser.readline()
		if(line): #line available?
			#process line
			if line.count(";")==1:
				#valid data received
				lineAsFile = StringIO.StringIO(line) #open line as a file to use it with csv.reader
				reader = csv.reader(lineAsFile, delimiter=';',quoting=csv.QUOTE_NONNUMERIC)
				for	row	in reader: #Relace empty annotations with None
					"""if(row[2]==''):
						row[2]=None
					if(row[5]==''):
						row[5]=None"""
					#append new row to data table, print it to stdout and write complete datatable to json file
					newRow= [{'Time': datetime.today(),'BeerTemp': row[0]}]
					print newRow
					dataTable.AppendData(newRow)
					jsonfile = open(localJsonFileName,'w')
					jsonfile.write(unicode(dataTable.ToJSon(columns_order=["Time", "BeerTemp"])))
					jsonfile.close()
					shutil.copyfile(localJsonFileName,wwwJsonFileName)
					#copy to www dir. Do not write directly to www dir to prevent blocking www file.
					#write csv file too
					csvFile = open(csvFileName,"a")
					lineToWrite = time.strftime("%b %d %Y %H:%M:%S;" ) + line
					csvFile.write(lineToWrite)
					csvFile.close()
				prevDataTime = time.time() #store time of last new data for interval check
			else:
				print >> sys.stderr, "Error: Received	invalid	line: " + line
		elif((time.time() - prevDataTime) >= serialRequestInterval): #if no new data has been received for serialRequestInteval seconds, request it
			ser.write("r")		#	request	new	data from	arduino
			time.sleep(1)		#   give the arduino time to respond
			continue
		elif(time.time() - prevDataTime > serialRequestInterval+2*serialCheckInterval):
			#something is wrong: arduino is not responding to data requests
			print >> sys.stderr, "Error: Arduino is not responding to new data requests"
		else:
			break
	"""while (1):
		line = ser.readline()
		if (line != ""):
			#print line[:-1]         # strip \n
			fields = line[:-1].split('; ');

			ID = fields[0]
			#TIME = int(fields[1])
			# print fields
			print "device ID: ", ID
			# write to file
			text_file = open("Pdata.csv", "w")
			#line = str(CT) + "\n"
			text_file.write(line)
			text_file.close()

		# do some other things here"""

	print "Stop Monitoring"



monitor()

"""while True:
	print ser.readline()
time.sleep(1) """