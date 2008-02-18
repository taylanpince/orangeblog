from django.db import models


class PostManager(models.Manager):
    """ Calls public entries order by post date descending, with extra attributes """
    
    def get_query_set(self):
        return super(PostManager, self).get_query_set().filter(public=True).order_by('-post_date').extra(
            select={
                'avg_score': "SELECT AVG(vote) FROM entries_postvote as v WHERE entries_post.id = v.post_id",
                'num_votes': "SELECT COUNT(vote) FROM entries_postvote as v WHERE entries_post.id = v.post_id",
                'rating': "SELECT AVG(rating) FROM entries_postrating as r WHERE entries_post.id = r.post_id",
                'num_comments': "SELECT COUNT(id) FROM comments_comment as c WHERE entries_post.id = c.post_id",
            }
        )

class CategoryManager(models.Manager):
    """ Calls categories ordered by weight, with extra attributes """

    def get_query_set(self):
        return super(CategoryManager, self).get_query_set().order_by('weight').extra(
            select={
                'num_entries': "SELECT COUNT(*) FROM entries_post as p WHERE entries_category.id = p.category_id",
            }
        )
