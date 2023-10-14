# Very short description of the package

[![Latest Version on Packagist](https://img.shields.io/packagist/v/irteel/php-smsgw.svg?style=flat-square)](https://packagist.org/packages/irteel/php-smsgw)
[![Total Downloads](https://img.shields.io/packagist/dt/irteel/php-smsgw.svg?style=flat-square)](https://packagist.org/packages/irteel/php-smsgw)
![GitHub Actions](https://github.com/irteel/php-smsgw/actions/workflows/main.yml/badge.svg)

With this application you can easily turn your mobile phone into the SMS Gateway for your applications.
 
You will get Admin Panel and Android application with this package. Admin Panel keeps track of all messages you sent using this API and Android application turns your mobile into SMS Gateway. All the requests that you send will be first stored in Server using Admin Panel on https://smsgw.irteel.com and then it will be handed over to the Android application. The android application sends the SMS according to the request and reports the status of the messages to the Admin Panel.
 
Features
 
Send SMS from your application developed using any programming language.
Use CSV or Excel file containing numbers and messages in first two columns to send bulk messages.
Shows status of messages sent using SMS Gateway in Admin Panel.
Ability to receive messages in Admin Panel and respond to it using a WebHook.
Ability to sign in using multiple Android devices to split messages between them when sending bulk messages.
Ability to create other users to let them use SMS Gateway from their mobile phones.

## Installation

You can install the package via composer:

```bash
composer require irteel/php-smsgw
```

## Usage

```php
PHP Integration
Include following code in your PHP file to start sending messages.
define("SERVER", "https://smsgw.irteel.com");
define("API_KEY", "ffc81eb642f20f1ccc4ed03a32939582ff3bb05d");

define("USE_SPECIFIED", 0);
define("USE_ALL_DEVICES", 1);
define("USE_ALL_SIMS", 2);

/**
 * @param string     $number      The mobile number where you want to send message.
 * @param string     $message     The message you want to send.
 * @param int|string $device      The ID of a device you want to use to send this message.
 * @param int        $schedule    Set it to timestamp when you want to send this message.
 * @param bool       $isMMS       Set it to true if you want to send MMS message instead of SMS.
 * @param string     $attachments Comma separated list of image links you want to attach to the message. Only works for MMS messages.
 * @param bool       $prioritize  Set it to true if you want to prioritize this message.
 *
 * @return array     Returns The array containing information about the message.
 * @throws Exception If there is an error while sending a message.
 */
function sendSingleMessage($number, $message, $device = 0, $schedule = null, $isMMS = false, $attachments = null, $prioritize = false)
{
    $url = SERVER . "/services/send.php";
    $postData = array(
        'number' => $number,
        'message' => $message,
        'schedule' => $schedule,
        'key' => API_KEY,
        'devices' => $device,
        'type' => $isMMS ? "mms" : "sms",
        'attachments' => $attachments,
        'prioritize' => $prioritize ? 1 : 0
    );
    return sendRequest($url, $postData)["messages"][0];
}

/**
 * @param array  $messages        The array containing numbers and messages.
 * @param int    $option          Set this to USE_SPECIFIED if you want to use devices and SIMs specified in devices argument.
 *                                Set this to USE_ALL_DEVICES if you want to use all available devices and their default SIM to send messages.
 *                                Set this to USE_ALL_SIMS if you want to use all available devices and all their SIMs to send messages.
 * @param array  $devices         The array of ID of devices you want to use to send these messages.
 * @param int    $schedule        Set it to timestamp when you want to send these messages.
 * @param bool   $useRandomDevice Set it to true if you want to send messages using only one random device from selected devices.
 *
 * @return array     Returns The array containing messages.
 *                   For example :-
 *                   [
 *                      0 => [
 *                              "ID" => "1",
 *                              "number" => "+11234567890",
 *                              "message" => "This is a test message.",
 *                              "deviceID" => "1",
 *                              "simSlot" => "0",
 *                              "userID" => "1",
 *                              "status" => "Pending",
 *                              "type" => "sms",
 *                              "attachments" => null,
 *                              "sentDate" => "2018-10-20T00:00:00+02:00",
 *                              "deliveredDate" => null
 *                              "groupID" => ")V5LxqyBMEbQrl9*J$5bb4c03e8a07b7.62193871"
 *                           ]
 *                   ]
 * @throws Exception If there is an error while sending messages.
 */
function sendMessages($messages, $option = USE_SPECIFIED, $devices = [], $schedule = null, $useRandomDevice = false)
{
    $url = SERVER . "/services/send.php";
    $postData = [
        'messages' => json_encode($messages),
        'schedule' => $schedule,
        'key' => API_KEY,
        'devices' => json_encode($devices),
        'option' => $option,
        'useRandomDevice' => $useRandomDevice
    ];
    return sendRequest($url, $postData)["messages"];
}

/**
 * @param int    $listID      The ID of the contacts list where you want to send this message.
 * @param string $message     The message you want to send.
 * @param int    $option      Set this to USE_SPECIFIED if you want to use devices and SIMs specified in devices argument.
 *                            Set this to USE_ALL_DEVICES if you want to use all available devices and their default SIM to send messages.
 *                            Set this to USE_ALL_SIMS if you want to use all available devices and all their SIMs to send messages.
 * @param array  $devices     The array of ID of devices you want to use to send the message.
 * @param int    $schedule    Set it to timestamp when you want to send this message.
 * @param bool   $isMMS       Set it to true if you want to send MMS message instead of SMS.
 * @param string $attachments Comma separated list of image links you want to attach to the message. Only works for MMS messages.
 *
 * @return array     Returns The array containing messages.
 * @throws Exception If there is an error while sending messages.
 */
function sendMessageToContactsList($listID, $message, $option = USE_SPECIFIED, $devices = [], $schedule = null, $isMMS = false, $attachments = null)
{
    $url = SERVER . "/services/send.php";
    $postData = [
        'listID' => $listID,
        'message' => $message,
        'schedule' => $schedule,
        'key' => API_KEY,
        'devices' => json_encode($devices),
        'option' => $option,
        'type' => $isMMS ? "mms" : "sms",
        'attachments' => $attachments
    ];
    return sendRequest($url, $postData)["messages"];
}

/**
 * @param int $id The ID of a message you want to retrieve.
 *
 * @return array     The array containing a message.
 * @throws Exception If there is an error while getting a message.
 */
function getMessageByID($id)
{
    $url = SERVER . "/services/read-messages.php";
    $postData = [
        'key' => API_KEY,
        'id' => $id
    ];
    return sendRequest($url, $postData)["messages"][0];
}

/**
 * @param string $groupID The group ID of messages you want to retrieve.
 *
 * @return array     The array containing messages.
 * @throws Exception If there is an error while getting messages.
 */
function getMessagesByGroupID($groupID)
{
    $url = SERVER . "/services/read-messages.php";
    $postData = [
        'key' => API_KEY,
        'groupId' => $groupID
    ];
    return sendRequest($url, $postData)["messages"];
}

/**
 * @param string $status         The status of messages you want to retrieve.
 * @param int    $deviceID       The deviceID of the device which messages you want to retrieve.
 * @param int    $simSlot        Sim slot of the device which messages you want to retrieve. Similar to array index. 1st slot is 0 and 2nd is 1.
 * @param int    $startTimestamp Search for messages sent or received after this time.
 * @param int    $endTimestamp   Search for messages sent or received before this time.
 *
 * @return array     The array containing messages.
 * @throws Exception If there is an error while getting messages.
 */
function getMessagesByStatus($status, $deviceID = null, $simSlot = null, $startTimestamp = null, $endTimestamp = null)
{
    $url = SERVER . "/services/read-messages.php";
    $postData = [
        'key' => API_KEY,
        'status' => $status,
        'deviceID' => $deviceID,
        'simSlot' => $simSlot,
        'startTimestamp' => $startTimestamp,
        'endTimestamp' => $endTimestamp
    ];
    return sendRequest($url, $postData)["messages"];
}

/**
 * @param int $id The ID of a message you want to resend.
 *
 * @return array     The array containing a message.
 * @throws Exception If there is an error while resending a message.
 */
function resendMessageByID($id)
{
    $url = SERVER . "/services/resend.php";
    $postData = [
        'key' => API_KEY,
        'id' => $id
    ];
    return sendRequest($url, $postData)["messages"][0];
}

/**
 * @param string $groupID The group ID of messages you want to resend.
 * @param string $status  The status of messages you want to resend.
 *
 * @return array     The array containing messages.
 * @throws Exception If there is an error while resending messages.
 */
function resendMessagesByGroupID($groupID, $status = null)
{
    $url = SERVER . "/services/resend.php";
    $postData = [
        'key' => API_KEY,
        'groupId' => $groupID,
        'status' => $status
    ];
    return sendRequest($url, $postData)["messages"];
}

/**
 * @param string $status         The status of messages you want to resend.
 * @param int    $deviceID       The deviceID of the device which messages you want to resend.
 * @param int    $simSlot        Sim slot of the device which messages you want to resend. Similar to array index. 1st slot is 0 and 2nd is 1.
 * @param int    $startTimestamp Resend messages sent or received after this time.
 * @param int    $endTimestamp   Resend messages sent or received before this time.
 *
 * @return array     The array containing messages.
 * @throws Exception If there is an error while resending messages.
 */
function resendMessagesByStatus($status, $deviceID = null, $simSlot = null, $startTimestamp = null, $endTimestamp = null)
{
    $url = SERVER . "/services/resend.php";
    $postData = [
        'key' => API_KEY,
        'status' => $status,
        'deviceID' => $deviceID,
        'simSlot' => $simSlot,
        'startTimestamp' => $startTimestamp,
        'endTimestamp' => $endTimestamp
    ];
    return sendRequest($url, $postData)["messages"];
}

/**
 * @param int    $listID      The ID of the contacts list where you want to add this contact.
 * @param string $number      The mobile number of the contact.
 * @param string $name        The name of the contact.
 * @param bool   $resubscribe Set it to true if you want to resubscribe this contact if it already exists.
 *
 * @return array     The array containing a newly added contact.
 * @throws Exception If there is an error while adding a new contact.
 */
function addContact($listID, $number, $name = null, $resubscribe = false)
{
    $url = SERVER . "/services/manage-contacts.php";
    $postData = [
        'key' => API_KEY,
        'listID' => $listID,
        'number' => $number,
        'name' => $name,
        'resubscribe' => $resubscribe
    ];
    return sendRequest($url, $postData)["contact"];
}

/**
 * @param int    $listID The ID of the contacts list from which you want to unsubscribe this contact.
 * @param string $number The mobile number of the contact.
 *
 * @return array     The array containing the unsubscribed contact.
 * @throws Exception If there is an error while setting subscription to false.
 */
function unsubscribeContact($listID, $number)
{
    $url = SERVER . "/services/manage-contacts.php";
    $postData = [
        'key' => API_KEY,
        'listID' => $listID,
        'number' => $number,
        'unsubscribe' => true
    ];
    return sendRequest($url, $postData)["contact"];
}

/**
 * @return string    The amount of message credits left.
 * @throws Exception If there is an error while getting message credits.
 */
function getBalance()
{
    $url = SERVER . "/services/send.php";
    $postData = [
        'key' => API_KEY
    ];
    $credits = sendRequest($url, $postData)["credits"];
    return is_null($credits) ? "Unlimited" : $credits;
}

/**
 * @param string $request   USSD request you want to execute. e.g. *150#
 * @param int $device       The ID of a device you want to use to send this message.
 * @param int|null $simSlot Sim you want to use for this USSD request. Similar to array index. 1st slot is 0 and 2nd is 1.
 *
 * @return array     The array containing details about USSD request that was sent.
 * @throws Exception If there is an error while sending a USSD request.
 */
function sendUssdRequest($request, $device, $simSlot = null)
{
    $url = SERVER . "/services/send-ussd-request.php";
    $postData = [
        'key' => API_KEY,
        'request' => $request,
        'device' => $device,
        'sim' => $simSlot
    ];
    return sendRequest($url, $postData)["request"];
}

/**
 * @param int $id The ID of a USSD request you want to retrieve.
 *
 * @return array     The array containing details about USSD request you requested.
 * @throws Exception If there is an error while getting a USSD request.
 */
function getUssdRequestByID($id)
{
    $url = SERVER . "/services/read-ussd-requests.php";
    $postData = [
        'key' => API_KEY,
        'id' => $id
    ];
    return sendRequest($url, $postData)["requests"][0];
}

/**
 * @param string   $request        The request text you want to look for.
 * @param int      $deviceID       The deviceID of the device which USSD requests you want to retrieve.
 * @param int      $simSlot        Sim slot of the device which USSD requests you want to retrieve. Similar to array index. 1st slot is 0 and 2nd is 1.
 * @param int|null $startTimestamp Search for USSD requests sent after this time.
 * @param int|null $endTimestamp   Search for USSD requests sent before this time.
 *
 * @return array     The array containing USSD requests.
 * @throws Exception If there is an error while getting USSD requests.
 */
function getUssdRequests($request, $deviceID = null, $simSlot = null, $startTimestamp = null, $endTimestamp = null)
{
    $url = SERVER . "/services/read-ussd-requests.php";
    $postData = [
        'key' => API_KEY,
        'request' => $request,
        'deviceID' => $deviceID,
        'simSlot' => $simSlot,
        'startTimestamp' => $startTimestamp,
        'endTimestamp' => $endTimestamp
    ];
    return sendRequest($url, $postData)["requests"];
}

/**
 * @return array     The array containing all enabled devices
 * @throws Exception If there is an error while getting devices.
 */
function getDevices() {
    $url = SERVER . "/services/get-devices.php";
    $postData = [
        'key' => API_KEY
    ];
    return sendRequest($url, $postData)["devices"];
}

function sendRequest($url, $postData)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if (curl_errno($ch)) {
        throw new Exception(curl_error($ch));
    }
    curl_close($ch);
    if ($httpCode == 200) {
        $json = json_decode($response, true);
        if ($json == false) {
            if (empty($response)) {
                throw new Exception("Missing data in request. Please provide all the required information to send messages.");
            } else {
                throw new Exception($response);
            }
        } else {
            if ($json["success"]) {
                return $json["data"];
            } else {
                throw new Exception($json["error"]["message"]);
            }
        }
    } else {
        throw new Exception("HTTP Error Code : {$httpCode}");
    }
}
Send Single Message
try {
    // Send a message using the primary device.
    $msg = sendSingleMessage("+11234567890", "This is a test of single message.");

    // Send a message using the Device ID 1.
    $msg = sendSingleMessage("+11234567890", "This is a test of single message.", 1);
    
    // Send a prioritize message using Device ID 1 for purpose of sending OTP, message reply etc…
    $msg = sendSingleMessage("+11234567890", "This is a test of single message.", 1, null, false, null, true);
    
    // Send a MMS message with image using the Device ID 1.
    $attachments = "https://example.com/images/footer-logo.png,https://example.com/downloads/sms-gateway/images/section/create-chat-bot.png";
    $msg = sendSingleMessage("+11234567890", "This is a test of single message.", 1, null, true, $attachments);
	
    // Send a message using the SIM in slot 1 of Device ID 1 (Represented as "1|0").
    // SIM slot is an index so the index of the first SIM is 0 and the index of the second SIM is 1.
    // In this example, 1 represents Device ID and 0 represents SIM slot index.
    $msg = sendSingleMessage("+11234567890", "This is a test of single message.", "1|0");

    // Send scheduled message using the primary device.
    $msg = sendSingleMessage("+11234567890", "This is a test of schedule feature.", null, strtotime("+2 minutes"));
    print_r($msg);

    echo "Successfully sent a message.";
} catch (Exception $e) {
    echo $e->getMessage();
}
Send Bulk Messages
$messages = array();

for ($i = 1; $i <= 12; $i++) {
    array_push($messages,
        [
            "number" => "+11234567890",
            "message" => "This is a test #{$i} of PHP version. Testing bulk message functionality."
        ]);
}

try {
    // Send messages using the primary device.
    sendMessages($messages);

    // Send messages using default SIM of all available devices. Messages will be split between all devices.
    sendMessages($messages, USE_ALL_DEVICES);
	
    // Send messages using all SIMs of all available devices. Messages will be split between all SIMs.
    sendMessages($messages, USE_ALL_SIMS);

    // Send messages using only specified devices. Messages will be split between devices or SIMs you specified.
    // If you send 12 messages using this code then 4 messages will be sent by Device ID 1, other 4 by SIM in slot 1 of 
    // Device ID 2 (Represendted as "2|0") and remaining 4 by SIM in slot 2 of Device ID 2 (Represendted as "2|1").
    sendMessages($messages, USE_SPECIFIED, [1, "2|0", "2|1"]);
    
    // Send messages on schedule using the primary device.
    sendMessages($messages, null, null, strtotime("+2 minutes"));
    
    // Send a message to contacts in contacts list with ID of 1.
    sendMessageToContactsList(1, "Test", USE_SPECIFIED, 1);
    
    // Send a message on schedule to contacts in contacts list with ID of 1.
    sendMessageToContactsList(1, "Test", null, null, strtotime("+2 minutes"));
    
    // Array of image links to attach to MMS message;
    $attachments = [
        "https://example.com/images/footer-logo.png",
        "https://example.com/downloads/sms-gateway/images/section/create-chat-bot.png"
    ];
    $attachments = implode(',', $attachments);
    
    $mmsMessages = [];
    for ($i = 1; $i <= 12; $i++) {
        array_push($mmsMessages,
            [
                "number" => "+11234567890",
                "message" => "This is a test #{$i} of PHP version. Testing bulk MMS message functionality.",
                "type" => "mms",
                "attachments" => $attachments
            ]);
    }
    // Send MMS messages using all SIMs of all available devices. Messages will be split between all SIMs.
    $msgs = sendMessages($mmsMessages, USE_ALL_SIMS);
    
    print_r($msgs);

    echo "Successfully sent bulk messages.";
} catch (Exception $e) {
    echo $e->getMessage();
}
Get remaining message credits
try {
    $credits = getBalance();
    echo "Message Credits Remaining: {$credits}";
} catch (Exception $e) {
    echo $e->getMessage();
}
Get messages and their current status
try {
    // Get a message using the ID.
    $msg = getMessageByID(1);
    print_r($msg);

    // Get messages using the Group ID.
    $msgs = getMessagesByGroupID(')V5LxqyBMEbQrl9*J$5bb4c03e8a07b7.62193871');
    print_r($msgs);
    
    // Get messages received in last 24 hours.
    $msgs = getMessagesByStatus("Received", null, null, time() - 86400);
    
    // Get messages received on SIM 1 of device ID 8 in last 24 hours.
    $msgs = getMessagesByStatus("Received", 8, 0, time() - 86400);
    print_r($msgs);
} catch (Exception $e) {
    echo $e->getMessage();
}
Resend messages
try {
    // Resend a message using the ID.
    $msg = resendMessageByID(1);
    print_r($msg);

    // Get messages using the Group ID and Status.
    $msgs = resendMessagesByGroupID('LV5LxqyBMEbQrl9*J$5bb4c03e8a07b7.62193871', 'Failed');
    print_r($msgs);
    
    // Resend pending messages in last 24 hours.
    $msgs = resendMessagesByStatus("Pending", null, null, time() - 86400);
    
    // Resend pending messages sent using SIM 1 of device ID 8 in last 24 hours.
    $msgs = resendMessagesByStatus("Received", 8, 0, time() - 86400);
    print_r($msgs);
} catch (Exception $e) {
    echo $e->getMessage();
}
Manage Contacts
try {
    // Add a new contact to contacts list 1 or resubscribe the contact if it already exists.
    $contact = addContact(1, "+11234567890", "Test", true);
    print_r($contact);
    
    // Unsubscribe a contact using the mobile number.
    $contact = unsubscribeContact(1, "+11234567890");
    print_r($contact);
} catch (Exception $e) {
    echo $e->getMessage();
}
Send USSD request
try {
    // Send a USSD request using default SIM of Device ID 1.
    $ussdRequest = sendUssdRequest("*150#", 1);
    print_r($ussdRequest);
    
    // Send a USSD request using SIM in slot 1 of Device ID 1.
    $ussdRequest = sendUssdRequest("*150#", 1, 0);
    print_r($ussdRequest);
    
    // Send a USSD request using SIM in slot 2 of Device ID 1.
    $ussdRequest = sendUssdRequest("*150#", 1, 1);
    print_r($ussdRequest);
} catch (Exception $e) {
    echo $e->getMessage();
}
Get USSD requests
try {
    // Get a USSD request using the ID.
    $ussdRequest = getUssdRequestByID(1);
    print_r($ussdRequest);
    
    // Get USSD requests with request text "*150#" sent in last 24 hours.
    $ussdRequests = getUssdRequests("*150#", null, null, time() - 86400);
    print_r($ussdRequests);
} catch (Exception $e) {
    echo $e->getMessage();
}
Get Devices
try {
    // Get all enabled devices for sending messages.
    $devices = getDevices()
    print_r($devices);
} catch (Exception $e) {
    echo $e->getMessage();
}
```

### Testing

```bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email cyrille.bidongo@gmail.com instead of using the issue tracker.

## Credits

-   [Cyrille Bekono](https://github.com/irteel)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
