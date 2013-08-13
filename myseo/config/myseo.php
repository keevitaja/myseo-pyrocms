<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['myseo_tables'] = array(
    'pages' => 'pages',
    'posts' => 'myseo_posts_meta'
);

$config['myseo_fields'] = array(
    'pages' => array(
        'title' => 'meta_title',
        'keywords' => 'meta_keywords',
        'description' => 'meta_description',
        'no_index' => 'meta_robots_no_index',
        'no_follow' => 'meta_robots_no_follow'
    ),
    'posts' => array(
        'title' => 'title',
        'keywords' => 'keywords',
        'description' => 'description',
        'no_index' => 'no_index',
        'no_follow' => 'no_follow'
    )
);

$config['myseo_filters'] = array(
    'pages' => array(
        'hide_drafts' => array(
            'type' => 'checkbox',
            'title' => 'myseo:pages:filters:fields:draft',
            'title_long' => 'myseo:pages:filters:fields:long:draft'
        ),
        'top_page' => array(
            'type' => 'dropdown_from_model',
            'method' => 'top_pages',
            'title' => 'myseo:pages:filters:fields:top_page',
            'title_long' => 'myseo:pages:filters:fields:long:top_page'
        ),
        'by_title' => array(
            'type' => 'input',
            'title' => 'myseo:pages:filters:fields:title',
            'title_long' => 'myseo:pages:filters:fields:long:title'
        ),
        'by_uri' => array(
            'type' => 'input',
            'title' => 'myseo:pages:filters:fields:uri',
            'title_long' => 'myseo:pages:filters:fields:long:uri'
        )
    ),
    'posts' => array(
        'hide_drafts' => array(
            'type' => 'checkbox',
            'title' => 'myseo:posts:filters:fields:draft',
            'title_long' => 'myseo:posts:filters:fields:long:draft'
        ),
        'category' => array(
            'type' => 'dropdown_from_model',
            'method' => 'categories',
            'title' => 'myseo:posts:filters:fields:category',
            'title_long' => 'myseo:posts:filters:fields:long:category'
        ),
        'by_title' => array(
            'type' => 'input',
            'title' => 'myseo:posts:filters:fields:title',
            'title_long' => 'myseo:posts:filters:fields:long:title'
        ),
        'by_slug' => array(
            'type' => 'input',
            'title' => 'myseo:posts:filters:fields:slug',
            'title_long' => 'myseo:posts:filters:fields:long:slug'
        )
    )
);