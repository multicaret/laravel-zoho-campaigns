<?php

namespace Liliom\Zoho\Campaigns;

use GuzzleHttp\Client;

class CampaignsClient implements CampaignsClientContract
{
    const API_URL = 'https://campaigns.zoho.com/api/';

    private $client;

    /**
     * @var array
     */
    private $headers;

    /**
     * @var string
     */
    private $authToken;

    /**
     * @var array
     */
    private $additionalParams;
    /**
     * @var bool
     */
    public $requestAsync = false;

    /**
     * @var Callable
     */
    private $requestCallback;
    /**
     * @var String
     */
    private $responseFormat = 'json';


    public function __construct(string $authToken)
    {
        $this->authToken = $authToken;
        $this->client = new Client();
        $this->headers = ['headers' => []];
        $this->additionalParams = [
            'resfmt' => strtoupper($this->responseFormat),
            'scope' => 'CampaignsAPI',
        ];
    }

    /**
     *  Fetch the mailing lists.
     *
     * @param string   $sort      [asc/desc]
     * @param int|null $fromIndex values are in number.
     * @param int|null $range     values are in number.
     *
     * @return object
     */
    public function getMailingLists(string $sort = 'desc', int $fromIndex = null, int $range = null): object
    {
        return $this->postRequest('getmailinglists', [
            'sort' => $sort,
            'fromindex' => $fromIndex,
            'range' => $range,
        ]);
    }


    /**
     * Advanced details include all data like subscriber details, stats and complete details about campaigns sent to this mailing list. Using this API, you can get the list's advanced details.
     *
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
    ): object {
        return $this->postRequest('getlistadvanceddetails', [
            'listkey' => $listKey,
            'filtertype' => $filterType,
            'fromindex' => $fromIndex,
            'range' => $range,
        ]);
    }

    /**
     *  Fetch the mailing lists.
     *
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
    ): object {
        return $this->postRequest('getlistsubscribers', [
            'listkey' => $listKey,
            'status' => $status,
            'sort' => $sort,
            'fromindex' => $fromIndex,
            'range' => $range,
        ]);
    }


    /**
     * Fetch subscriber fields to get profile information of the subscribers. Using this API, you can get the list of all subscriber fields.
     *
     * @param string $type xml (or) json
     *
     * @return array
     */
    public function getAllSubscriberFields(string $type = 'json'): array
    {
        return $this->postRequest('contact/allfields', [
            'type' => $type,
        ])->response->fieldnames->fieldname;
    }

    /**
     * Get the segment details by using our API.
     *
     * @param string $listKey List Key to send a subscription mail to contacts.
     * @param int    $cvId    You will get cvid from @method getMailingLists().
     *
     * @return object
     */
    public function getSegmentDetails(string $listKey, int $cvId): object
    {
        return $this->postRequest('getsegmentdetails', [
            'listkey' => $listKey,
            'cvid' => $cvId,
        ])->segments_details;
    }

    /**
     * Using this API, you can get the segment subscribers along with their relevant details like first name, last name, time when they were added, company and their phone number.
     *
     * @param int $cvId You will get cvid from @method getMailingLists().
     *
     * @return object
     */
    public function getSegmentContacts(int $cvId): object
    {
        return $this->postRequest('getsegmentcontacts', [
            'cvid' => $cvId,
        ]);
    }

    /**
     * Updating a list can be to rename a list or to associate a signup form to the list. Using this API, you can make changes in mailing lists.
     *
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
    ): object {
        return $this->postRequest('updatelistdetails', [
            'listkey' => $listKey,
            'newlistname' => $newListName,
            'signupform' => $signUpForm,
        ]);
    }

    /**
     * Using this API, you can delete a mailing list. All you need to provide is the list key and choose whether to delete all list subscribers from the organization or remove them only from the list.
     *
     * @param string $listKey        List Key to send a subscription mail to subscribers.
     * @param string $deleteContacts [on/off]
     *
     * @return object
     */
    public function deleteMailingList(string $listKey, string $deleteContacts = 'off'): object
    {
        return $this->postRequest('deletemailinglist', [
            'listkey' => $listKey,
            'deletecontacts' => $deleteContacts,
        ]);
    }

    /**
     * You don’t have to research on how effective a list has been or how much reach does a particular list measure. Find out using our API Authentication Token to find out the number of subscribers in a list.
     *
     * @param string $listKey List Key to send a subscription mail to subscribers
     * @param string $status  [active|unsub|bounce|spam]
     *
     * @return object
     */
    public function listSubscribersCount(string $listKey, string $status = 'active'): object
    {
        return $this->postRequest('listsubscriberscount', [
            'listkey' => $listKey,
            'status' => $status,
        ]);
    }

    /**
     * Users can subscribe to a list without using the signup form or by getting added by another user.
     * They can subscribe using this API and the user added will be notified.
     * Remember that, using this API, you can update your subscriber details
     * for the existing leads in a private list only.
     *
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
    ): object {
        return $this->postRequest($this->responseFormat . DIRECTORY_SEPARATOR . 'listsubscribe', [
            'listkey' => $listKey,
            'contactinfo' => $contactInfo,
            'sources' => $sources,
        ]);
    }

    /**
     * Disinterested leads in your list? Never mind, all you need to do is unsubscribe them.
     * Using this API, you can unsubscribe leads and they will be notified.
     *
     * @param string $listKey     List Key to send a subscription mail to subscribers
     * @param string $contactInfo Provide email id to be moved to Do-Not-Mail registry.
     *
     * @return object
     */
    public function listUnsubscribe(
        string $listKey,
        string $contactInfo
    ): object {
        return $this->postRequest($this->responseFormat . DIRECTORY_SEPARATOR . 'listunsubscribe', [
            'listkey' => $listKey,
            'contactinfo' => $contactInfo,
        ]);
    }

