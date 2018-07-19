#restyle_json
#Matthew Farias

import json

table = {}
keys=['name','address','postalCode','lat','lng','is_Accom','is_Arts','is_Asso','is_Edu','is_Finan',
	'is_Healthcare','is_Parks','is_Public','is_Resturant','is_Shop','is_Sports','is_Trans']


educationWords=['school','university','college']

def addTable(line):
	seperate = line.split(',')
	table[seperate[0]]=seperate[1:]

def clean(vals):
	name = vals.pop('name')
	location=vals.pop('location')
	cats = vals.pop('categories')

	vals.clear()

	vals['name']=name
	try:
		vals['address']=location['address']
		vals['postalCode']=location['postalCode']
	except:
		vals['address']=None
		vals['postalCode']=None
	

	vals['lat']=location['lat']
	vals['lng']=location['lng']
	vals['is_Accom'] = vals['is_Arts'] = vals['is_Asso'] = vals['is_Edu'] = vals['is_Finan'] = vals['is_Healthcare'] = False
	vals['is_Parks'] = vals['is_Public'] = vals['is_Resturant'] = vals['is_Shop'] = vals['is_Sports'] = vals['is_Trans'] = False

	for i in educationWords:
		if i in name.lower():
			vals['is_Edu'] = True

	if len(cats)>0:
		shortName=cats[0]['shortName']
		for i in table[shortName]:
			if i[-1]=='\n':
				i=i[:-1]
			if i not in keys:
				print(i)
			vals[i] = True

def main():
	fd = open("data_backup.json",'r',encoding="latin-1")
	lines=fd.read()
	fd.close()

	catFile=open('categories','r')
	for line in catFile:
		addTable(line)
	catFile.close()

	data = json.loads(lines)
	for i in data:
		clean(i)
	saveFile=open("cleanData.json",'w')
	json.dump(data,saveFile)
	saveFile.close()

if __name__ == "__main__":
    main()