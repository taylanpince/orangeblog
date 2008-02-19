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

from entries.models import Post
from comments.models import Comment, CommentRating


conn = MySQLdb.connect(host='localhost', user='root', passwd='La3164Fi', db='orangeblog_legacy')
conn.set_character_set('utf8')

cursor = conn.cursor(MySQLdb.cursors.DictCursor)
cursor.execute("SELECT c.id, c.tarih, c.yorum, c.oy, u.kulisim as yazar, b.baslik as baslik FROM ob_blog as b, ob_uyeler as u, ob_yorumlar as c WHERE c.yazarid = u.id AND c.blogid = b.id")
comments = cursor.fetchall()

for comment in comments:
    user = User.objects.get(username=comment["yazar"])
    date = gmtime(comment["tarih"])
    
    try:
        post = Post.objects.get(slug=slugify(comment["baslik"]))
    except:
        pass
    else:
        c = Comment(user=user, post=post, content_md=unicode(comment["yorum"], "utf-8"), post_date=datetime(date[0], date[1], date[2], date[3], date[4], date[5]), save_date=datetime(date[0], date[1], date[2], date[3], date[4], date[5]))
        c.save()
        
        #print "Date: %s" % datetime(date[0], date[1], date[2], date[3], date[4], date[5])
        #print "User: %s" % user
        #print "Post: %s" % post
        #print "Rating: %s" % comment["oy"]
        
        if comment["oy"] > 0:
            subcursor = conn.cursor(MySQLdb.cursors.DictCursor)
            subcursor.execute("SELECT u.kulisim as yazar FROM ob_yorumoy as r, ob_uyeler as u WHERE r.yorumid = %s AND r.yazarid = u.id LIMIT %s" % (comment["id"], comment["oy"]))
            ratings = subcursor.fetchall()
        
            for rating in ratings:
                ruser = User.objects.get(username=rating["yazar"])
            
                CommentRating(user=ruser, comment=c, rating=True).save()
            
                #print "Rating for: %s" % ruser
        
        #print ""

cursor.close()
conn.close()