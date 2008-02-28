from django.db import models
from django.db.models import permalink
from django.contrib.auth.models import User
from django.template.defaultfilters import slugify
from django.utils.translation import ugettext_lazy as _

from profiles.managers import ProfileManager


class UserProfile(models.Model):
    """ Holds extra profile information for users """
    
    user = models.ForeignKey(User, unique=True, verbose_name=_("User"))
    nickname = models.CharField(_("Nickname"), max_length=40, unique=True)
    slug = models.SlugField(blank=True, editable=False)
    birth_date = models.DateField(blank=True, null=True)
    
    admin_objects = models.Manager()
    objects = ProfileManager()
    
    @permalink
    def get_absolute_url(self):
        return ('user_info', (), {'slug': self.slug})
    
    def save(self):
        self.slug = slugify(self.nickname)
        super(UserProfile, self).save()
    
    class Admin:
        list_display = ('user', 'nickname', )
        search_fields = ('nickname', )
    
    class Meta:
        verbose_name = _("User Profile")
        verbose_name_plural = _("User Profiles")
    
    def __unicode__(self):
        return "UserProfile: %s" % self.user
