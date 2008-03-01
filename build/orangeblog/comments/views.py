from django.template import RequestContext
from django.http import HttpResponseRedirect
from django.core.urlresolvers import reverse
from django.views.decorators.http import require_POST
from django.utils.translation import ugettext as _
from django.contrib.auth.decorators import login_required
from django.shortcuts import render_to_response, get_object_or_404

from entries.models import Post
from comments.models import Comment
from comments.forms import CommentSubmitForm


@login_required
@require_POST
def comment_submit(request, slug):
    """ Submit a new comment """
    
    post = get_object_or_404(Post.objects, slug=slug)
    form = CommentSubmitForm(request.POST, prefix="CommentSubmitForm")
    
    if form.is_valid():
        comment = form.save(commit=False)
        comment.user = request.user
        comment.post = post
        comment.save()
        
        request.user.message_set.create(message=_("Your comment has been posted."))
        return HttpResponseRedirect(reverse("post_detail", kwargs={"slug": post.slug}))
    else:
        return render_to_response("comments/comment_error.html", {
            "form" : form,
            "post" : post
        }, context_instance=RequestContext(request))


@login_required
def comment_delete(request, id):
    """ Delete a comment """

    comment = get_object_or_404(Comment.objects, pk=id)

    if request.user.is_staff or (request.user is comment.user):
        post = comment.post
        comment.delete()

        request.user.message_set.create(message=_("Your comment has been deleted."))

        return HttpResponseRedirect(reverse("post_detail", kwargs={"slug": post.slug}))
    else:
        request.user.message_set.create(message=_("You don't have permission to delete this comment."))

        return HttpResponseRedirect(reverse("post_detail", kwargs={"slug": post.slug}))


@login_required
def comment_change(request, id):
    """ Update a comment """

    comment = get_object_or_404(Comment.objects, pk=id)

    if request.user.is_staff or (request.user is comment.user):
        if request.method == "POST":
            form = CommentSubmitForm(request.POST, prefix="CommentChangeForm")

            if form.is_valid():
                comment.content_md = form.cleaned_data["content_md"]
                comment.save()

                request.user.message_set.create(message=_("Your comment has been updated."))

                return HttpResponseRedirect(comment.get_absolute_url())
        else:
            form = CommentSubmitForm(prefix="CommentChangeForm", instance=comment)

        return render_to_response("comments/comment_change.html", {
            "comment" : comment,
            "form" : form
        }, context_instance=RequestContext(request))
    else:
        request.user.message_set.create(message=_("You don't have permission to modify this comment."))

        return HttpResponseRedirect(comment.get_absolute_url())
