from django.conf.urls.defaults import *


urlpatterns = patterns('',
    url(r'^(?P<slug>[-\w]+)/$', 'entries.views.post_detail', name="post_detail"),
    url(r'^$', 'entries.views.home'),
)