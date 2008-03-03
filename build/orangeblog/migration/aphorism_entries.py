import sys
import os
import MySQLdb

pathname = os.path.dirname(sys.argv[0])
sys.path.append(os.path.normpath(os.path.join(os.path.abspath(pathname), '../../')))
sys.path.append(os.path.normpath(os.path.join(os.path.abspath(pathname), '../')))
os.environ['DJANGO_SETTINGS_MODULE'] = 'orangeblog.settings'

from django.contrib.auth.models import User

from aphorisms.models import Aphorism


conn = MySQLdb.connect(host='localhost', user='root', passwd='La3164Fi', db='orangeblog_legacy')
conn.set_character_set('utf8')

cursor = conn.cursor(MySQLdb.cursors.DictCursor)
cursor.execute("SELECT * FROM ob_vecize")
aphorisms = cursor.fetchall()

u = User.objects.get(pk=1)

count = 0

for aphorism in aphorisms:
    a = Aphorism(user=u, content=unicode(aphorism["vecize"], "utf-8"), person=unicode(aphorism["kisi"], "utf-8").lstrip('" ').rstrip('" '))
    a.save()
    
    count += 1

print "%s / %s" % (count, len(aphorisms))

cursor.close()
conn.close()