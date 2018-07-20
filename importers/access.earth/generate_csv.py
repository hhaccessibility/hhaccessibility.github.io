# json_to_csv_converter
# Matthew Farias

import json
import sys
import csv

def main():
	getVals = open("cleanData.json", 'r',encoding="latin-1")
	values = getVals.read()
	getVals.close()

	dataJson = json.loads(values)
	keys=['name','address','postalCode','lat','lng','is_Accom','is_Arts','is_Asso','is_Edu','is_Finan',
	'is_Healthcare','is_Parks','is_Public','is_Resturant','is_Shop','is_Sports','is_Trans']

	csvFile = open("canada_buildings.csv", 'w',encoding='utf-8')

	csvWriter = csv.DictWriter(csvFile,fieldnames=keys)
	csvWriter.writeheader()
	for val in dataJson:
		csvWriter.writerow(val)
	csvFile.close()

if __name__ == "__main__":
    main()