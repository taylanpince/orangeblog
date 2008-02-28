from django.conf import settings
from django.template import RequestContext
from django.http import HttpResponseRedirect
from django.core.urlresolvers import reverse
from django.contrib.auth import login, logout
from django.utils.translation import ugettext as _
from django.contrib.auth.decorators import login_required
from django.shortcuts import render_to_response, get_object_or_404

from profiles.models import UserProfile
from profiles.forms import UserLoginForm


def user_info(request, slug):
    """ User profile page """
    profile = get_object_or_404(UserProfile.objects, slug=slug)
    
    return render_to_response("profiles/user_info.html", {
        "profile" : profile
    }, context_instance=RequestContext(request))


@login_required
def user_logout(request):
    """ User logout """
    
    if 'HTTP_REFERER' in request.META:
        next = request.META['HTTP_REFERER']
    else:
        next = reverse('home')
    
    logout(request)
    
    return HttpResponseRedirect(next)


def user_login(request):
    """ User login page """
    
    if request.method == "POST":
        form = UserLoginForm(request.POST, prefix="UserLoginForm")
        if form.is_valid():
            login(request, form.user)
            
            request.user.message_set.create(message=_("Welcome back!"))
            
            if "next" in request.GET:
                return HttpResponseRedirect(request.GET["next"])
            else:
                return HttpResponseRedirect(settings.LOGIN_REDIRECT_URL)
        else:
            return render_to_response("profiles/user_login.html", {
                "form" : form,
                "login_page" : True
            }, context_instance=RequestContext(request))
    else:
        return render_to_response("profiles/user_login.html", {
            "form" : UserLoginForm(prefix="UserLoginForm"),
            "login_page" : True
        }, context_instance=RequestContext(request))