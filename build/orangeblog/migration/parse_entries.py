import sys
import os
import re

sys.path.append('/Users/taylan/Development/orangeblog/trunk/build')
sys.path.append('/Users/taylan/Development/orangeblog/trunk/build/orangeblog')

os.environ['DJANGO_SETTINGS_MODULE'] = 'settings'

from entries.models import Post
from comments.models import Comment


emphasis = re.compile(r"\[(i|em)\](.*?)\[/(i|em)\]", re.DOTALL|re.IGNORECASE)
strong = re.compile(r"\[(b|strong)\](.*?)\[/(b|strong)\]", re.DOTALL|re.IGNORECASE)
underline = re.compile(r"\[u\](.*?)\[/u\]", re.DOTALL|re.IGNORECASE)
colour = re.compile(r"\[renk=(\"|'?)(.*?)(\"|'?)\](.*?)\[/renk\]", re.DOTALL|re.IGNORECASE)
size = re.compile(r"\[boyut=(\"|'?)(.*?)(\"|'?)\](.*?)\[/boyut\]", re.DOTALL|re.IGNORECASE)
image = re.compile(r"\[img\](.*?)\[/img\]", re.IGNORECASE)
url = re.compile(r"\[url=(\"|'?)(.*?)(\"|'?)\](.*?)\[/url\]", re.IGNORECASE)
email_without_title = re.compile(r"\[email\](.*?)\[/email\]", re.IGNORECASE)
email_with_title = re.compile(r"\[email=(\"|'?)(.*?)(\"|'?)\](.*?)\[/email\]", re.IGNORECASE)

lookup = re.compile(r"\((gbkz|tbkz):( ?)(.*?)\)", re.IGNORECASE)
lookup_short = re.compile(r"\[(gbkz|bkz|tez|g|k|t)\](.*?)\[/(gbkz|bkz|tez|g|k|t)\]", re.IGNORECASE)


posts = Post.objects.all()

for post in posts:
    post.content_md = re.sub(emphasis, r"*\2*", post.content_md)
    post.content_md = re.sub(strong, r"**\2**", post.content_md)
    post.content_md = re.sub(underline, r"\1", post.content_md)
    post.content_md = re.sub(colour, r"\4", post.content_md)
    post.content_md = re.sub(size, r"\4", post.content_md)
    post.content_md = re.sub(image, r"![](\1)", post.content_md)
    post.content_md = re.sub(url, r"[\4](\2)", post.content_md)
    post.content_md = re.sub(email_without_title, r"<\1>", post.content_md)
    post.content_md = re.sub(email_with_title, r"<\2>", post.content_md)
    
    post.content_md = re.sub(lookup, r"(bkz: \3)", post.content_md)
    post.content_md = re.sub(lookup_short, r"(bkz: \2)", post.content_md)
    
    post.save()


comments = Comment.objects.all()

for comment in comments:
    comment.content_md = re.sub(emphasis, r"*\2*", comment.content_md)
    comment.content_md = re.sub(strong, r"**\2**", comment.content_md)
    comment.content_md = re.sub(underline, r"\1", comment.content_md)
    comment.content_md = re.sub(colour, r"\4", comment.content_md)
    comment.content_md = re.sub(size, r"\4", comment.content_md)
    comment.content_md = re.sub(image, r"![](\1)", comment.content_md)
    comment.content_md = re.sub(url, r"[\4](\2)", comment.content_md)
    comment.content_md = re.sub(email_without_title, r"<\1>", comment.content_md)
    comment.content_md = re.sub(email_with_title, r"<\2>", comment.content_md)
    
    comment.content_md = re.sub(lookup, r"(bkz: \3)", comment.content_md)
    comment.content_md = re.sub(lookup_short, r"(bkz: \2)", comment.content_md)

    comment.save()