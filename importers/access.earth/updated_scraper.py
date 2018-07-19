# updated_json_scraper
# Matthew Farias

import urllib
import requests
import time
import io
import sys

found={}
bigList=[]

# Access.Earth uses a specific link with a center long/lat and two corner long lats to make a rectangle and stores a json
# of all buildings found within that rectangle
# Unfortunately there are limits as to how many buildings can be stored in one link so this program will check the location of every municipality
# and gather the data of all buildings around them while avoiding duplicates
def scrapeLocation(city):
	city = city.split()
	if len(city[-1])==1:
		centerLat = float(city[-3])
		centerLong = float(city[-2])
	else:
		centerLat = float(city[-2])
		centerLong = float(city[-1])	 
	long1 = centerLong - 0.00547608948
	long2 = centerLong + 0.00547608948
	lat1 = centerLat - 0.0026599506
	lat2 = centerLat + 0.0026599506

	url="https://access.earth/php/factual_data.php?lat="+str(centerLat)+"&lng="+str(centerLong)+"&bounds="+str(long1)+","+str(lat1)+","+str(long2)+","+str(lat2)+"&q=e&user=1"
	data=requests.get(url)
	if len(data.text) < 10:
		return ""
	checkDup(data.json())

# Checks to see if any duplicates have been found
def checkDup(arr):
	out=[]
	for i in arr:
		if i['id'] not in found:
			bigList.append(i)
			found[i['id']]=True

def main(arg1,arg2):	
	fd=open(arg1, 'r')
	saveData=io.open(arg2, 'a', encoding="utf-8")
	for line in fd:
		scrapeLocation(line)
	json.dump(bigList,saveData)
	saveData.close()
	fd.close()

if __name__ == "__main__":
    main(sys.argv[1],sys.argv[2])
