<p align="center">
<img src="https://cdn.liliomlab.com/packages/assets/img/zoho-campaigns.png">
</p>


# Laravel Zoho Campaigns 1.0
 
Laravel package to help you use the Zoho Campaigns API v1.0 to create, send, and track email campaigns that help you build a strong customer base. From beautiful email templates to an easy-to-use editor, and automation tools to real-time analytics, Zoho Campaigns has it all.


<p align="center">
<a href="https://packagist.org/packages/liliom/laravel-zoho-campaigns"><img src="https://poser.pugx.org/liliom/laravel-zoho-campaigns/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/liliom/laravel-zoho-campaigns"><img src="https://poser.pugx.org/liliom/laravel-zoho-campaigns/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/liliom/laravel-zoho-campaigns"><img src="https://poser.pugx.org/liliom/laravel-zoho-campaigns/license.svg" alt="License"></a>
</p>

---
## Installation

First, install the package through Composer.

```sh
$ composer require liliom/laravel-zoho-campaigns
```

#### Laravel 5.5 and up

You don't have to do anything else, this package uses the Package Auto-Discovery feature, and should be available as soon as you install it via Composer.

#### Laravel 5.4 and down

Then include the service provider inside `config/app.php`.

```php
'providers' => [
    ...
    Liliom\Zoho\Campaigns\CampaignsServiceProvider::class,
    ...
];
```
And add the alias as well

```php
'aliases' => [
    ...
    'Campaigns' => Liliom\Zoho\Campaigns\CampaignsFacade::class,
    ...
],
```

