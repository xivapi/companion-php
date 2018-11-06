
# Companion PHP  
  
A library for interacting with the FFXIV Companion App API  
  
To learn more about the FFXIV Companion App, read the [Research Doc](https://github.com/viion/ffxiv-datamining/blob/master/docs/CompanionAppApi.md).  
  
## Testing  
  
- `php bin/cli`  
  
## How to use  
  
Include the library using composer: https://packagist.org/packages/xivapi/companion-php  
  
```bash  
composer require xivapi/companion-php
```  
  
The library supports multiple tokens so you can login to multiple characters, initialize the CompanionApi with a profile name:  
  
```php  
use Companion\CompanionApi;  
$api = new CompanionApi('character_profile_name');  
```  
  
Once initialized, to interact with the Companion API you need to authenticate your token. There are two ways to authenticate with the companion app using the library:  
  
- **Manual** - Ask the library to generate a login URL for you. This Url is an official Square-Enix Secure URL (the exact one the Companion App presents to you) which you can login to and authenticate your token.  
- **Auto-Login** - Provide the library your Square-Enix Username+Password and it will automatically login to the app for you and authenticate your token.  
  
### Manual

```php
use Companion\CompanionApi; 

// do something
$api      = new CompanionApi('character_profile_name');
$loginUrl = $api->Account()->getLoginUrl();
$token    = $api->Profile()->getToken();

echo "Login to the url: {$loginUrl} and then the token {$token} will work";
```

The variable `$loginUrl` will be the url to the secure Square-Enix login form. When you open it; if you have never logged in before then a login form will appear. Login with your Square-Enix account and it will set cookies, authenticate the token and return a status code of either `200` or `202`.

Once you have a `200` or `202` status, your `$token` will be valid for 24 hours.

The token is automatically saved to your config under the profile name given, eg in the example above we used `character_profile_name`. 

This means all future queries (once you have selected a character) can be setup like so:

```php
use Companion\CompanionApi; 

$api = new CompanionApi('character_profile_name');
$earthShardPrices = $api->market()->getItemMarketListings(5);
```

You can validate logins are successful by calling `postAuth()`

```php
if ($api->Login()->postAuth()->status === 200) {
    echo "You're all loged in just fine!";
} else {
    echo "Could not confirm login authentication...";
}
```

### Automatic

To smooth out automation, you can have the library log into the companion app for you, like so:

```php
use Companion\CompanionApi; 

$api = new CompanionApi('character_profile_name');

// login to our SE Account
$api->Account()->login('Username', 'Password');
```

Once this logs in, your token will be saved under the profile name supplied, in this case `character_profile_name` and your token will last 24 hours.

### Selecting a character

Now that you're all logged in and have a valid token, we need select a character. A token can only have 1 character logged in at a time, which is why the initial `CompanionApi('some_name')` can be used to login to multiple characters.

**It is important to remember:** You cannot be in-game whilst the companion app is logged in and attempts to access Market Place information. It is recommended to create a second character to use with the library.

List your characters like so:

```php
foreach ($api->login()->getCharacters()->accounts[0]->characters as $character) {
    echo "- {$character->cid} :: {$character->name} ({$character->world})\n";
}
```

Characters have a unique `cid`, this is not the one you see on Lodestone or anything you'll be familiar with, once you find the character you want, take its `cid` over to the `loginCharacter` function, for example:

```php
$api->login()->loginCharacter('character_id');
```

Once this is called, you will be logged in and good to go!

You can confirm your logged in character with code like this:

```php
$character = $api->login()->getCharacter()->character;
echo "- Logged in as: {$character->name} ({$character->world}) \n";
```

Now you can access all the various different API methods, view the API Docs below for details on these:

## API

You can find all the PHP logic for these API endpoints in: the `Companion/Api` folder.

### Account

### AddressBook

### ChatRoom

### Item

### Login

### Market

### Payments

### Reports

### Schedule

---

Library by: Josh Freeman (Vekien on Discord: (XIVAPI) https://discord.gg/MFFVHWC) - License is MIT, do whatever you want with it :)
