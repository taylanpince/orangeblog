from django.conf.urls.defaults import *


urlpatterns = patterns('',
    url(r'^(?P<slug>[-\w]+)/$', 'profiles.views.user_info', name="user_info"),
)