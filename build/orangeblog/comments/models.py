from django.db import models
from django.db.models import permalink
from django.contrib.auth.models import User
from django.utils.translation import ugettext_lazy as _

from entries.models import Post
from tools.utils import mark_lookups
from comments.managers import CommentManager

from markdown import markdown


class Comment(models.Model):
    """ A comment for a blog post """
    
    user = models.ForeignKey(User, verbose_name=_("Author"))
    post = models.ForeignKey(Post, verbose_name=_("Post"))
    content = models.TextField(_("HTML Content"), blank=True, editable=False)
    content_md = models.TextField(_("Content"))
    public = models.BooleanField(_("Public"), default=True)
    post_date = models.DateTimeField(_("Post Date"), blank=True, auto_now_add=True, editable=False)
    save_date = models.DateTimeField(_("Last Update"), blank=True, auto_now=True, editable=False)
    
    admin_objects = models.Manager()
    objects = CommentManager()
    
    @permalink
    def get_absolute_url(self):
        return ('post_detail', (), {'slug': self.post.slug})
    
    def save(self):
        self.content = markdown(mark_lookups(self.content_md))
        
        super(Comment, self).save()
    
    def delete(self):
        self.commentrating_set.all().delete()
        
        super(Comment, self).delete()
    
    class Admin:
        list_display = ('post', 'user', 'post_date', 'save_date', 'public', )
        list_filter = ('public', )
        search_fields = ('content', )
        date_hierarchy = 'post_date'
    
    class Meta:
        verbose_name = _("Comment")
        verbose_name_plural = _("Comments")
        permissions = (("can_post_comments", "Can post comments"), )
    
    def __unicode__(self):
        return "Comment: %s" % self.post


class CommentRating(models.Model):
    """ A rating for a comment """
    
    comment = models.ForeignKey(Comment, verbose_name=_("Comment"))
    user = models.ForeignKey(User, verbose_name=_("User"))
    rating = models.BooleanField(_("Rating"))

    class Admin:
        list_display = ('comment', 'user', 'rating', )
        list_filter = ('rating', )

    class Meta:
        verbose_name = _("Comment Rating")
        verbose_name_plural = _("Comment Ratings")
        unique_together = ('comment', 'user', )

    def __unicode__(self):
        return "Comment Rating: %s" % self.rating
