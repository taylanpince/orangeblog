from django import newforms as forms
from django.utils.translation import ugettext as _

from comments.models import Comment


class CommentSubmitForm(forms.ModelForm):
    """ Used to submit a new comment """
    
    class Meta:
        model = Comment
        fields = ("content_md", )


class CommentDeleteForm(forms.Form):
    """ Used to delete a comment """
    
    confirm = forms.BooleanField(widget=forms.CheckboxInput(), label=_(u"I confirm that I want to delete this comment."))
    
    def clean_confirm(self):
        """ Validates that the user checked the confirm box """
        
        if self.cleaned_data.get("confirm", False):
            return self.cleaned_data["confirm"]
        
        raise forms.ValidationError(_(u"You must check the confirmation box to delete this comment."))