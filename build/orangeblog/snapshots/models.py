from django.db import models
from django.conf import settings
from django.contrib.auth.models import User
from django.utils.translation import ugettext_lazy as _

from entries.models import Post


class Snapshot(models.Model):
    """ A snapshot image that links to a post """
    
    post = models.ForeignKey(Post, raw_id_admin=True, verbose_name=_("Post"))
    user = models.ForeignKey(User, verbose_name=_("Author"))
    image = models.ImageField(_("Snapshot"), upload_to="files/snapshots")
    post_date = models.DateTimeField(_("Post Date"), blank=True, auto_now_add=True, editable=False)
    
    def get_snapshot_url(self):
        return urljoin(settings.MEDIA_URL, os.path.join('dynamic', 'snapshots', ('%s.jpg' % self.id)))
    
    class Admin:
        list_display = ('image', 'post', 'user', 'post_date', )
    
    class Meta:
        verbose_name = _("Snapshot")
        verbose_name_plural = _("Snapshots")
    
    def __unicode__(self):
        return "Snapshot: %s" % self.image
