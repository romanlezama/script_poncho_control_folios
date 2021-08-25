# -*- coding: utf-8 -*-
import pyexcel, re
# Leer el documento de excel
def read_excel_sheets(sheet_name=None, file_url=None, all_sheets=False):
    book_dict = pyexcel.get_book_dict(file_name=file_url)
    if all_sheets:
        dict_all_sheets = {}
        for name_sheet in book_dict:
            records = book_dict[name_sheet]
            header = records.pop(0)
            try:
                header = [str(col).lower().replace(u'\xa0',u' ').strip().replace(' ', '_') for col in header]
            except UnicodeEncodeError:
                header = [col.lower().replace(u'\xa0',u' ').strip().replace(' ', '_') for col in header]
            dict_all_sheets[ name_sheet.lower().replace(' ', '_') ] = {
                'header': header,
                'records': records
            }
        return dict_all_sheets
    if book_dict.has_key(sheet_name):
        records = book_dict[sheet_name]
        header = records.pop(0)
        try:
            header = [str(col).lower().replace(u'\xa0',u' ').strip().replace(' ', '_') for col in header]
        except UnicodeEncodeError:
            header = [col.lower().replace(u'\xa0',u' ').strip().replace(' ', '_') for col in header]
        return header, records

def get_month_number( m ):
    dict_months = {
        "ene": "01",
        "feb": "02",
        "marz": "03",
        "abr": "04",
        "may": "05",
        "jun": "06",
        "jul": "07",
        "ago": "08",
        "sep": "09",
        "oct": "10",
        "nov": "11",
        "dic": "12"
    }
    return dict_months.get( m, '0' )

def get_full_date( text_with_date ):
    text_fecha = text_with_date.split(')')[1]
    text_fecha = text_fecha.lower()

    print 'text_fecha=',text_fecha
    
    day = re.search(r"[0-9]{1,2}", text_fecha, re.IGNORECASE).group()
    int_day = int(day)
    day = "0"+str(int_day) if int_day < 10 else day
    print 'day=',day
    
    month = re.search(r"ene|feb|marz|abr|may|jun|jul|ago|sep|oct|nov|dic", text_fecha, re.IGNORECASE).group()
    print 'month=',month
    n_month = get_month_number(month)
    
    year = re.search(r"202[0-9]", text_fecha, re.IGNORECASE).group()
    print 'year=',year
    full_date = year + '-' + n_month + '-' + day
    print 'full_date=',full_date
    return full_date

"""
# Procesa la pagina de Diesel para obtener los totales de las ventas y la fecha de la venta
"""
def process_diesel_sheet( content_sheet ):
    # Obtengo el texto donde está la fecha de corte
    print '====== Buscando la fecha ======'
    text_with_date = ''
    for r in content_sheet:
        for c in r:
            if re.match(r"(TEOTI|teoti|Teoti).*(\s+)?\((\s+)?5787(\s+)?\)", str(c)):
                text_with_date = c
                break
        if text_with_date:
            break
    print 'text_with_date=',text_with_date
    
    # Proceso el texto encontrado para formatear la fecha
    if text_with_date:
        full_date = get_full_date( text_with_date )

    # Recorro de nuevo la información de la hoja para buscar los totales
    print '====== Buscando los totales ======'
    pos_row = None
    pos_col = None
    for cont_r, r in enumerate(content_sheet):
        for cont_c, c in enumerate(r):
            if str(c).lower().strip() == "r1":
                pos_row = cont_r
                pos_col = cont_c
                break
        if pos_row and pos_col:
            break
    print 'pos_row=',pos_row
    print 'pos_col=',pos_col
    dict_totales = {}
    for r in content_sheet[pos_row:]:
        tipo_bomba = r[ pos_col ]
        if not tipo_bomba:
            break
        dict_totales.update({
            tipo_bomba: round(r[ pos_col + 1 ], 2)
        })
    print 'dict_totales=',dict_totales

"""
# Procesa la pagina de Notas para obtener las compras de los Clientes
"""
def get_client_name( full_name ):
    listEval = ['vales', 'notas', 'vale', 'nota']
    for strEval in listEval:
        lName = full_name.split( strEval )
        if len(lName) > 1:
            return lName[1].strip()
    return ''

def process_notas_sheet( content_sheet ):
    print '====== Buscando las ventas a los clientes ======'
    dict_ventas_clientes = {}
    for r in content_sheet:
        for cont_c, c in enumerate(r):
            try:
                if re.match(r"^[0-9]{1,3}\s+?(vale|nota)", c.lower().strip()):
                    monto_vendido = 0
                    for cc in r[ cont_c + 1: ]:
                        try:
                            monto_vendido = round( float( cc ), 2 )
                            break
                        except:
                            continue
                    nameClient = get_client_name( c.lower() )
                    dict_ventas_clientes.update({
                        nameClient: monto_vendido
                    })
            except:
                continue
    print 'dict_ventas_clientes=',dict_ventas_clientes

if __name__ == "__main__":
    
    # Dirección donde está guardado el Excel
    name_file = 'CUENTA DEL DIA MIERCOLES 18  DE AGOSTO DEL  2021'
    file_url = '/home/roman/script_poncho/excel_files/{}.xlsx'.format(name_file)
    
    # Obtengo un diccionario con las páginas y el contenido de cada una
    dict_all_sheets_excel = read_excel_sheets(file_url=file_url, all_sheets=True)
    process_diesel_sheet( dict_all_sheets_excel.get('diesel', {}).get('records', []) )
    process_notas_sheet( dict_all_sheets_excel.get('notas', {}).get('records', []) )
    #for r in records:
