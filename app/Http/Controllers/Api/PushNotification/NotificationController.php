<?php

namespace App\Http\Controllers\Api\PushNotification;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DateTime;
use onesignal\client\api\DefaultApi;
use onesignal\client\Configuration;
use onesignal\client\model\GetNotificationRequestBody;
use onesignal\client\model\Notification;
use onesignal\client\model\StringMap;
use onesignal\client\model\Player;
use onesignal\client\model\UpdatePlayerTagsRequestBody;
use onesignal\client\model\ExportPlayersRequestBody;
use onesignal\client\model\Segment;
use onesignal\client\model\FilterExpressions;
use PHPUnit\Framework\TestCase;
use GuzzleHttp;
use App\Events\testWebsocket;

class NotificationController extends Controller
{
	private $apiInstance;
	public function __construct(Request $request)
	{
		// $config = Configuration::getDefaultConfiguration()
		//     ->setAppKeyToken(env('ONESIGNAL_APP_KEY_TOKEN'))
		//     ->setUserKeyToken(env('ONESIGNAL_USER_KEY_TOKEN'));

		// $apiInstance = new DefaultApi(
		//     new GuzzleHttp\Client(),
		//     $config
		// );
		// $this->apiInstance = $apiInstance;
	}
	// public function sentPushMessage(Request $request){
	// 	try {
	// 		$notification = $this->createNotification(@$request->message);
	// 		$result = $this->apiInstance->createNotification($notification);
	// 		dd($result);
	// 	}
	// 	catch(\Exception $e) {
	// 		return response()->json([
	// 			"code"=> 403,
	// 			'status' => 'token_expire',
	// 			'message' => $e->getMessage(),
	// 		],403);
	// 	}
	// }
 //    private function createNotification($enContent): Notification {
	//     $content = new StringMap();
	//     $content->setEn($enContent);

	//     $notification = new Notification();
	//     $notification->setAppId(env('ONESIGNAL_APP_ID'));
	//     $notification->setContents($content);
	//     $notification->setIncludedSegments(['Subscribed Users']);

	//     return $notification;
	// }

	// public function sendSSE(){
	// 	$notification = 'Hello World';
	// 	header('Content-Type: text/event-stream');
	// 	header('Cache-Control: no-cache');
	// 	header('Connection: keep-alive');

	// 	if($notification){
	// 		$eventData = [
	// 			'message' => $notification,
	// 		];

	// 		echo "data:" . json_encode(@$eventData) . "\n\n";
	// 	}
	// 	else{
	// 		echo "\n\n";
	// 	}
	// 	ob_flush();
	// 	flush();
	// }
	public function test(){
		event(new testWebsocket);
		return true;
	}
}
