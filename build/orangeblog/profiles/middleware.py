from datetime import datetime

from profiles.models import UserProfile


class UserProfileActivityMiddleware(object):
    """ Updates the last_active attribute of the logged in user """
    
    def process_request(self, request):
        if request.user.is_authenticated():
            try:
                profile = request.user.get_profile()
                profile.last_active = datetime.now()
                profile.save()
            except:
                pass