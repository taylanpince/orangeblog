from django.db.models import Q
from django.template import RequestContext
from django.http import HttpResponseRedirect
from django.core.urlresolvers import reverse
from django.template.defaultfilters import slugify
from django.views.decorators.http import require_POST
from django.utils.translation import ugettext as _
from django.contrib.auth.decorators import login_required
from django.core.paginator import ObjectPaginator, InvalidPage
from django.shortcuts import render_to_response, get_object_or_404
from django.contrib.admin.views.decorators import staff_member_required

from tools.utils import unslugify
from entries.models import Category, Post
from tools.pagination import make_url_pattern
from entries.forms import PostTitleForm, PostSubmitForm, PostChangeForm, PostDeleteForm


def home(request):
    """ Home Page """
    
    pager = ObjectPaginator(Post.objects.all(), 10)
    pager.page = int(request.GET.get("sayfa", 1))
    
    try:
        posts = pager.get_page(pager.page - 1)
    except:
        posts = None
    
    pager.url = make_url_pattern(reverse("home"), request.GET)
    
    return render_to_response("entries/home.html", {
        "posts" : posts,
        "pager" : pager
    }, context_instance=RequestContext(request))


def post_detail(request, slug):
    """ Post detail page """
    
    post = get_object_or_404(Post.objects, slug=slug)
    
    return render_to_response("entries/post_detail.html", {
        "post" : post,
    }, context_instance=RequestContext(request))


def category_list(request):
    """ A list of available categories """
    
    categories = Category.objects.all()
    
    return render_to_response("entries/category_list.html", {
        "categories" : categories,
    }, context_instance=RequestContext(request))


def category_detail(request, slug):
    """ Category detail page """
    
    category = get_object_or_404(Category.objects, slug=slug)
    
    pager = ObjectPaginator(Post.objects.filter(category=category), 10)
    pager.page = int(request.GET.get("sayfa", 1))
    
    try:
        posts = pager.get_page(pager.page - 1)
    except:
        posts = None
    
    pager.url = make_url_pattern(reverse("home"), request.GET)
    
    return render_to_response("entries/category_detail.html", {
        "category" : category,
        "posts" : posts,
        "pager" : pager
    }, context_instance=RequestContext(request))


def lookup_post(request, slug):
    """ Tries to find the submitted slug, if it can't give a 404 """
    
    try:
        post = Post.objects.get(slug=slug)
    except:
        request.session["post_submit_title"] = unslugify(slug)
        
        return HttpResponseRedirect(reverse("post_submit", kwargs={"slug": slug}))
    else:
        return HttpResponseRedirect(reverse("post_detail", kwargs={"slug": slug}))


@login_required
@require_POST
def get_or_submit(request):
    """ Tries to find the submitted title, if it can't, redirects to the submit page """
    
    form = PostTitleForm(request.POST, prefix="PostTitleForm")
    
    if form.is_valid():
        try:
            post = Post.objects.get(slug=form.slug)
        except:
            request.session["post_submit_title"] = form.cleaned_data["title"]
            
            return HttpResponseRedirect(reverse("post_submit", kwargs={"slug": form.slug}))
        else:
            return HttpResponseRedirect(reverse("post_detail", kwargs={"slug": form.slug}))
    else:
        if 'HTTP_REFERER' in request.META:
            return HttpResponseRedirect(request.META['HTTP_REFERER'])
        else:
            return HttpResponseRedirect(reverse("home"))


@login_required
def post_submit(request, slug):
    """ Submit a new post """
    
    if request.method == "POST":
        related_posts = None
        form = PostSubmitForm(request.POST, prefix="PostSubmitForm")
        
        if form.is_valid():
            post = form.save(commit=False)
            post.user = request.user
            post.save()
            
            request.user.message_set.create(message=_("Your entry has been posted."))
            
            if "post_submit_title" in request.session:
                del request.session["post_submit_title"]
            
            return HttpResponseRedirect(reverse("post_detail", kwargs={"slug": post.slug}))
    else:
        related_title = request.session.get("post_submit_title", unslugify(slug))
        related_posts = Post.objects.filter(title__icontains=related_title)
        for keyword in related_title.split(" "):
            related_posts = related_posts | Post.objects.filter(title__icontains=keyword)
        
        form = PostSubmitForm(prefix="PostSubmitForm", initial={"title": request.session.get("post_submit_title", "")})
    
    return render_to_response("entries/post_submit.html", {
        "form" : form,
        "post_slug" : slug,
        "related_posts" : related_posts
    }, context_instance=RequestContext(request))


@login_required
def post_change(request, slug):
    """ Changes an existing post """
    
    post = get_object_or_404(Post.objects, slug=slug)
    
    if request.user.is_staff or (request.user is post.user):
        if request.method == "POST":
            form = PostChangeForm(request.POST, prefix="PostChangeForm")
        
            if form.is_valid():
                post.category = form.cleaned_data["category"]
                post.content_md = form.cleaned_data["content_md"]
                post.save()
            
                request.user.message_set.create(message=_("Your changes have been saved."))
            
                return HttpResponseRedirect(reverse("post_detail", kwargs={"slug": post.slug}))
        else:
            form = PostChangeForm(prefix="PostChangeForm", instance=post)
    
        return render_to_response("entries/post_change.html", {
            "post" : post,
            "form" : form
        }, context_instance=RequestContext(request))
    else:
        request.user.message_set.create(message=_("You don't have permission to delete this entry."))
        
        return HttpResponseRedirect(reverse("post_detail", kwargs={"slug": post.slug}))


@login_required
@staff_member_required
def post_delete(request, slug):
    """ Deletes an existing post """

    post = get_object_or_404(Post.objects, slug=slug)

    if request.method == "POST":
        form = PostDeleteForm(request.POST, prefix="PostDeleteForm")
        
        if form.is_valid():
            post.delete()

            request.user.message_set.create(message=_("Your entry has been deleted."))

            return HttpResponseRedirect(reverse("home"))
    else:
        form = PostDeleteForm(prefix="PostDeleteForm")

    return render_to_response("entries/post_delete.html", {
        "post" : post,
        "form" : form
    }, context_instance=RequestContext(request))
