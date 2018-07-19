# find_categories
# Matthew Farias

import json
import sys

found = {}

# Loads already existing categories from text file
def loadCats(catFile):
	for line in catFile:
		found[line.split(',')[0]]=True

# Checks to see if the JSON value entered has a new type of category
def findCats(val, out):
	catArr=val['categories']
	if len(catArr)==0:
		return 0
	cat = catArr[0]['shortName']
	if cat not in found:
		found[cat] = True
		out.write(cat+'\n')
		return 1

# Takes in data file as an argument from commandline and will add new categories if thet exists
def main(dataFile):
	fd = open(dataFile, 'r',encoding="latin-1")
	line = fd.read()
	fd.close()
	fd=open('categories','r')
	loadCats(fd)
	fd.close()
	cats = open("categories",'a')
	arr = json.loads(line)
	count=0
	for i in arr:
		count+=(findCats(i, cats))
	cats.close()
	print('Added', count, 'new categories')

if __name__ == "__main__":
    main(sys.argv[1])