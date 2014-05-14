# We Gallery

The missing gallery of WordPress. Simple, yet the effective gallery plugin!


If you are looking for a simple, easy to use and lightweight gallery plugin, **We Gallery** is here for that. It's the only gallery plugin you'll need.

###Features
* Lightweight and intuitive UI
* Display as media grid
* Display as slider &rarr; Flexslider
* Bulk image uploader and WordPress 3.8 UI compatible
* Developer friendly

We Gallery is a modern image gallery for WordPress that leverages WordPress aesthetics. No bulky features, **no extra database tables**. Simply it uses the built-in WordPress post type support and enables you a sleek and rich gallery experience.


### Display a gallery

To display a gallery, you can use any of the following methods:

**In a post/page:**
Simply insert the shortcode below into the post/page.

`[wegallery id="1"]`

**In your theme:**
To insert a gallery in your theme, add the following code to the appropriate theme file.

```php
<?php
if ( function_exists( 'wegal_show_gallery' ) ) {
    wegal_show_gallery( 1, $args = array() );
}
?>
```

## Changelog

** 0.1**
* Initial release.

### Contribute
For feature improvements, please fork the [Github](https://github.com/tareq1988/we-gallery) repository. Contributions are always welcome :-)


## Author
[Tareq Hasan](http://tareq.weDevs.com)