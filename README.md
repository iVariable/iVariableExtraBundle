# iVariableExtraBundle #

Often used components of my symfony2 apps

Contents:
 - iv.user service container getter
 - iv.repo service container getter
 - twig options

## Installation ##

Install to bundles/iVariable/ExtraBundle

## iv.repo ##

Service shortcut to repositories.

### Configuration ###

Make aliases to yout repo in your config.yml

	i_variable_extra:
		repo:
			blogpost: AcmeBundle:Blog\Post
			blogcomment: AcmeBundle:Blog\Comment

Instead of:

	$repo = $this->getDoctrine()->getRepository('AcmeBundle:Blog\Post');
	$post = new Acme\AcmeBundle\Blog\Post('title', 'post',$author);

Use:

	$repo = $this->get('iv.repo.blogpost');
	$post = $this->get('iv.repo.blogpost')->newEntity('title', 'post',$author);