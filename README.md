[![Build Status](https://travis-ci.org/websupporter/WPNonce.svg?branch=master)](https://travis-ci.org/websupporter/WPNonce)
WPNonce
===================

A objectoriented approach to use the WordPress Nonce System
----------

## Get started

### The configuration:
```
$config = new WPNonceConfig( 
	'action', 
	'request_name' 
);
```
WordPress Nonces need an action name, something like "create-post", to identify the current action, which is supposed to be secured by a nonce. The first parameter of the configuration defines this name. Nonces are passed through Forms or URLs, so usually via `$_POST` or `$_GET`. The second parameter identifies the key. In this case, we would expect the nonce to be in `$_REQUEST['request_name']`.


### Ways to create a nonce
If you just want to get the nonce, you can use `WPNonceCreate`:
```
$create = new WPNonceCreate( $config );
$nonce = $create->create();
```

But WordPress gives you two shortcuts to implement nonces quickly into URLs or into forms. To add a nonce to an URL, you can use

```
$create = new WPNonceCreateURL( $config );
$url = $create->create_url( 'http://example.com/' );
```
This would add the nonce to the given URL. With the `$config` from above, we would get this URL:
`http://example.com/?request_name=$nonce`

With `WPNonceCreateField` you can create a hidden form field to use it in your forms:
```
$create = new WPNonceCreateField( $config );
$field = $create->create_field();
```

`$field` would now contain the HTML string for an hidden field. With the `$config` from above, this would look like
`<input type="hidden" name="request_name" value="$nonce">`

To replicate the `wp_nonce_field()` functionality, you can add two parameters: `(bool) $referer` and `(bool) $echo`. Both are set to `false` by default. 

If you set `$referer` `true` an additional field will be appended, containing the URL of the current page as a value. 

If you set `$echo` `true`, you will immediatly `echo` the field, before `create_url()` returns it.

### Validate a nonce

To validate a nonce, you can use `WPNonceVerify`:
```
$create = new WPNonceVerify( $config );
$is_valid = $create->verify( $nonce );
```

This will check if the nonce, you have passed is valid for the current configuration. Since you most likely want to validate the nonce, which is passed via `$_POST` or `$_GET` with the request name given in `$config`, you can just use `$create->verify()` since `WPNonceVerify` has already set this nonce (if given).
