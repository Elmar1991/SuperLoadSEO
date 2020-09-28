# SuperLoadSEO
Create images in the perfect size for your SEO, control the cache time of external CSS and JS files and improve your score on Gmetrix and Google PageSpeed.

##Motivation

Let's suppose you have an image that loads in mobile at 245px and desktop at 475px. With SuperLoadSEO you will be able to easily create a copy of your original image of the exact size that your image needs to have and optionally convert it to WEBP format, saving loading time and earning points on Google Pagespeed. Uniting this tool with a few lines of Javascript you can dynamically load all the images from your website. And don't stop there, with SuperLoadSEO you are able to load external CSS and JS files with all the Cache control necessary for Google to improve your PageSpeed points.

##Some Tech specs

**Use PHP 6 +**
Requires GD Library with support for JPG / PNG and optional WEBP.

The code was written in PHP using the GD library, generally all servers have this standard library, however, **make sure you have WEBP support before requesting this type of file** from SuperLoadSEO. 

##Installation

Upload the superLoadSEO.php file to your server and replace the url pointing to that file by sending the necessary variables to dynamically create your image, CSS or Javascript files. A good idea is to create a redirect in htaccess to another file name such as *images*, it is not necessary, however, it gives a special touch.

##Variables you can send:

**url:** Address of the image you want to convert. This url must be url_encoded so the code don't give an error when sending the GET variable. Mandatory data*.
**size:** Width in pixels of the image you want to generate. Mandatory data for JPG / JPEG / PNG images. 
**quality:** You can further reduce the weight of images by reducing their quality. Number from 0 to 100. Optional, default value 50.
**cache:** Time the file will be cached. Optional, default value 800000
**webp:** 0 or 1 if you want to convert your JPG / JPEG / PNG image to WEBP format. Optional, default value 0.

##How to use:

It's very simple, in the case of an image you need to send the url and size variables:
```
http://test.com/superLoadSEO.php?url=XXX&size=YYY
```
Don't forget to encode the link you want to copy to url, example:
```
For this: https://upload.wikimedia.org/wikipedia/commons/4/47/PNG_transparency_demonstration_1.png
To this: https%3A%2F%2Fupload.wikimedia.org%2Fwikipedia%2Fcommons%2F4%2F47%2FPNG_transparency_demonstration_1.png
```
##SuperLoadSEO and Jquery

You can greatly improve the loading time of your website using SuperLoadSEO with Jquery or pure Javascript. Let's see:

```html
<img id="example" src="2pximage.jpg" superload_url="full_image_url">
```

```javascript
superLoad_size = Math.round( $("#example").width() );
superLoad_url = encodeURIComponent( $("#example").attr("superload_url") );
superLoad_result = "http://test.com/superLoadSEO.php?url"+superLoad_url+"&size="+superLoad_size;

$("#example").attr( "src" , superLoad_result );
```


