<?= $renderer->render('header') ?>

<h1> Binevenue sur le blog ( indexphp plus header et footer</h1>

<ul>
<li><a href="<?= $router->generateUri('blog.show', ['slug' => 'zza056-54lm']); ?>"> Article 1</a></li>
<li> Article</li><li> Article</li>
<li> Article</li>
</ul>
<?= $renderer->render('footer') ?>