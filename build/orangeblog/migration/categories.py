import sys
import os

sys.path.append('/Users/taylan/Development/orangeblog/trunk/build/orangeblog')

os.environ['DJANGO_SETTINGS_MODULE'] = 'settings'

from entries.models import Category
import MySQLdb

conn = MySQLdb.connect(host='localhost', user='root', passwd='La3164Fi', db='orangeblog_legacy')
cursor = conn.cursor(MySQLdb.cursors.DictCursor)

cursor.execute("SELECT * FROM ob_kategori")
categories = cursor.fetchall()

for cat in categories:
    Category(name=cat["isim"]).save()

cursor.close()
conn.close()