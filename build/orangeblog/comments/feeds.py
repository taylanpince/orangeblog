from django.core.urlresolvers import reverse
from django.contrib.syndication.feeds import Feed

from comments.models import Comment
from entries.models import Post


class RssLatestComments(Feed):
    title_template = "feeds/comment_title.html"
    description_template = "feeds/comment_description.html"
    
    title = "orangeblog - son yorumlar"
    
    def link(self):
        return reverse("home")

    description = "orangeblog'a en son eklenen yorumlar"

    def items(self):
        return Comment.objects.order_by('-post_date')[:20]


class RssCommentsByPost(Feed):
    title_template = "feeds/comment_title.html"
    description_template = "feeds/comment_description.html"
    
    def get_object(self, bits):
        if len(bits) != 1:
            raise ObjectDoesNotExist
        return Post.objects.get(slug__exact=bits[0])

    def title(self, obj):
        return "orangeblog - %s entrisindeki yorumlar" % obj.title
    
    def link(self, obj):
        if not obj:
            raise FeedDoesNotExist
        return reverse("post_detail", kwargs={"slug": obj.slug})

    def description(self, obj):
        return "%(post)s entrisindeki yorumlar" % {"post": obj.title}

    def items(self, obj):
        return obj.comment_set.all()