from django.core.urlresolvers import reverse
from django.contrib.syndication.feeds import Feed

from entries.models import Post, Category


class RssLatestPosts(Feed):
    title_template = "feeds/post_title.html"
    description_template = "feeds/post_description.html"
    
    title = "orangeblog - son entriler"
    
    def link(self):
        return reverse("post_landing")

    description = "orangeblog'a en son eklenen entriler"

    def items(self):
        return Post.objects.order_by('-post_date')[:15]


class RssLatestPostsByCategory(Feed):
    title_template = "feeds/post_title.html"
    description_template = "feeds/post_description.html"

    def get_object(self, bits):
        if len(bits) != 1:
            raise ObjectDoesNotExist
        return Category.objects.get(slug__exact=bits[0])

    def title(self, obj):
        return "orangeblog - %s kategorisindeki en son entriler" % obj.name

    def link(self, obj):
        if not obj:
            raise FeedDoesNotExist
        return reverse("category_detail", kwargs={"slug": obj.slug})

    def description(self, obj):
        return "%(category)s kategorisine en son eklenen entriler" % {"category": obj.name}

    def items(self, obj):
        return obj.post_set.all()[:15]