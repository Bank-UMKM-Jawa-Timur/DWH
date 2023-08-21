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
        

        return view('pages.collection.index', $param);
    }

    public function upload(Request $request) {
        if ($request->hasFile('file')) {
            $filename = $request->file('file')->getClientOriginalName();
            $save_dir = 'collection/'.$filename;
            // Storage::disk('public')->put($save_dir, fopen($request->file('file'), 'r+'));
            // Storage::disk('ftp')->put($filename, fopen($request->file('file'), 'w'));
            // connect to FTP server
            // $ftp_server = env('FTP_HOST');
            // $ftp_conn = ftp_connect($ftp_server) or die("Could not connect to $ftp_server");

            // if ($ftp_conn) {
            //     //login to FTP server
            //     $ftp_username = env('FTP_USERNAME');
            //     $ftp_password = env('FTP_PASSWORD');
            //     $login = ftp_login($ftp_conn, $ftp_username, $ftp_password);
            //     ftp_pasv($ftp_conn, true) or die("Cannot switch to passive mode"); 
            //     ftp_set_option($ftp_conn, FTP_USEPASVADDRESS, false);

            //     if ($login) {
            //         // upload file
            //         if (ftp_put($ftp_conn, "serverfile.txt", $request->file('file'), FTP_ASCII))
            //         {
            //             return "Successfully uploaded $filename.";
            //         }
            //         else
            //         {
            //             return "Error uploading $filename.";
            //         }
            //     }
            //     else {
            //         return 'login failed';
            //     }
            // }
            // else {
            //     return 'failed to connect';
            // }
            try {
                $host = env('COLLECTION_API_HOST');
                $apiURL = $host . '/upload';
                
                $response = Http::timeout(360)->post($apiURL, [
                    'file' => $request->file('file')
                ]);
                $response = Http::attach('file', fopen($request->file('file'), 'r+'), $filename)->post($apiURL);
                
                $statusCode = $response->status();
                $responseBody = json_decode($response->getBody(), true);

                return $responseBody;
            } catch (\Exception $e) {
                return 'err'.$e->getMessage();
            } catch (\Illuminate\Http\Client\ConnectionException $e) {
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
                // $finalResponse['data'] = array_slice($finalResponse['data'], 0, 100);
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
