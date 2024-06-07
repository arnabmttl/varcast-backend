<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use JWTAuth;
use App\Models\Playlist;
use App\Models\PlaylistMedia;
use Helper;

class PlaylistController extends Controller
{
    //

    public function __construct(Request $request)
    {
        $token = $request->header('x-access-token');
        $request->headers->set('Authorization', $token);
    }

    /**
     * My All Playlists
     * GET
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request) : JsonResponse {
        try {
            if (! $user = JWTAuth::parseToken()->authenticate()) {
				return response()->json([					
					'status' => false,
					'message' => @trans('error.not_found'),
                    'data' => (object)[]
				], 200);
			}
            $userId = $user->_id;
            $take = !empty($request->take)?$request->take:15;
            $page = !empty($request->page)?$request->page:0;
            $skip = ($page * $take);

            $listData = Playlist::where('userId', $userId)->with('media');
            $countData = Playlist::where('userId', $userId)->count();

            $listData = $listData->take($take)->skip($skip)->get();

            return \Response::json([
                'status' => true,
                'message' => "My Playlists",
                'data' => array(
                    'countData' => $countData,
                    'listData' => $listData
                )
            ], 200);


        } catch (\Throwable $e) {
            return response()->json([
				'status' => false,
				'message' => $e->getMessage(),
                'data' => (object)[]
			],403);
        }
    }

    /**
     * Create Playlist
     * POST
     *
     * @return \Illuminate\Http\Response
     */

    public function create(Request $request) : JsonResponse {
        try {
            if (! $user = JWTAuth::parseToken()->authenticate()) {
				return response()->json([					
					'status' => false,
					'message' => @trans('error.not_found'),
                    'data' => (object)[]
				], 200);
			}
            $userId = $user->_id;

            $validator = \Validator::make($request->all(),[
                'name' => 'required',
                'image' => 'file'                
            ]);
            if($validator->fails()){
                foreach($validator->errors()->messages() as $key => $value){
                    return \Response::json([
                        'status' => false,
                        'message' => "validation",
                        'data' =>  $value[0]
                    ], 400);
                }
            }

            $params = $request->except('_token');
            $params['userId'] = $userId;
            /* Image File */
            $fileImage = $request->file('image');
            $file_name_image= time()."_".$fileImage->getClientOriginalName();
            $locationImage="uploads/playlists/";
            $fileImage->move($locationImage,$file_name_image);
            $imagefilename=$locationImage."".$file_name_image;
            $params['image']=$imagefilename;

            $playlist = Playlist::create($params);

            return \Response::json([
                'status' => true,
                'message' => "PodPlaylistscast Created",
                'data' =>  $playlist
            ], 201);


        } catch (\Throwable $e) {
            return response()->json([
				'status' => false,
				'message' => $e->getMessage(),
                'data' => (object)[]
			],403);
        }
    }

    /**
     * Add Media Url
     * POST
     *
     * @return \Illuminate\Http\Response
     */

    public function add_media(Request $request) : JsonResponse {
        try {
            if (! $user = JWTAuth::parseToken()->authenticate()) {
				return response()->json([					
					'status' => false,
					'message' => @trans('error.not_found'),
                    'data' => (object)[]
				], 200);
			}
            
            $validator = \Validator::make($request->all(),[
                'playlistId' => 'required|exists:mongodb.playlists,_id',
                'mediaUrl' => 'required'
            ]);
            if($validator->fails()){
                foreach($validator->errors()->messages() as $key => $value){
                    return \Response::json([
                        'status' => false,
                        'message' => "validation",
                        'data' =>  $value[0]
                    ], 400);
                }
            }
            $userId = $user->_id;
            $params = $request->except('_token');
            $params['userId'] = $userId;

            $checkPlaylist = Playlist::find($params['playlistId']);
            if($checkPlaylist->userId != $userId){
                return \Response::json([
                    'status' => false,
                    'message' => "Unknown playlist",
                    'data' =>  (object)[]
                ], 400);
            }

            $existsSameMedia = PlaylistMedia::where('playlistId', $params['playlistId'])->where('mediaUrl', $params['mediaUrl'])->first();

            if(!empty($existsSameMedia)){
                ## This media already exists
                return \Response::json([
                    'status' => false,
                    'message' => "This media already exists",
                    'data' =>  (object)[]
                ], 400);
            }
            PlaylistMedia::create($params);

            return \Response::json([
                'status' => true,
                'message' => "Media added into your playlist successfully",
                'data' =>  $params
            ], 201);


        } catch (\Throwable $e) {
            return response()->json([
				'status' => false,
				'message' => $e->getMessage(),
                'data' => (object)[]
			],403);
        }
    }
}
