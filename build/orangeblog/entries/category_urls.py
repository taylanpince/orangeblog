from django.conf.urls.defaults import *


urlpatterns = patterns('',
    url(r'^(?P<slug>[-\w]+)/sayfa-(?P<page>[0-9]+)/$', 'entries.views.category_detail', name="category_detail_paginated"),
    url(r'^(?P<slug>[-\w]+)/$', 'entries.views.category_detail', name="category_detail"),
    url(r'^$', 'entries.views.category_list', name="category_list"),
)