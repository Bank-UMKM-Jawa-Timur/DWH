<?php

namespace App\Http\Controllers;

use App\Models\MstFileContentDictionary;
use App\Models\MstFileDictionary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Alert;

class CollectionController extends Controller
{
    public function index(Request $request) {
        $param['title'] = 'Collection';
        $param['pageTitle'] = 'Collection';

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
        $receiver = new FileReceiver('file', $request, HandlerFactory::classFromRequest($request));

        if (!$receiver->isUploaded()) {
            // file not uploaded
            $response = [
                'status' => false,
                'message' => 'Tidak bisa mengunggah berkas',
            ];
        }

        $fileReceived = $receiver->receive(); // receive file
        if ($fileReceived->isFinished()) { // file uploading is complete / all chunks are uploaded
            $file = $fileReceived->getFile(); // get file
            $extension = $file->getClientOriginalExtension();
            $onlyFileName = str_replace('.'.$extension, '', $file->getClientOriginalName()); //file name without extenstion
            $time = time();
            $fileName = $onlyFileName . '_' . $time . '.' . $extension; // a unique file name

            $disk = Storage::disk('public');
            $path = $disk->putFileAs("txt-files/$onlyFileName"."_$time/", $file, $fileName);

            // delete chunked file
            unlink($file->getPathname());
            $response = [
                'path' => asset('storage/' . $path),
                'filename' => $fileName
            ];
            return response()->json($response);
        }

        // otherwise return percentage information
        $handler = $fileReceived->handler();
        return [
            'done' => $handler->getPercentageDone(),
            'status' => true
        ];
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
        $param['total_data'] = 0;
        $limit = 1000;
        $param['total_per_page'] = $limit;
        $param['page'] = 1;
        
        if ($request->has('file') && $request->has('result_filename')) {
            $param['file'] = $request->file;
            $param['result_filename'] = $request->result_filename;
            $inp_filename = explode('.', $request->file)[0]; // Only filename
            $result_filename = $request->result_filename; // Filename + time + ext
            $filenametime = str_replace('.txt', '', $result_filename); // Filename + time
            $param['filename'] = $request->file;
            $param['filenametime'] = $filenametime;
            
            $file_txt_path = Storage::path("public/txt-files/$filenametime/$result_filename");
            $content = fopen($file_txt_path,'r');

            $txt_data = [];
            while(!feof($content)){
                $line = fgets($content);
                if (!str_contains($line, "")) {
                    array_push($txt_data, utf8_encode($line));
                }
            }
            fclose($content);

            // Split data txt to files (1.000 data per file)
            $total_data_txt = count($txt_data);

            if ($total_data_txt > $limit) {
                $offset = 0;
                $total_page  = (int) ceil($total_data_txt / $limit);
                for ($i=1; $i <= $total_page; $i++) { 
                    if ($i > 1) {
                        $offset = ($limit * $i - $limit);
                    }
                    $value = array_slice($txt_data, $offset, $limit);
                    
                    Storage::disk('public')->put("json-files/$filenametime/$filenametime"."_$i.json", json_encode($value));
                }
            }
            else {
                $value = $txt_data;
                Storage::disk('public')->put("json-files/$filenametime/$filenametime"."_1.json", json_encode($value));
            }

            if (Storage::fileExists("txt-files/$filenametime"))
                Storage::disk('public')->deleteDirectory("txt-files/$filenametime");
            
            $json_path = "public/json-files/$filenametime".'/'.$filenametime.'_1.json';
            $file_json = json_decode(Storage::get($json_path), true);
            $param['file_json'] = $file_json;
            $dictionary = $this->getDictionary($inp_filename);
            $param['dictionary'] = $dictionary['item'];

            // retrieve from api
            $host = env('COLLECTION_API_HOST');
            if (!$host) {
                Alert::error('Gagal', 'API host belum diatur');
                return back();
            }
            $apiURL = $host . '/extract';

            $filename = '';
            $finalResponse = null;

            try {
                $reqBody = [
                    'file' => $filenametime,
                    'file_json' => $file_json,
                    'total' => $total_data_txt,
                    'page' => 1,
                    'dictionary' => $dictionary['item']
                ];
                $param['fields'] = $dictionary['fields'];
                $response = Http::withHeaders([
                    'Accept' => '*/*',
                    'Content-Type' => 'application/json'
                ])->timeout(360)
                ->withBody(json_encode($reqBody), 'application/json')
                ->post($apiURL);

                $statusCode = $response->status();
                $responseBody = json_decode($response->getBody(), true);
                if ($responseBody) {
                    if ($responseBody['status'] == 'success') {
                        $filename = $responseBody['filename'];
                        $param['filename'] = $filename;
                        if ($filename != '') {
                            $page = $request->has('page') ? $request->page : 1;
                            $apiURL2 = $host . '/json';
                            $res2 = Http::withHeaders([
                                'Accept' => '*/*',
                                'Content-Type' => 'application/json'
                            ])->timeout(360)->get($apiURL2, [
                                'filename' => $filename,
                                'page' => $page
                            ]);
                            
                            $statusCode2 = $res2->status();
                            $responseBody2 = json_decode($res2->getBody(), true);
                            if ($statusCode2 == 200) {
                                if ($responseBody2) {
                                    $finalResponse = $responseBody2;
        
                                    if ($finalResponse) {
                                        if (array_key_exists('total', $finalResponse) && array_key_exists('data', $finalResponse)) {
                                            $param['total_data'] = $finalResponse['total'];
                                            $param['total_all_data'] = $finalResponse['total_all_data'];
                                            $param['total_page'] = $total_page;
                                            $param['result'] = $finalResponse['data'];
                                        }
                                        else {
                                            Alert::error('Gagal', 'Terjadi kesalahan saat mengambil data');
                                        }
                                    }
                                }
                            }
                            else if ($statusCode2 == 404) {
                                Alert::error('Gagal', 'Data tidak ditemukan');
                                return back();
                            }
                            else {
                                Alert::error('Gagal', 'Terjadi kesalahan saat mengambil data');
                                return back();
                            }
                        }
                    }
                }
                else {
                    Alert::error('Gagal', 'Gagal mengunggah berkas');
                    return back();
                }

                return view('pages.collection.result', $param);
            } catch (\Exception $e) {
                Alert::error('Error', $e->getMessage());
                return back();
            } catch (\Illuminate\Http\Client\ConnectionException $e) {
                Alert::error('Error', $e->getMessage());
                return back();
            }
        }
        else {
            Alert::error('Gagal', 'Tidak ada berkas yang dipilih');
            return back();
        }
    }

