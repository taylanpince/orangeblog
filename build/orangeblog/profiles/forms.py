from django import newforms as forms
from django.contrib.auth.models import User
from django.contrib.auth import authenticate
from django.utils.translation import ugettext as _


class UserLoginForm(forms.Form):
    username = forms.CharField(label=_("Username"), required=True)
    password = forms.CharField(label=_("Password"), required=True, widget=forms.PasswordInput())

    user = None

    def clean(self):
        # only do further checks if the rest was valid
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