from django.conf.urls.defaults import *
from django.views.generic.simple import direct_to_template

from comments.feeds import RssLatestComments, RssCommentsByPost
from entries.feeds import RssLatestPosts, RssLatestPostsByCategory


rss_feeds = {
    "son-entriler": RssLatestPosts,
    "son-yorumlar": RssLatestComments,
    "kategoriler": RssLatestPostsByCategory,
    "entriler": RssCommentsByPost,
}

urlpatterns = patterns('',
    url(r'^admin/', include('django.contrib.admin.urls')),
    url(r'^entriler/', include('entries.post_urls')),
    url(r'^kategoriler/', include('entries.category_urls')),
    url(r'^yorumlar/', include('comments.urls')),
    url(r'^yazarlar/', include('profiles.urls')),
    url(r'^unutkan/$', 'profiles.views.user_password_reset', name="user_password_reset"),
    url(r'^unutkan/tamam/$', direct_to_template, {'template': 'profiles/user_password_reset_done.html'}, name="user_password_reset_done"),
    url(r'^giris/$', 'profiles.views.user_login', name="user_login"),
    url(r'^cikis/$', 'profiles.views.user_logout', name="user_logout"),
    url(r'^ayarlar/$', 'profiles.views.user_update', name="user_update"),
    url(r'^ayarlar/sifre/$', 'profiles.views.user_password_update', name="user_password_update"),
    url(r'^getur/(?P<slug>[-\w]+)/$', 'entries.views.lookup_post', name="lookup_post"),
    url(r'^entri-getir/$', 'entries.views.get_or_submit', name="get_or_submit"),
    url(r'^yeni-entri/(?P<slug>[-\w]+)/$', 'entries.views.post_submit', name="post_submit"),
    url(r'^$', 'entries.views.home', name="home"),
)

urlpatterns += patterns('django.contrib.syndication.views',
    url(r'^rss/(?P<url>.*)/$', 'feed', {'feed_dict': rss_feeds}, name="rss_feeds"),
)