## Configurations
Hit to your Zoho campaigns [Settings](https://campaigns.zoho.com/campaigns/home.do#settings/api) page to generate a new API Key. To load it, add this to your `config/services.php` file:
```
'zoho' => [
    'campaigns' => [
        'auth_token' => env('ZOHO_CAMPAIGNS_AUTH_TOKEN', ''),
    ],
]
```

Then add Zoho Campaigns API auth token to your `.env` file

```
ZOHO_CAMPAIGNS_AUTH_TOKEN={YOUR_AUTH_TOKEN}
```

---
## Usage

#### 1. List Management:  

Fetch the mailing lists.
`\Campaigns::getMailingLists();`
```php
    /**
     * @param string   $sort      [asc/desc]
     * @param int|null $fromIndex values are in number.
     * @param int|null $range     values are in number.
     *
     * @return object
     */
    public function getMailingLists(
        string $sort = 'desc',
        int $fromIndex = null,
        int $range = null
    ): object;
```


 
Advanced details include all data like subscriber details, stats and complete details about campaigns sent to this mailing list. Using this API, you can get the list's advanced details. 
`\Campaigns::getListAdvancedDetails();`
```php
    /**
     * @param string   $listKey    List Key to send a subscription mail to contacts.
     * @param string   $filterType sentcampaigns,scheduledcampaigns,recentcampaigns.
     * @param int|null $fromIndex  values are in number.
     * @param int|null $range      values are in number.
     *
     * @return object
     */
    public function getListAdvancedDetails(
        string $listKey,
        string $filterType = 'sentcampaigns',
        int $fromIndex = null,
        int $range = null
    ): object;
```


 
Fetch the mailing lists. 
`\Campaigns::getListSubscribers();`
```php
    /**
     * @param string   $listKey   List Key to send a subscription mail to contacts.
     * @param string   $status    [active/recent/most recent/unsub/bounce].
     * @param string   $sort      [asc/desc]
     * @param int|null $fromIndex values are in number.
     * @param int|null $range     values are in number.
     *
     * @return object
     */
    public function getListSubscribers(
        string $listKey,
        string $status = 'active',
        string $sort = 'desc',
        int $fromIndex = null,
        int $range = null
    ): object;
```


 
Fetch subscriber fields to get profile information of the subscribers. Using this API, you can get the list of all subscriber fields.
`\Campaigns::getAllSubscriberFields();`
```php
    /**
     * @param string $type xml (or) json
     *
     * @return array
     */
    public function getAllSubscriberFields(
        string $type
    ): array;
```

 
Get the segment details by using our API. 
`\Campaigns::getSegmentDetails();`
```php
    /**
     * @param string $listKey List Key to send a subscription mail to contacts.
     * @param int    $cvId    You will get cvid from @method getMailingLists().
     *
     * @return object
     */
    public function getSegmentDetails(
        string $listKey,
        int $cvId
    ): object;
```


 
Using this API, you can get the segment subscribers along with their relevant details like first name, last name, time when they were added, company and their phone number. 
`\Campaigns::getSegmentContacts();`
```php
    /**
     * @param int $cvId You will get cvid from @method getMailingLists().
     *
     * @return object
     */
    public function getSegmentContacts(
        int $cvId
    ): object;
```


 
Updating a list can be to rename a list or to associate a signup form to the list. Using this API, you can make changes in mailing lists.
`\Campaigns::updateListDetails();`
```php
    /**
     * @param string $listKey     List Key to send a subscription mail to contacts.
     * @param string $newListName Give list name.
     * @param string $signUpForm  [public/private]
     *
     * @return object
     */
    public function updateListDetails(
        string $listKey,
        string $newListName,
        string $signUpForm = 'public'
    ): object;
```


 
Using this API, you can delete a mailing list. All you need to provide is the list key and choose whether to delete all list subscribers from the organization or remove them only from the list. 
`\Campaigns::deleteMailingList();`
```php
    /**
     * @param string $listKey        List Key to send a subscription mail to subscribers.
     * @param string $deleteContacts [on/off]
     *
     * @return object
     */
    public function deleteMailingList(
        string $listKey,
        string $deleteContacts = 'off'
    ): object;
```


 
You don’t have to research on how effective a list has been or how much reach does a particular list measure. Find out using our API Authentication Token to find out the number of subscribers in a list. 
`\Campaigns::listSubscribersCount();`
```php
    /**
     * @param string $listKey List Key to send a subscription mail to subscribers
     * @param string $status  [active|unsub|bounce|spam]
     *
     * @return object
     */
    public function listSubscribersCount(
        string $listKey,
        string $status = 'active'
    ): object;
```


 
Users can subscribe to a list without using the signup form or by getting added by another user. 
They can subscribe using this API and the user added will be notified.
Remember that, using this API, you can update your subscriber details for the existing leads in a private list only. 
`\Campaigns::listSubscribe();`
```php
    /**
     * @param string $listKey     List Key to send a subscription mail to subscribers
     * @param string $contactInfo Provide email id to be moved to Do-Not-Mail registry.
     * @param null   $sources     Subscriber source can be added.
     *
     * @return object
     */
    public function listSubscribe(
        string $listKey,
        string $contactInfo,
        $sources = null
    ): object;
```


 
Disinterested leads in your list? Never mind, all you need to do is unsubscribe them.
Using this API, you can unsubscribe leads and they will be notified. 
`\Campaigns::listUnsubscribe();`
```php
    /**
     * @param string $listKey     List Key to send a subscription mail to subscribers
     * @param string $contactInfo Provide email id to be moved to Do-Not-Mail registry.
     *
     * @return object
     */
    public function listUnsubscribe(
        string $listKey,
        string $contactInfo
    ): object;
```


 
You can move the contact to do-not-mail registry if you do not want to send emails further to that user
or if they unsubscribe from the organization.
Using this API, you can move/add contacts into "Do-Not-Mail" registry.
`\Campaigns::contactDoNotMail();`
```php
    /**
     * @param string $contactInfo Provide email id to be moved to Do-Not-Mail registry.
     *
     * @return object
     */
    public function contactDoNotMail(
        string $contactInfo
    ): object;
```


 
Did you know that you can also add leads not only to a new list but also existing lists using API?
Well, using this API you can add leads in existing list. 
`\Campaigns::addListSubscribersInBulk();`
```php
    /**
     * @param string $listKey  List Key to send a subscription mail to subscribers
     * @param string $emailIds Provide maximum of ten (10) EMAILID's comma (,) separately.
     *
     * @return object
     */
    public function addListSubscribersInBulk(
        string $listKey,
        string $emailIds
    ): object;
```


 
Using this API, you can add new list and subscribers in the list without having to do this manually in the product UI. 
`\Campaigns::addListAndContacts();`
```php
    /**
     * @param string $emailIds        Provide maximum of ten (10) EMAILID's comma (,) separately.
     * @param string $listName        Provide your list name.
     * @param string $signUpForm      [public/private]
     * @param string $mode            'newlist'
     * @param string $listDescription Provide a description for your list.
     *
     * @return object
     */
    public function addListAndContacts(
        string $emailIds,
        string $listName,
        string $signUpForm = 'public',
        string $mode = 'newlist',
        string $listDescription = ''
    ): object;
```


 
Custom fields can be used to feed any type of information, set character limit and use them to save specific data. Using this API, you can create custom fields to store unique information about subscribers. 
`\Campaigns::addCustomField();`
```php
    /**
     * @param string $fieldName   Alphanumeric
     * @param string $fieldType   [Text|Integer|Phone|Date|Picklist|Email|Checkbox|LongInteger|URL|textarea|RadioOption|Multiselect|DateTime|Decimal|Percent]
     * @param int    $fieldLength This lets you to set length of the field. Default value is 20.
     * @param string $type        xml (or) json
     *
     * @return object
     */
    public function addCustomField(
        string $fieldName,
        string $fieldType,
        int $fieldLength = 20,
        string $type = 'json'
    ): object;
```


 
Send your campaigns by addressing your subscribers by their first name, last name or anything else you find appropriate. Using this API, youcan create merge tags.
`\Campaigns::createMergeTag();`
```php
    /**
    * @param string $tagType     Custom
    * @param string $fieldName   Alphanumeric
    * @param string $fieldType   [Text|Integer|Phone|Date|Picklist|Email|Checkbox|LongInteger|URL|textarea|RadioOption|Multiselect|DateTime|Decimal|Percent]
    * @param string $mailValue   Give a default value for the tag to be used in email content.
    * @param string $socialValue Give a default value for the tag to be used in email content of social campaigns.
    * @param string $type        xml (or) json
    *
    * @return object
    */
    public function createMergeTag(
        string $tagType,
        string $fieldName,
        string $fieldType,
        string $mailValue = '',
        string $socialValue = '',
        string $type = 'json'
    ): object;
```



You may make asynchronous calls to Zoho Campaigns API, by prefixing your methods with the `async()` function:
```php
\Campaigns::async(true) // async calls on, default value is true
\Campaigns::async(false) // async calls off


// Later you can append the callback() to be executed when the response returns.
\Campaigns::async()->callback(Callable $requestCallback) 

``` 



For code error meanings please refer to the [Error Codes](https://www.zoho.com/campaigns/help/developers/error-codes.html).


For more details about the parameters please refer to the [Api Documentation](https://www.zoho.com/campaigns/help/developers/) for more info, or read the [source code](https://github.com/liliomlab/laravel-zoho-campaigns/blob/master/src/CampaignsClient.php).



#### 2. Campaign Management:
This is a work in progress, and unfortunately it's not implement it for now, please be patient :see_no_evil:


#### 3. Extra Methods:
```php
// To test credentials and make sure the Auth Token is configured correctly. 
\Campaigns::testCredentials();
```

### Contributing
See the [CONTRIBUTING](CONTRIBUTING.md) guide.

### Changelog
Please see [CHANGELOG](CHANGELOG.md) for more information about what has changed recently.
