# Companion PHP  
  
A library for interacting with the FFXIV Companion App API

To learn more about the FFXIV Companion App, read the [Research Doc](https://github.com/viion/ffxiv-datamining/blob/master/docs/CompanionAppApi.md).

## If you cannot use PHP

XIVAPI provides endpoints that can give you a companion token with your own account and even let you query the market board. It provides both automated login or providing the SE Official Login. This is useful if you want to use your own account but only know frontend dev (eg: JavaScript), or you're building an Electron app.

If you'd like to try this, please message **Vekien#3458** on discord for endpoint information.

## Terminology:

- **Companion**: The Official FFXIV Companioon Mobile App.
- **Sight**: The API that SE uses within the app to talk to the Game Servers.

## Library Documentation

- [Ban Disclaimer](https://github.com/xivapi/companion-php/wiki/Ban-Disclaimer) - Please read this.
- [Getting Started](https://github.com/xivapi/companion-php/wiki/Getting-Started)
  - `composer require xivapi/companion-php`
- [Tokens](https://github.com/xivapi/companion-php/wiki/Tokens)
  - Information on how tokens work, and understanding the `SightToken` object.
  - [Tokens: Manual Login](https://github.com/xivapi/companion-php/wiki/Tokens:-Manual)
  - [Tokens: Automatic Login](https://github.com/xivapi/companion-php/wiki/Tokens:-Automatic)
  

-----

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

Now that we've told it what character to use, we have to confirm its worlds status. This is a new addition in Patch 4.4 and 
I believe it will be used for when World Visit system is in place. For now it is a requirement and returns the current world and your home world:

```php
$api->login()->getCharacterWorlds();
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
