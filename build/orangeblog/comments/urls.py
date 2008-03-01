from django.conf.urls.defaults import *


urlpatterns = patterns('comments.views',
    url(r'^sil/(?P<id>[0-9]+)/$', 'comment_delete', name="comment_delete"),
    url(r'^ekle/(?P<slug>[-\w]+)/$', 'comment_submit', name="comment_submit"),
    url(r'^degistir/(?P<id>[0-9]+)/$', 'comment_change', name="comment_change"),
)