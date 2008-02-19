import sys
import os
import MySQLdb
from time import gmtime, localtime
from datetime import datetime, date

pathname = os.path.dirname(sys.argv[0])
sys.path.append(os.path.normpath(os.path.join(os.path.abspath(pathname), '../../')))
sys.path.append(os.path.normpath(os.path.join(os.path.abspath(pathname), '../')))
os.environ['DJANGO_SETTINGS_MODULE'] = 'orangeblog.settings'

from django.contrib.auth.models import User

from profiles.models import UserProfile

conn = MySQLdb.connect(host='localhost', user='root', passwd='La3164Fi', db='orangeblog_legacy')
cursor = conn.cursor(MySQLdb.cursors.DictCursor)

cursor.execute("SELECT * FROM ob_uyeler")
users = cursor.fetchall()

for user in users:
    if user["songiris"] > 0:
        ll = gmtime(user["songiris"])
    else:
        ll = localtime()
    
    dj = gmtime(user["katilimtarih"])
    
    u = User(username=user["kulisim"], password=User.objects.make_random_password(), email=user["email"], last_login=datetime(ll[0], ll[1], ll[2], ll[3], ll[4], ll[5]), date_joined=datetime(dj[0], dj[1], dj[2], dj[3], dj[4], dj[5]))
    u.save()
    
    p = u.get_profile()
    p.nickname = user["isim"]
    
    if user["dogumtarih"] is not None:
        bd = gmtime(user["dogumtarih"])
        p.birth_date = date(bd[0], bd[1], bd[2])
    
    p.save()

cursor.close()
conn.close()