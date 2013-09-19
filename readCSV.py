import csv

out=open("data/data2.csv", "rb")
data=csv.reader(out)
data=[row for row in data]
#data=[[row[0],eval(row[1])] for row in data]
out.close
print data


