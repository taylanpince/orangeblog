from django import newforms as forms
from django.template.defaultfilters import slugify
from django.utils.translation import ugettext as _

from entries.models import Post


class PostTitleForm(forms.Form):
    """ Used to submit a title value """
    
    title = forms.CharField(max_length=100, min_length=3)
    
    slug = None
    
    def clean(self):
        if self._errors:
            return
        else:
            self.slug = slugify(self.cleaned_data["title"])
        
            return self.cleaned_data


class PostSubmitForm(forms.ModelForm):
    """ Used to submit a new post """
    
    def clean_title(self):
        try:
            post = Post.objects.get(slug=slugify(self.cleaned_data['title']))
        except Post.DoesNotExist:
            pass
        else:
            raise forms.ValidationError(_("This title is already taken."))
        return self.cleaned_data['title']
    
    class Meta:
        model = Post
        fields = ("title", "category", "content_md")


class PostChangeForm(forms.ModelForm):
    """ Used to change an existing post """

    class Meta:
        model = Post
        fields = ("category", "content_md")


class PostDeleteForm(forms.Form):
    """ Used to delete an entry """

    confirm = forms.BooleanField(widget=forms.CheckboxInput(), label=_(u"I confirm that I want to delete this entry."))

    def clean_confirm(self):
        """ Validates that the user checked the confirm box """

        if self.cleaned_data.get("confirm", False):
            return self.cleaned_data["confirm"]

        raise forms.ValidationError(_(u"You must check the confirmation box to delete this entry."))
