<?php

namespace App\Http\Controllers;

use App\Models\MstFileContentDictionary;
use App\Models\MstFileDictionary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class CollectionController extends Controller
{
    public function index(Request $request) {
        $param['title'] = 'Collection';
        $param['pageTitle'] = 'Collection';
        $param['total_data'] = 0;
        $param['total_per_page'] = 1000;
        
        if ($request->has('filename')) {
            // retrieve from api
            $host = env('COLLECTION_API_HOST');
            $apiURL = $host . '/';

            $filename = '';
            $finalResponse = null;

            try {
                $dictionary = $this->getDictionary($request->filename);
                $param['fields'] = $dictionary['fields'];
                $response = Http::timeout(360)->post($apiURL, [
                    'file' => $request->filename.'.txt',
                    'dictionary' => $dictionary['item']
                ]);

                $statusCode = $response->status();
                $responseBody = json_decode($response->getBody(), true);
                if ($responseBody) {
                    if ($responseBody['status'] == 'success') {
                        $filename = $responseBody['filename'];
                        $param['filename'] = $filename;
                    }
                }

                if ($filename != '') {
                    $apiURL = $host . '/json';
                    $res2 = Http::timeout(360)->get($apiURL, [
                        'filename' => $filename
                    ]);

                    $statusCode2 = $res2->status();
                    $responseBody2 = json_decode($res2->getBody(), true);
                    if ($statusCode2 == 200) {
                        if ($responseBody2) {
                            $finalResponse = $responseBody2;
                        }
                    }
                }

                if ($finalResponse) {
                    $param['total_data'] = $finalResponse['total'];
                    $offset = 0;
                    if ($request->has('page')) {
                        $page = $request->page;
                        if ($page > 1) {
                            $offset = ($param['total_per_page'] * $page - $param['total_per_page']) + 1;
                        }
                    }

                    $finalResponse['data'] = array_slice($finalResponse['data'], $offset, $param['total_per_page']);
                    $param['result'] = $finalResponse;
                }

                return view('pages.collection.index', $param);
            } catch (\Exception $e) {
                return $e->getMessage();
            } catch (\Illuminate\Http\Client\ConnectionException $e) {
                return $e->getMessage();
            }
        }

        return view('pages.collection.index', $param);
    }

    public function setupFTP()
	{
		return [
				'ftp' => [
					'driver' => 'ftp',
					'host'     => env('FTP_HOST'),
                    'username' => env('FTP_USERNAME'),
                    'password' => env('FTP_PASSWORD'),
                    'port'     => (int)env('FTP_PORT'),
					'root' => env('FTP_ROOT')
				],
		];
	}

    public function upload(Request $request) {
        $start = date('H:i:s');
        if ($request->hasFile('file')) {
            $filename = $request->file('file')->getClientOriginalName();
            $save_dir = 'collection/'.$filename;
            try {
                Storage::disk('ftp')->put($filename, fopen($request->file('file'), 'r+'));
                $end = date('H:i:s');
                
                return back()->withStatus('Berhasil upload file');
            } catch (\Exception $e) { // If I looked correctly it is RuntimeException so you can be more explicit
                return $e->getMessage();
            }
            return back();
        }
    }

    private function getDictionary($file) {
        $fileDictionary = MstFileDictionary::select('id',
                                                    'filename',
                                                    'description'
                                                    )
                                                    ->where('filename', $file)
                                                    ->first();
        if ($fileDictionary) {
            $fieldDictionary = MstFileContentDictionary::where('file_dictionary_id', $fileDictionary->id)
                                                        ->orderBy('id')
                                                        ->pluck('field');
            $itemDictionary = MstFileContentDictionary::select('id',
                                                                'field',
                                                                'from',
                                                                'to',
                                                                'length',
                                                                'description'
                                                                )
                                                                ->where('file_dictionary_id', $fileDictionary->id)
                                                                ->orderBy('id')
                                                                ->get();
        }

        $data = [
            'id' => $fileDictionary->id,
            'filename' => $fileDictionary->filename,
            'description' => $fileDictionary->description,
            'fields' => $fieldDictionary,
            'total_item' => count($itemDictionary),
            'item' => $itemDictionary,
        ];

        return $data;
    }

    public function store(Request $request) {
        $param['title'] = 'Collection Result';
        $param['pageTitle'] = 'Collection';
        // retrieve from api
        $host = env('COLLECTION_API_HOST');
        $apiURL = $host . '/';

        $filename = '';
        $finalResponse = null;

        try {
            $dictionary = $this->getDictionary($request->filename);
            $param['fields'] = $dictionary['fields'];
            $response = Http::timeout(360)->post($apiURL, [
                'file' => $request->filename.'.txt',
                'dictionary' => $dictionary['item']
            ]);

            $statusCode = $response->status();
            $responseBody = json_decode($response->getBody(), true);
            if ($responseBody) {
                if ($responseBody['status'] == 'success') {
                    $filename = $responseBody['filename'];
                    $param['filename'] = $filename;
                }
            }

            if ($filename != '') {
                $apiURL = $host . '/json';
                $res2 = Http::timeout(360)->get($apiURL, [
                    'filename' => $filename
                ]);

                $statusCode2 = $res2->status();
                $responseBody2 = json_decode($res2->getBody(), true);
                if ($statusCode2 == 200) {
                    if ($responseBody2) {
                        $finalResponse = $responseBody2;
                    }
                }
            }

            if ($finalResponse) {
                $finalResponse['data'] = array_slice($finalResponse['data'], 0, 1000);
                $param['result'] = $finalResponse;
            }

            return view('pages.collection.result', $param);
        } catch (\Exception $e) {
            return $e->getMessage();
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            return $e->getMessage();
        }
    }
}
