from django.template.defaultfilters import slugify

def generate_slug(model, title):
    slug    = slugify(title)
    count   = model.objects.filter(slug__startswith=slug).count()
    
    if count > 1:
        slug = '%s-%s' % (slug, count)
    
    return slug