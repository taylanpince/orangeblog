from django.conf.urls.defaults import *


urlpatterns = patterns('',
    url(r'^admin/', include('django.contrib.admin.urls')),
    url(r'^entriler/', include('entries.post_urls')),
    url(r'^kategoriler/', include('entries.category_urls')),
    url(r'^yorumlar/', include('comments.urls')),
    url(r'^yazarlar/', include('profiles.urls')),
    url(r'^giris/$', 'profiles.views.user_login', name="user_login"),
    url(r'^cikis/$', 'profiles.views.user_logout', name="user_logout"),
    url(r'^entri-getir/$', 'entries.views.get_or_submit', name="get_or_submit"),
    url(r'^yeni-entri/(?P<slug>[-\w]+)/$', 'entries.views.post_submit', name="post_submit"),
    url(r'^sayfa-(?P<page>[0-9]+)/$', 'entries.views.home', name="home_paginated"),
    url(r'^$', 'entries.views.home', name="home"),
)
