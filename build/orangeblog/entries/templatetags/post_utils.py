from datetime import datetime

from django import template

from entries.models import Post, Category

register = template.Library()


@register.inclusion_tag("entries/todays_posts.html")
def todays_entries():
    """ Creates a list of today's entries """
    
    today = datetime.today()
    entries = Post.objects.filter(post_date__day=today.day, post_date__month=today.month, post_date__year=today.year)
    
    return {'entries': entries}


@register.inclusion_tag("entries/recent_posts.html")
def recent_entries():
    """ Creates a list of recent entries """
    
    entries = Post.objects.all()[:10]
    
    return {'entries': entries}


@register.inclusion_tag("entries/category_links.html")
def category_links():
    """ Creates a list of all categories """

    categories = Category.objects.all()

    return {'categories': categories}
