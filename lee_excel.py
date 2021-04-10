# coding: utf-8
import pyexcel
# Leer el documento de excel
def read_file(file_url):
	#book_dict = pyexcel.get_book_dict(url=file_url)
	sheet = pyexcel.get_sheet(file_name = file_url)
	all_records = sheet.array
	header = all_records.pop(0)
	header = [col.lower().replace(u'\xa0',u' ').strip().replace(' ', '_') for col in header]
	records = []
	return header, all_records


if __name__ == "__main__":
	file_url = '/home/roman/script_poncho/OZZY.xlsx'
	print( read_file(file_url) )