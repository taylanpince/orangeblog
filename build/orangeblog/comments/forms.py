from django import newforms as forms
from django.utils.translation import ugettext_lazy as _

from comments.models import Comment


class CommentSubmitForm(forms.ModelForm):
    """ Used to submit a new comment """
    
    class Meta:
        model = Comment
        fields = ("content_md", )