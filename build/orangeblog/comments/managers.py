from django.db import models

class CommentManager(models.Manager):
    """ Calls public comments """
    
    def get_query_set(self):
        return super(CommentManager, self).get_query_set().filter(public=True).order_by('-post_date').extra(
            select={
                'rating': "SELECT AVG(rating) FROM comments_commentrating as c WHERE comments_comment.id = c.comment_id",
            }
        )