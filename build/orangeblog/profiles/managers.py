from django.db import models


class ProfileManager(models.Manager):
    """ Adds a few extra attributes to the profile instance """
    
    def get_query_set(self):
        return super(ProfileManager, self).get_query_set().extra(
            select={
                'num_posts': "SELECT COUNT(id) FROM entries_post as p WHERE profiles_userprofile.user_id = p.user_id",
                'num_comments': "SELECT COUNT(id) FROM comments_comment as c WHERE profiles_userprofile.user_id = c.user_id",
            }
        )