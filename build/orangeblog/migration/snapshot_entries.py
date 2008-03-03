import sys
import os
import MySQLdb
from PIL import Image
from time import gmtime, localtime
from datetime import datetime, date

pathname = os.path.dirname(sys.argv[0])
sys.path.append(os.path.normpath(os.path.join(os.path.abspath(pathname), '../../')))
sys.path.append(os.path.normpath(os.path.join(os.path.abspath(pathname), '../')))
os.environ['DJANGO_SETTINGS_MODULE'] = 'orangeblog.settings'

from django.contrib.auth.models import User
from django.template.defaultfilters import slugify

from entries.models import Post
from snapshots.models import Snapshot


conn = MySQLdb.connect(host='localhost', user='root', passwd='La3164Fi', db='orangeblog_legacy')
cursor = conn.cursor(MySQLdb.cursors.DictCursor)

cursor.execute("SELECT z.id, z.resim, z.tarih, b.baslik FROM ob_zirtapoz as z, ob_blog as b WHERE z.blogid = b.id")
snapshots = cursor.fetchall()

u = User.objects.get(pk=1)

LEGACY_PATH = "/Users/taylan/Development/orangeblog/trunk/build/legacy/zirtapoz"
NEW_PATH = "/Users/taylan/Development/orangeblog/trunk/build/orangeblog/media/files/snapshots"

for snapshot in snapshots:
    pd = gmtime(snapshot["tarih"])
    
    try:
        p = Post.objects.get(slug=slugify(snapshot["baslik"]))
        
        img = Image.open(os.path.join(LEGACY_PATH, snapshot["resim"]))
        img.save(os.path.join(NEW_PATH, snapshot["resim"]))
        img_path = os.path.join("files", "snapshots", snapshot["resim"])
        
        s = Snapshot(post=p, user=u, image=img_path, post_date=datetime(pd[0], pd[1], pd[2], pd[3], pd[4], pd[5]))
        s.save()
        
        print s
    except:
        print "ERROR IMPORTING: %s" % snapshot["id"]

cursor.close()
conn.close()