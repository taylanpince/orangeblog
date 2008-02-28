from django.template import RequestContext
from django.http import HttpResponseRedirect
from django.core.urlresolvers import reverse
from django.template.defaultfilters import slugify
from django.views.decorators.http import require_POST
from django.utils.translation import ugettext as _
from django.contrib.auth.decorators import login_required
from django.core.paginator import ObjectPaginator, InvalidPage
from django.shortcuts import render_to_response, get_object_or_404

from entries.forms import PostTitleForm, PostSubmitForm
from entries.models import Category, Post


def home(request, page=1):
    """ Home Page """
    pager = ObjectPaginator(Post.objects.all(), 10)
    
    try:
        posts = pager.get_page(int(page) - 1)
    except:
        posts = None
    
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


def category_detail(request, slug, page=1):
    """ Category detail page """
    category = get_object_or_404(Category.objects, slug=slug)
    
    pager = ObjectPaginator(Post.objects.filter(category=category), 10)
    
    try:
        posts = pager.get_page(int(page) - 1)
    except:
        posts = None
    
    return render_to_response("entries/category_detail.html", {
        "category" : category,
        "posts" : posts,
        "pager" : pager
    }, context_instance=RequestContext(request))


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
        form = PostSubmitForm(prefix="PostSubmitForm", initial={"title": request.session.get("post_submit_title", "")})
    
    return render_to_response("entries/post_submit.html", {
        "form" : form,
        "post_slug" : slug
    }, context_instance=RequestContext(request))
