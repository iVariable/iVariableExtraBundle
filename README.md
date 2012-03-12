# iVariableExtraBundle #

Often used components of my symfony2 apps

Contents:
- iv.user service container getter
- iv.repo service container getter
- twig options

## iv.repo ##

Service shortcut to repositories.

### Configration ###

Make aliases to yout repo in your config.yml

	i_variable_extra:
		repo:
			blogpost: AcmeBundle:Blog\Post
			blogcomment: AcmeBundle:Blog\Comment

Instead of:

	$this->getDoctrine()->getRepository('AcmeBundle:Blog\Post');

Use:

	$this->get('iv.repo.blogpost');