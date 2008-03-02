from datetime import datetime

from django import template

from entries.forms import PostTitleForm
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


@register.inclusion_tag("entries/title_form.html")
def post_title_form():
    """ Renders the post title form """
    
    return {'form': PostTitleForm(prefix="PostTitleForm")}


@register.inclusion_tag("entries/post_controls.html")
def show_post_controls(user, post):
    """ Renders the post controls """
    
    enable_edit = False
    enable_delete = False
    
    if user.is_authenticated():
        if user.is_staff:
            enable_edit = True
            enable_delete = True
        elif user == post.user:
            enable_edit = True
    
    return {'post': post, 'enable_edit': enable_edit, 'enable_delete': enable_delete}
