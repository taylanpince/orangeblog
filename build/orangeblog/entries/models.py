from django.db import models
from django.db.models import permalink
from django.contrib.auth.models import User
from django.template.defaultfilters import slugify
from django.utils.translation import ugettext_lazy as _

from tools.utils import mark_lookups
from entries.managers import PostManager, CategoryManager

from markdown import markdown


class Category(models.Model):
    """ A blog category """
    
    name = models.CharField(_("Name"), max_length=100, unique=True)
    slug = models.SlugField(_("Slug"), blank=True, editable=False)
    weight = models.IntegerField(_("Weight"), default=0)
    rated = models.BooleanField(_("Rated"), default=False)
    
    admin_objects = models.Manager()
    objects = CategoryManager()
    
    def get_rss_url(self):
        return "kategoriler/%s" % self.slug
    
    @permalink
    def get_absolute_url(self):
        return ('category_detail', (), {'slug': self.slug})
    
    def save(self):
        self.slug = slugify(self.name)
        
        super(Category, self).save()
    
    def delete(self):
        self.post_set.all().delete()
        
        super(Category, self).delete()
    
    class Admin:
        list_display = ('name', 'weight', 'rated', )

    class Meta:
        verbose_name = _("Category")
        verbose_name_plural = _("Categories")
        ordering = ["weight"]
        permissions = (("can_create_categories", "Can create blog categories"), )

    def __unicode__(self):
        return self.name


class Post(models.Model):
    """ An entry in the blog """
    
    user = models.ForeignKey(User, verbose_name=_("Author"))
    title = models.CharField(_("Title"), max_length=100, unique=True)
    slug = models.SlugField(_("Slug"), blank=True, editable=False, max_length=100)
    category = models.ForeignKey(Category, verbose_name=_("Category"))
    content = models.TextField(_("HTML Content"), blank=True, editable=False)
    content_md = models.TextField(_("Content"))
    public = models.BooleanField(_("Public"), default=True)
    post_date = models.DateTimeField(_("Post Date"), blank=True, auto_now_add=True, editable=False)
    save_date = models.DateTimeField(_("Last Update"), blank=True, auto_now=True, editable=False)
    
    admin_objects = models.Manager()
    objects = PostManager()
    
    def is_rated(self):
        """ Checks to see if this post can be rated or not """
        return self.category.rated
    rated = property(is_rated)
    
    def get_rss_url(self):
        return "entriler/%s" % self.slug
    
    @permalink
    def get_absolute_url(self):
        return ('post_detail', (), {'slug': self.slug})
    
    def save(self):
        self.slug = slugify(self.title)
        self.content = markdown(mark_lookups(self.content_md))
        
        super(Post, self).save()
    
    def delete(self):
        self.postrating_set.all().delete()
        self.postvote_set.all().delete()
        self.comment_set.all().delete()
        
        super(Post, self).delete()
    
    class Admin:
        list_display = ('title', 'category', 'user', 'post_date', 'save_date', 'public', )
        list_filter = ('category', 'public', )
        search_fields = ('title', 'content', )
        date_hierarchy = 'post_date'
    
    class Meta:
        verbose_name = _("Post")
        verbose_name_plural = _("Posts")
        ordering = ["-post_date"]
        permissions = (("can_post_entries", "Can post blog entries"), )
    
    def __unicode__(self):
        return self.title


class PostRating(models.Model):
    """ A rating for a blog post """
    
    post = models.ForeignKey(Post, verbose_name=_("Post"))
    user = models.ForeignKey(User, verbose_name=_("User"))
    rating = models.BooleanField(_("Rating"))
    
    class Admin:
        list_display = ('post', 'user', 'rating', )
        list_filter = ('rating', )
    
    class Meta:
        verbose_name = _("Post Rating")
        verbose_name_plural = _("Post Ratings")
        unique_together = ('post', 'user', )
    
    def __unicode__(self):
        return "Post Rating: %s" % self.rating


class PostVote(models.Model):
    """ A vote for a blog post (for rated categories) """
    
    post = models.ForeignKey(Post, verbose_name=_("Post"))
    user = models.ForeignKey(User, verbose_name=_("User"))
    vote = models.PositiveSmallIntegerField(_("Vote"))
    
    class Admin:
        list_display = ('post', 'user', 'vote', )
    
    class Meta:
        verbose_name = _("Post Vote")
        verbose_name_plural = _("Post Votes")
        unique_together = ('post', 'user', )
    
    def __unicode__(self):
        return "Post Vote: %s" % self.vote
