import sys
import os

pathname = os.path.dirname(sys.argv[0])
sys.path.append(os.path.normpath(os.path.join(os.path.abspath(pathname), '../../')))
sys.path.append(os.path.normpath(os.path.join(os.path.abspath(pathname), '../')))
os.environ['DJANGO_SETTINGS_MODULE'] = 'orangeblog.settings'

from django.core.mail import EmailMessage
from django.template import Context, loader

from django.contrib.auth.models import User


users = User.objects.filter(is_staff=False)

for user in users:
    new_pass = User.objects.make_random_password()
    user.set_password(new_pass)
    user.save()
    
    body = loader.get_template("migration/launch_body.txt")
    subject = loader.get_template("migration/launch_subject.txt")
    params = {"new_password": new_pass, "user": user}
    
    EmailMessage(subject=subject.render([]), body=body.render(Context(params)), to=[user.email]).send()
