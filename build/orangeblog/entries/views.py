from django.template import RequestContext
from django.shortcuts import render_to_response, get_object_or_404

from entries.models import Category, Post


def home(request):
    """ Home Page """
    entries = Post.objects.all()[:10]
    
    return render_to_response("entries/home.html", {
        "entries" : entries,
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
    
    return render_to_response("entries/category_detail.html", {
        "category" : category,
    }, context_instance=RequestContext(request))
