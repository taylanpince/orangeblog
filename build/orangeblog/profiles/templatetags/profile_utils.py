from datetime import datetime, timedelta

from django import template

from profiles.models import UserProfile
from profiles.forms import UserLoginForm

register = template.Library()


@register.inclusion_tag("profiles/user_login_form.html")
def user_login_form():
    """ Renders the user login form """
    
    form = UserLoginForm(prefix="UserLoginForm")
    
    return {'form': form}


@register.inclusion_tag("profiles/active_users.html")
def active_users():
    """ Renders a list of users logged in """
    
    offset = datetime.now() - timedelta(minutes=15)
    profiles = UserProfile.objects.filter(last_active__gte=offset)
    
    return {'profiles': profiles}