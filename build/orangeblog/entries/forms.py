from django import newforms as forms
from django.template.defaultfilters import slugify
from django.utils.translation import ugettext as _

from entries.models import Post


class PostTitleForm(forms.Form):
    """ Used to submit a title value """
    
    title = forms.CharField(max_length=100)
    
    slug = None
    
    def clean(self):
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