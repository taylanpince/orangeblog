from django.db import models

class CommentManager(models.Manager):
    """ Calls public comments """
    
    def get_query_set(self):
        return super(CommentManager, self).get_query_set().filter(public=True).order_by('-post_date').extra(
            select={
                'rating': "SELECT AVG(rating) FROM comments_commentrating as c WHERE comments_comment.id = c.comment_id",
                'author': "SELECT p.nickname FROM profiles_userprofile as p WHERE comments_comment.user_id = p.user_id",
                'title': "SELECT p.title FROM entries_post as p WHERE comments_comment.post_id = p.id",
            }
        )