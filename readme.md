# OAuth 1.0 / 2.0 component

Authorize users with your application using multiple OAuth 1/2 providers.

Based on [oauth1](https://github.com/chrisnharvey/oauth1) and [oauth2](https://github.com/chrisnharvey/oauth2).

## Supported Providers

- Dropbox
- Flickr
- LinkedIn
- Tumblr
- Twitter
- UbuntuOne
- Vimeo
- Appnet
- Facebook
- Foursquare
- GitHub
- Google
- Instagram
- Mailchimp
- Mailru
- PayPal
- Soundcloud
- Vkontakte
- Windows Live
- Yandex
- YouTube
- Odnoklassniki

## Usage Example

In this example we will authenticate the user using Twitter.

```php
qwe
```

### Calling OAuth 1 / 2 APIs using Guzzle

You can also use this package to make calls to your respective APIs
using Guzzle.

```php
$client = new Mindy\SocialAuth\OAuthClient('http://api.twitter.com/1.1');
$client->setUserTokens($provider->getUserTokens());
echo $client->get('statuses/mentions_timeline.json')->send();
```

This example should show your Twitter mentions from the API along with the headers

```php
$client = new \OAuth2\Client('https://graph.facebook.com');
$client->setUserTokens($oauth->getUserTokens());

echo $client->get('me')->send();
```

This example should show your Facebook profile from the API along with the headers

### TODO

- OpenID
