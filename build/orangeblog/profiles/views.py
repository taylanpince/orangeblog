from django.conf import settings
from django.template import RequestContext
from django.http import HttpResponseRedirect
from django.core.urlresolvers import reverse
from django.contrib.auth import login, logout
from django.utils.translation import ugettext as _
from django.contrib.auth.decorators import login_required
from django.shortcuts import render_to_response, get_object_or_404

from profiles.models import UserProfile
from profiles.forms import UserLoginForm, UserProfileForm, UserForm, UserPasswordForm, UserPasswordResetForm


def user_info(request, slug):
    """ User profile page """
    profile = get_object_or_404(UserProfile.objects, slug=slug)
    
    return render_to_response("profiles/user_info.html", {
        "profile" : profile
    }, context_instance=RequestContext(request))


@login_required
def user_update(request):
    """ User profile settings form """

    user = request.user
    profile = request.user.get_profile()
    
    if request.method == "POST":
        user_form = UserForm(request.POST, instance=user, prefix="UserForm")
        profile_form = UserProfileForm(request.POST, request.FILES, instance=profile, prefix="UserProfileForm")
        
        if user_form.is_valid() and profile_form.is_valid():
            user_form.save()
            profile_form.save()
            
            request.user.message_set.create(message=_("Your profile settings have been updated."))
            
            return HttpResponseRedirect(profile.get_absolute_url())
    
    else:
        user_form = UserForm(instance=user, prefix="UserForm")
        profile_form = UserProfileForm(instance=profile, prefix="UserProfileForm")

    return render_to_response("profiles/user_update.html", {
        "user_form" : user_form,
        "profile_form" : profile_form
    }, context_instance=RequestContext(request))


@login_required
def user_password_update(request):
    """ User password update form """

    if request.method == "POST":
        form = UserPasswordForm(request.user, request.POST, prefix="UserPasswordForm")

        if form.is_valid():
            form.save()
            
            request.user.message_set.create(message=_("Your password has been changed."))
            
            return HttpResponseRedirect(request.user.get_profile().get_absolute_url())

    else:
        form = UserPasswordForm(request.user, prefix="UserPasswordForm")

    return render_to_response("profiles/user_password_update.html", {
        "form" : form
    }, context_instance=RequestContext(request))


@login_required
def user_logout(request):
    """ User logout """
    
    if 'HTTP_REFERER' in request.META:
        next = request.META['HTTP_REFERER']
    else:
        next = reverse('home')
    
    profile = request.user.get_profile()
    profile.last_active = None
    profile.save()
    
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


def user_password_reset(request):
    """ User password reset form """

    if request.method == "POST":
        form = UserPasswordResetForm(request.POST, prefix="UserPasswordResetForm")
        
        if form.is_valid():
            form.save()

            return HttpResponseRedirect(reverse('user_password_reset_done'))
    else:
        form = UserPasswordResetForm(prefix="UserPasswordResetForm")
    
    return render_to_response("profiles/user_password_reset.html", {
        "form" : form
    }, context_instance=RequestContext(request))
