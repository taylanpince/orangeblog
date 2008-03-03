from django.db import models
from django.contrib.auth.models import User
from django.utils.translation import ugettext_lazy as _


class Aphorism(models.Model):
    """ An aphorism with a person """
    
    user = models.ForeignKey(User, verbose_name=_("Author"))
    content = models.TextField(_("Content"), max_length=400)
    person = models.CharField(_("Person"), max_length=50)
    post_date = models.DateTimeField(_("Post Date"), blank=True, auto_now_add=True, editable=False)
    
    class Admin:
        list_display = ('user', 'content', 'person', 'post_date', )
        search_fields = ('content', 'person', )
    
    class Meta:
        verbose_name = _("Aphorism")
        verbose_name_plural = _("Aphorisms")
    
    def __unicode__(self):
        return "Aphorism: %s" % self.id
