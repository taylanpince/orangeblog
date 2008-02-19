import sys
import os
import MySQLdb
from time import gmtime
from datetime import datetime

sys.path.append('/Users/taylan/Development/orangeblog/trunk/build')
sys.path.append('/Users/taylan/Development/orangeblog/trunk/build/orangeblog')

os.environ['DJANGO_SETTINGS_MODULE'] = 'settings'

from django.template.defaultfilters import slugify
from django.contrib.auth.models import User

from entries.models import Post, PostRating, Category


conn = MySQLdb.connect(host='localhost', user='root', passwd='La3164Fi', db='orangeblog_legacy')
conn.set_character_set('utf8')

cursor = conn.cursor(MySQLdb.cursors.DictCursor)
cursor.execute("SELECT b.id, b.tarih, b.baslik, b.icerik, b.daha, b.oy, u.kulisim as yazar, c.isim as kategori FROM ob_blog as b, ob_uyeler as u, ob_kategori as c WHERE b.yazarid = u.id AND b.kategori = c.id")
posts = cursor.fetchall()

for post in posts:
    user = User.objects.get(username=post["yazar"])
    cat = Category.objects.get(slug=slugify(post["kategori"]))
    da = gmtime(post["tarih"])
    
    p = Post(user=user, title=unicode(post["baslik"], "utf-8"), category=cat, content_md=unicode("%s %s" % (post["icerik"], post["daha"]), "utf-8"), post_date=datetime(da[0], da[1], da[2], da[3], da[4], da[5]), save_date=datetime(da[0], da[1], da[2], da[3], da[4], da[5]))
    p.save()
    
    #print "Title: %s" % post["baslik"]
    #print "Date: %s" % datetime(da[0], da[1], da[2], da[3], da[4], da[5])
    #print "User: %s" % user
    #print "Category: %s" % cat
    #print "Content: %s %s" % (post["icerik"], post["daha"])
    
    if post["oy"] > 0:
        subcursor = conn.cursor(MySQLdb.cursors.DictCursor)
        subcursor.execute("SELECT u.kulisim as yazar FROM ob_blogoy as r, ob_uyeler as u WHERE r.blogid = %s AND r.yazarid = u.id LIMIT %s" % (post["id"], post["oy"]))
        ratings = subcursor.fetchall()
    
        for rating in ratings:
            ruser = User.objects.get(username=rating["yazar"])
        
            PostRating(user=ruser, post=p, rating=True).save()
        
            #print "Rating for: %s" % ruser
    
    #print ""

cursor.close()
conn.close()