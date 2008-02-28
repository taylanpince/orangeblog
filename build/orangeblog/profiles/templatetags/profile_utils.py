from django import template

from profiles.forms import UserLoginForm

register = template.Library()


@register.inclusion_tag("profiles/user_login_form.html")
def user_login_form():
    """ Renders the user login form """
    
    form = UserLoginForm(prefix="UserLoginForm")
    
    return {'form': form}