    /**
     * You can move the contact to do-not-mail registry if you do not want to send emails further to that user
     * or if they unsubscribe from the organization.
     * Using this API, you can move/add contacts into "Do-Not-Mail" registry.
     *
     * @param string $contactInfo Provide email id to be moved to Do-Not-Mail registry.
     *
     * @return object
     */
    public function contactDoNotMail(
        string $contactInfo
    ): object {
        return $this->postRequest($this->responseFormat . DIRECTORY_SEPARATOR . 'contactdonotmail', [
            'contactinfo' => $contactInfo,
        ]);
    }

    /**
     * Did you know that you can also add leads not only to a new list but also existing lists using API?
     * Well, using this API you can add leads in existing list.
     *
     * @param string $listKey  List Key to send a subscription mail to subscribers
     * @param string $emailIds Provide maximum of ten (10) EMAILID's comma (,) separately.
     *
     * @return object
     */
    public function addListSubscribersInBulk(
        string $listKey,
        string $emailIds
    ): object {
        return $this->postRequest('addlistsubscribersinbulk', [
            'listkey' => $listKey,
            'emailids' => $emailIds,
        ]);
    }


    /**
     * Using this API, you can add new list and subscribers in the list without having to do this manually in the product UI.
     *
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
    ): object {
        return $this->postRequest('addlistandcontacts', [
            'emailids' => $emailIds,
            'listname' => $listName,
            'signupform' => $signUpForm,
            'mode' => $mode,
            'listdescription' => $listDescription,
        ]);
    }


    /**
     * Custom fields can be used to feed any type of information, set character limit and use them to save specific data. Using this API, you can create custom fields to store unique information about subscribers.
     *
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
    ): object {
        return $this->postRequest('custom/add', [
            'fieldname' => $fieldName,
            'fieldtype' => $fieldType,
            'fieldlength' => $fieldLength,
            'type' => $type,
        ]);
    }


    /**
     * Send your campaigns by addressing your subscribers by their first name, last name or anything else you find appropriate. Using this API, youcan create merge tags.
     *
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
    ): object {
        return $this->postRequest('merge/create', [
            'tagtype' => $tagType,
            'fieldname' => $fieldName,
            'fieldtype' => $fieldType,
            'mailvalue' => $mailValue,
            'socialvalue' => $socialValue,
            'type' => $type,
        ]);
    }


    private function usesFormUrlEncoded()
    {
        $this->headers['headers']['Content-Type'] = 'application/x-www-form-urlencoded';

        return $this;
    }


    /**
     * @param string $endpoint
     * @param array  $parameters
     *
     * @param string $method
     *
     * @return mixed
     */
    public function dispatchRequest(string $endpoint, $parameters, string $method = 'post')
    {
        $this->usesFormUrlEncoded();
        if ( ! array_key_exists('authtoken', $parameters) || empty($parameters['authtoken'])) {
            $parameters['authtoken'] = $this->authToken;
        }
        if (count($this->additionalParams)) {
            $parameters = array_merge($parameters, $this->additionalParams);
        }

        $this->headers['body'] = http_build_query($parameters);
        $method = strtolower($method);

        return json_decode(
            $this->{$method}($endpoint)
                 ->getBody()
                 ->getContents()
        );
    }

    /**
     * @param string $endpoint
     * @param array  $parameters
     *
     * @return mixed
     */
    public function postRequest(string $endpoint, $parameters = [])
    {
        return $this->dispatchRequest($endpoint, $parameters, 'post');
    }

    /**
     * @param string $endpoint
     * @param array  $parameters
     *
     * @return mixed
     */
    public function getRequest(string $endpoint, $parameters = [])
    {
        return $this->dispatchRequest($endpoint, $parameters, 'get');
    }


    /**
     * Turn on, turn off async requests
     *
     * @param bool $on
     *
     * @return $this
     */
    public function async($on = true)
    {
        $this->requestAsync = $on;

        return $this;
    }

    /**
     * Callback to execute after Campaigns returns the response
     *
     * @param Callable $requestCallback
     *
     * @return $this
     */
    public function callback(Callable $requestCallback)
    {
        $this->requestCallback = $requestCallback;

        return $this;
    }

    public function testCredentials(): string
    {
        return "API Auth Token: " . $this->authToken;
    }

    public function addParams($params = [])
    {
        $this->additionalParams = $params;

        return $this;
    }

    public function setParam($key, $value)
    {
        $this->additionalParams[$key] = $value;

        return $this;
    }


    private function post($endPoint)
    {
        if ($this->requestAsync === true) {
            $promise = $this->client->postAsync($this->getEndpoint($endPoint), $this->headers);

            return (is_callable($this->requestCallback) ? $promise->then($this->requestCallback) : $promise);
        }

        return $this->client->post($this->getEndpoint($endPoint), $this->headers);
    }

    private function get($endPoint)
    {
        if ($this->requestAsync === true) {
            $promise = $this->client->getAsync($this->getEndpoint($endPoint), $this->headers);

            return (is_callable($this->requestCallback) ? $promise->then($this->requestCallback) : $promise);
        }

        return $this->client->get($this->getEndpoint($endPoint), $this->headers);
    }

    /**
     * @param $endPoint
     *
     * @return string
     */
    private function getEndpoint($endPoint): string
    {
        return self::API_URL . $endPoint;
    }
}
