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
