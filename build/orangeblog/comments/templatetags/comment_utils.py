from django import template

from comments.forms import CommentSubmitForm

register = template.Library()


@register.inclusion_tag("comments/comment_form.html")
def comment_submit_form(post):
    """ Creates a list of today's entries """
    
    form = CommentSubmitForm(prefix="CommentSubmitForm")
    
    return {'form': form, 'post': post}