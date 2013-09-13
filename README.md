##MySeo - SEO solution for PyroCMS

The MySeo module lets site admin edit pages and posts SEO related information, such as meta title, description etc, from one admin page. That means, no more surfing the pages to edit meta information.

- version - 2.0.3
- Author  - Tanel Tammik - keevitaja@gmail.com
- Support - [PyroCMS forum](https://forum.pyrocms.com/discussion/24523/myseo-seo-module-for-pyrocms)

###Install

Copy folder `myseo` from this repo to you modules folder and install as usual.

To make MySeo work with blog posts, please see blog.php file. It is part of PyroCMS blog module. You have to hack this file, because PyroCMS core blog module does not have SEO support. Do not ask me why.

I recommend you do not overwrite the core file, but make the modifications only in `_single_view()` method.

###Usage

When all is done, point your browser to Content > MySeo in admin control panel and start making google to like your site.

###If you like this module

If you like this module, please follow me [@keevitaja](https://twitter.com/keevitaja)