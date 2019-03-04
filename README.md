# Companion PHP  
  
A library for interacting with the FFXIV Companion App API

To learn more about the FFXIV Companion App, read the [Research Doc](https://github.com/viion/ffxiv-datamining/blob/master/docs/CompanionAppApi.md).

If you are just interested in market data and not access Companion App features, have a look at XIVAPI: https://xivapi.com/docs/Market

## Terminology:

- **Companion**: The Official FFXIV Companioon Mobile App.
- **Sight**: The API that SE uses within the app to talk to the Game Servers.

## How to use

Include the library using composer: https://packagist.org/packages/xivapi/companion-php

```bash
composer require xivapi/companion-php
```

### Token Management via `CompanionConfig`

> *Note*: It is highly recommended you manage your own tokens in a private database rather than using the in-built token management. You can always access the current token the library is using via: `$api->Token()->get()`.

The library can keep a record of your Sight Access Token information to be able to query against the Sight API. This is optional and by default it will not save any tokens. You can either inform the library to save a token to a file or you can request your token at anytime and save it however you prefer (db, redis, s3, whatever).

If you want the library to record the Sight Access Token information, tell it where to save:

```php
use Companion\Config\CompanionConfig;
CompanionConfig::setTokenFilename('/path/to/save/token');
```

It is extremely important that: The path is not publicly accessible on the internet AND is not within the library itself (composer update will delete it).

## Token API

This is the libraries token interface:

```
use Companion\CompanionApi;

$api = new CompanionApi();
$api->Token()->set($token); // Set a token to use, can override any existing token
$api->Token()->get(); // Returns a SightToken that is currently being used
$api->Token()->save(); // Save the current token
$api->Token()->load(); // Load all saved token
$api->Token()->hasExpired(timestamp); // true||false if a token has expired based on a timestamp
```

### Initializing the API

When you initialize the API you can provide a `token` parameter. This will either be:

- a `stdClass` object of an existing token that you have saved.
- a `string` that can be used as a name for your token. If you are using the libraries built-in Token Management then it will try find an existing token with this name, otherwise a new token object will be created.

```php
use Companion\CompanionApi;

// new or existing saved token
$api = new CompanionApi('name_of_your_token');

// passing a saved token
$savedToken = json_decode('{ ... }');
$api = new CompanionApi($savedToken);
```

If you do not provide one, it is expected you use `$api->Token()->set($token);` at some point to assign one.

### Getting a token!

Once you've decided how you're going to manage your Sight tokens and you're ready to go, we need to decide which method of accessing the API we're going to use. The 2 are:

- **Manual** - Ask the library to generate a login URL for you. This Url is an official Square-Enix Secure URL (the exact one the Companion App presents to you) which you can login to and authenticate your token. This works with 2 factor authentication.
- **Auto-Login** - Provide the library your Square-Enix Username+Password and it will automatically login to the app for you and authenticate your token. This does not work with 2 factor authentication at this time.



### Login - Manual

```php
use Companion\CompanionApi;
$api      = new CompanionApi('my_token_name');
$loginUrl = $api->Account()->getLoginUrl();
$token    = $api->Token()->get()->token;
echo "Token: {$token} - Login URL: \n\n{$loginUrl}\n\n";
```

The variable `$loginUrl` will be the url to the secure Square-Enix login form. When you open it; if you have never logged in before then a login form will appear. Login with your Square-Enix account and it will set cookies, authenticate the token and return a status code of either `200` or `202`.

Once you have a `200` or `202` status, your `$token` will be valid for 24 hours.

> *Note:* At this point you must follow the **Character Select Process** flow steps listed further down the documentation before you can query any of the Sight Endpoints.

If you are using the built in Token Manager; you can now access this token via the name `profile_name`. This means all future queries (once you have selected a character) can be setup like so:

```php
use Companion\CompanionApi;
$api = new CompanionApi('my_token_name');
$earthShardPrices = $api->market()->getItemMarketListings(5);
```


### Login Automatic

To smooth out automation, you can have the library log into the companion app for you, like so:

```php
use Companion\CompanionApi;

$api = new CompanionApi('my_token_name');

// login to our SE Account
$api->Account()->login('Username', 'Password');
```

Once logged in the API will be ready to go. You can get your token at anytime using:

```php
$api->Token()->get();
```

> *Note:* At this point you must follow the **Character Select Process** flow steps listed further down the documentation before you can query any of the Sight Endpoints.

If you are using the built in Token Manager; you can now access this token via the name `profile_name`. This means all future queries (once you have selected a character) can be setup like so:

```php
use Companion\CompanionApi;
$api = new CompanionApi('my_token_name');
$earthShardPrices = $api->market()->getItemMarketListings(5);
```

## Character Select Process

In order to use the Sight API you need a valid character with an active Subscription (it cannot be in the free trial period). A character does not need to be at any "stage" in the game, a brand new-never logged into character will have full market board access the moment you enter a name and click "create".


### Selecting a character

To access the market we first need to select our character and this requires knowing the unique "Character ID". We can find this by listing out our characters:

```php
foreach ($api->Login()->getCharacters()->accounts[0]->characters as $character) {
    echo "- {$character->cid} :: {$character->name} ({$character->world})\n";
}
```

Characters have a unique Character ID property known as: `cid`. This ID is not the one you see on Lodestone or anything you'll be familiar with, once you find the character you want take its `cid` over to the `loginCharacter` function, for example:

```php
$api->login()->loginCharacter('character_id');
```

This will confirm with Sight that you want to use this character, you can confirm yourself by performing the following optional code:

```php
// Get current logged in character#echo "Confirming logged in character ...\n";
$character = $api->login()->getCharacter()->character;
echo "- Logged in as: {$character->name} ({$character->world}) \n";
```

Now that we've told it what character to use, we have to confirm its status. This is a new addition in Patch 4.4 and I believe it will be used for when World Visit system is in place. For now it is a requirement and returns the current world and your home world:

```php
$api->login()->getCharacterStatus();
```

Once you have done this, you can now access the market board as well as any other Sight endpoint! You can find all API calls below.

##  Async Requests

The library supports async requests for all main data actions (not Account or Login actions). This allows you to query multiple endpoints at the same time.

When you enable async it will by pass the "garauntee response" feature. The Sight API doesn't provide data in the first request, instead it will take your request and queue it up, you then need to perform the same request again to get the response. Usually a Sight request will fulfill within 3 seconds, so you could 20 concurrent requests, wait 3 seconds and then perform the same 20 concurrent requests again using the same Request ID to get your response.

You can view an example of Async usage in `bin/cli_async`

## API

### Account

### AddressBook

### ChatRoom

### Item

### Login

### Market

#### `$api->market()->getItemMarketListings(int $itemId)`
Get the current Market Prices for an item. The item id must be the in-game dat value which is an integer, not the one on Lodestone.

#### `$api->market()->getTransactionHistory(int $itemId)`
Get the current Market History for an item. The item id must be the in-game dat value which is an integer, not the one on Lodestone. The maximum returned history is currently 20, this cannot be changed.

#### `$api->market()->getMarketListingsByCategory(int $categoryId)`
Get the current stock listings for a market category

### Payments

### Reports

### Schedule

## Testing  
  
- `php bin/cli` - Have a look into thisclass for examples

---

**Library by:** Josh Freeman (Vekien on Discord: (XIVAPI) https://discord.gg/MFFVHWC)

License is MIT, do whatever you want with it :)
