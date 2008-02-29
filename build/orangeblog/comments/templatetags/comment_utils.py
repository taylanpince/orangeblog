from django import template

from comments.models import Comment
from comments.forms import CommentSubmitForm

register = template.Library()


@register.inclusion_tag("comments/comment_form.html")
def comment_submit_form(post):
    """ Creates a list of today's entries """
    
    form = CommentSubmitForm(prefix="CommentSubmitForm")
    
    return {'form': form, 'post': post}


@register.inclusion_tag("comments/recent_comments.html")
def recent_comments():
    """ Lists the latest comments """

    comments = Comment.objects.order_by("-post_date")[:10]

    return {'comments': comments}