import os
from PIL import Image

from django.conf import settings
from django.contrib.auth.models import User

from profiles.models import UserProfile


def create_profile(sender, instance, signal, *args, **kwargs):
    if type(instance) == User:
        try:
            UserProfile(user=instance, nickname=instance.username).save()
        except:
            pass


def delete_profile(sender, instance, signal, *args, **kwargs):
    if type(instance) == User:
        try:
            UserProfile.objects.filter(user=instance).delete()
        except:
            pass


def generate_avatar(sender, instance, signal, *args, **kwargs):
    if type(instance) == UserProfile:
        if instance.avatar:
            avatar = Image.open(os.path.join(settings.MEDIA_ROOT, instance.avatar))
            
            if avatar.mode != "RGB":
                avatar = avatar.convert("RGB")
            
            if avatar.size[0] > avatar.size[1]:
                avatar = avatar.resize(((120 * avatar.size[0] / avatar.size[1]), 120), Image.ANTIALIAS)
                avatar = avatar.crop((0, 0, 120, 120))
            elif avatar.size[1] > avatar.size[0]:
                avatar = avatar.resize((120, (120 * avatar.size[1] / avatar.size[0])), Image.ANTIALIAS)
                avatar = avatar.crop((0, 0, 120, 120))
            else:
                avatar.resize((120, 120), Image.ANTIALIAS)
            
            # Large Avatar
            image_path = os.path.join(settings.MEDIA_ROOT, 'dynamic', 'users', ( instance.slug + '.jpg' ) )
            avatar.save(image_path)
            
            # Small Avatar
            image_path = os.path.join(settings.MEDIA_ROOT, 'dynamic', 'users', ( instance.slug + '-small.jpg' ) )
            small_avatar = avatar.resize((45, 45), Image.ANTIALIAS)
            small_avatar.save(image_path)
