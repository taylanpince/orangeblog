from django.conf.urls.defaults import *


urlpatterns = patterns('entries.views',
    url(r'^(?P<slug>[-\w]+)/sil/$', 'post_delete', name="post_delete"),
    url(r'^(?P<slug>[-\w]+)/degistir/$', 'post_change', name="post_change"),
    url(r'^(?P<slug>[-\w]+)/$', 'post_detail', name="post_detail"),
    url(r'^$', 'home', name="post_landing"),
)