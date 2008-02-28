from django.conf.urls.defaults import *


urlpatterns = patterns('',
    url(r'^ekle/(?P<slug>[-\w]+)/$', 'comments.views.comment_submit', name="comment_submit"),
)