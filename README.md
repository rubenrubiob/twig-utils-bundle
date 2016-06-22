TwigUtils Bundle
================

This is a Symfony bundle that provides some useful Twig functions ready
to use in your projects.

HTML and CSS thanks to [Gemma Casals](https://twitter.com/gemmacasals).

Installation
============

Step 1: Download the Bundle
---------------------------

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```bash
$ composer require rrb/twig-utils-bundle "*"
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

Step 2: Enable the Bundle
-------------------------

Then, enable the bundle by adding it to the list of registered bundles
in the `app/AppKernel.php` file of your project:

```php
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...

            new Rrb\TwigUtilsBundle\RrbTwigUtilsBundle(),
        );

        // ...
    }

    // ...
}
```

Usage
=====

Lazy loading images
-------------------

The bundles provides a custom implementation for lazy loading images based on the
explanation showed in [this video](https://www.youtube.com/watch?v=iAgSvlYavX0)
of the [Polymer Project](https://www.polymer-project.org/1.0/).

The idea is to render a low-resolution image of the image whilst it is
loading, so the user may start reading the content without having to wait
for all images to load.

The strategy is to show the low-resolution image as a base64 encoded image, so
the browser can immediately render it, avoiding a new request. Thus,
the low-resolution image should be at most 10px width by 10px height.

The low-resolution image is rendered as a background of a `<div>` that
wraps the `<img>` tag.


### lazy_image_render

```twig
{{ lazy_image_render(imageUrl, imageBase46, title='', alt='') }}
```

`imageUrl`: the url of the image to load.

&nbsp;&nbsp;&nbsp;&nbsp;**type**: `string`

`imageBase64`

&nbsp;&nbsp;&nbsp;&nbsp;**type**: `string`

`title` ***(optional)***

&nbsp;&nbsp;&nbsp;&nbsp;**type**: `string` **default**: `''`

`alt` ***(optional)***

&nbsp;&nbsp;&nbsp;&nbsp;**type**: `string` **default**: `''`

Renders the `<img>` tag wrapped in a `<div>` whose background is the
base64 encoded image. It optionally accepts the `title` and `alt` of
the image.

The HTML rendered is as follows:

```html
<div class="lazy-image-wrapper" style="background-image: url('data:image/jpeg;base64,/9j/4AAQSkZJRg...');">
    <img class="lazy-image"
         title="title of the image"
         alt="alt of the image"
         src="http://www.example.com/image1.png"
         onload="this.style.opacity = '1'"
    />
</div>
```


### lazy_image_css

```twig
{{ lazy_image_css() }}
```

Renders a stylesheet for the lazy images to work. You have to call this
function just once in your `<head>` tag. Of course, you may also add the
styles on your own stylesheet if you want to tweak them.

The CSS content included in the stylesheet is:

```css
.lazy-image{
    opacity:0;
    -webkit-transition: all 0.5s ease-in-out;
    transition: all 0.5s ease-in-out;
    max-width: 100%
}
.lazy-image-wrapper{
    background-size: cover;
    background-repeat: no-repeat;
}
```