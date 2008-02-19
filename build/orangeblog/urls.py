from django.conf.urls.defaults import *

urlpatterns = patterns('',
    url(r'^admin/', include('django.contrib.admin.urls')),
    url(r'^entriler/', include('entries.post_urls')),
    url(r'^kategoriler/', include('entries.category_urls')),
    url(r'^yazarlar/', include('profiles.urls')),
    url(r'^sayfa-(?P<page>[0-9]+)/$', 'entries.views.home', name="home_paginated"),
    url(r'^$', 'entries.views.home', name="home"),
)
