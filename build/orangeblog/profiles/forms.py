from django import newforms as forms
from django.core.mail import EmailMessage
from django.contrib.auth.models import User
from django.template import Context, loader
from django.contrib.auth import authenticate
from django.utils.translation import ugettext as _

from profiles.models import UserProfile


class UserLoginForm(forms.Form):
    """ Creates a login form, validates credentials """
    
    username = forms.CharField(label=_("Username"), required=True)
    password = forms.CharField(label=_("Password"), required=True, widget=forms.PasswordInput())

    user = None

    def clean(self):
        if self._errors:
            return
        else:
            try:
                user = User.objects.get(username=self.cleaned_data['username'])
            except User.DoesNotExist:
                raise forms.ValidationError(_("You could not be logged in with the e-mail address and password you specified."))

            user = authenticate(username=self.cleaned_data['username'], password=self.cleaned_data['password'])
            
            if user is not None:
                if user.is_active:
                    self.user = user                    
                else:
                    raise forms.ValidationError(_("This account is currently inactive. If you are a new member you will need to use the code in your activation email to activate your account."))
            else:
                raise forms.ValidationError(_("You could not be logged in with the e-mail address and password you specified."))


class UserProfileForm(forms.ModelForm):
    """ Creates a profile update form """
    
    class Meta:
        model = UserProfile
        fields = ("nickname", "birth_date", "avatar", )


class UserForm(forms.ModelForm):
    """ Creates a user update form """

    class Meta:
        model = User
        fields = ("username", "email", )


class UserPasswordForm(forms.Form):
    """ Password change form, validates the old password, passes on the new one """
    
    def __init__(self, user, *args, **kwargs):
        self.user = user
        super(UserPasswordForm, self).__init__(*args, **kwargs)
    
    password = forms.CharField(widget=forms.PasswordInput(render_value=False), label=_(u'current password'))
    new_password = forms.CharField(widget=forms.PasswordInput(render_value=False), label=_(u'new password'))
    new_password_confirm = forms.CharField(widget=forms.PasswordInput(render_value=False), label=_(u'new password (again)'))
    
    def clean_password(self):
        if self.user.check_password(self.cleaned_data["password"]):
            return self.cleaned_data["password"]
        else:
            raise forms.ValidationError(_("You entered the wrong password."))
    
    def clean_new_password_confirm(self):
        if self.cleaned_data["new_password"] != self.cleaned_data["new_password_confirm"]:
            raise forms.ValidationError(_("Please make sure that your new passwords match."))
        else:
            return self.cleaned_data["new_password_confirm"]
    
    def save(self):
        self.user.set_password(self.cleaned_data["new_password"])
        self.user.save()


class UserPasswordResetForm(forms.Form):
    """ Resets the user's password, validates the email first """
    
    email = forms.EmailField()
    
    user = None
    
    def clean_email(self):
        try:
            self.user = User.objects.get(email__exact=self.cleaned_data["email"])
        except:
            raise forms.ValidationError(_("There are no users registered with the email address you entered."))
        else:
            return self.cleaned_data["email"]
    
    def save(self, email_template="profiles/user_password_reset.txt"):
        new_pass = User.objects.make_random_password()
        self.user.set_password(new_pass)
        self.user.save()
        
        t = loader.get_template(email_template)
        c = {"new_password": new_pass, "user": user}
        
        EmailMessage(subject=_("Your password has been reset"), body=t.render(Context(c)), to=[user.email]).send()