    public function getPage(Request $request) {
        $param['title'] = 'Collection Result';
        $param['pageTitle'] = 'Collection';
        $param['total_data'] = 0;
        $limit = 1000;
        $param['total_per_page'] = $limit;
        
        if ($request->has('file') && $request->has('result_filename') && $request->has('page')) {
            $page = $request->page;
            $inp_filename = explode('.', $request->file)[0]; // Only filename
            $result_filename = $request->result_filename; // Filename + time + ext
            $filenametime = str_replace('.txt', '', $result_filename); // Filename + time
            $param['file'] = $request->file;
            $param['page'] = $page;
            $param['result_filename'] = $request->result_filename;
            $param['filename'] = $request->file;
            $param['filenametime'] = $filenametime;
            $total_data_txt = $request->total_all_data;
            $total_page = $request->total_page;
            $param['total_all_data'] = $total_data_txt;
            $param['total_page'] = $total_page;
            
            $json_path = "public/json-files/$filenametime".'/'.$filenametime."_$page.json";
            $file_json = json_decode(Storage::get($json_path), true);
            $param['file_json'] = $file_json;
            $dictionary = $this->getDictionary($inp_filename);
            $param['dictionary'] = $dictionary['item'];

            // retrieve from api
            $host = env('COLLECTION_API_HOST');
            if (!$host) {
                Alert::error('Gagal', 'API host belum diatur');
                return back();
            }
            $apiURL = $host . '/extract';

            $filename = '';
            $finalResponse = null;

            try {
                $reqBody = [
                    'file' => $filenametime,
                    'file_json' => $file_json,
                    'total' => $total_data_txt,
                    'page' => $page,
                    'dictionary' => $dictionary['item'],
                ];
                $param['fields'] = $dictionary['fields'];
                $response = Http::withHeaders([
                    'Accept' => '*/*',
                    'Content-Type' => 'application/json'
                ])->timeout(360)
                ->withBody(json_encode($reqBody), 'application/json')
                ->post($apiURL);

                $statusCode = $response->status();
                $responseBody = json_decode($response->getBody(), true);
                if ($responseBody) {
                    if ($responseBody['status'] == 'success') {
                        $filename = $responseBody['filename'];
                        $param['filename'] = $filename;
                        if ($filename != '') {
                            $apiURL2 = $host . '/json';
                            $res2 = Http::withHeaders([
                                'Accept' => '*/*',
                                'Content-Type' => 'application/json'
                            ])->timeout(360)->get($apiURL2, [
                                'filename' => $filename,
                                'page' => $page
                            ]);
                            
                            $statusCode2 = $res2->status();
                            $responseBody2 = json_decode($res2->getBody(), true);
                            if ($statusCode2 == 200) {
                                if ($responseBody2) {
                                    $finalResponse = $responseBody2;
        
                                    if ($finalResponse) {
                                        if (array_key_exists('total', $finalResponse) && array_key_exists('data', $finalResponse)) {
                                            $param['total_data'] = $finalResponse['total'];
                                            $param['total_all_data'] = $finalResponse['total_all_data'];
                                            $param['result'] = $finalResponse['data'];
                                        }
                                        else {
                                            Alert::error('Gagal', 'Terjadi kesalahan saat mengambil data');
                                        }
                                    }
                                }
                            }
                            else if ($statusCode2 == 404) {
                                Alert::error('Gagal', 'Data tidak ditemukan');
                                return back();
                            }
                            else {
                                Alert::error('Gagal', 'Terjadi kesalahan saat mengambil data');
                                return back();
                            }
                        }
                    }
                }
                else {
                    Alert::error('Gagal', 'Gagal mengunggah berkas');
                    return back();
                }

                return view('pages.collection.result', $param);
            } catch (\Exception $e) {
                Alert::error('Error', $e->getMessage());
                return back();
            } catch (\Illuminate\Http\Client\ConnectionException $e) {
                Alert::error('Error', $e->getMessage());
                return back();
            }
        }
        else {
            Alert::error('Gagal', 'Tidak ada berkas yang dipilih');
            return back();
        }
    }
}
