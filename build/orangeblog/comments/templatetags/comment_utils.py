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


@register.inclusion_tag("comments/comment_controls.html")
def show_comment_controls(user, comment):
    """ Renders the comment controls """

    enable_edit = False
    enable_delete = False

    if user.is_authenticated():
        if user.is_staff:
            enable_edit = True
            enable_delete = True
        elif user is comment.user:
            enable_edit = True

    return {'comment': comment, 'enable_edit': enable_edit, 'enable_delete': enable_delete}