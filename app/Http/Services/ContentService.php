<?php


namespace App\Http\Services;

use App\Constants\ContentTypes;
use App\Http\Repository\ContentRepository;
use App\Http\Services\ContentServiceHelper\ContentTypeContext;
use App\Http\Services\ContentServiceHelper\AudioTypeService;
use App\Http\Services\ContentServiceHelper\ImageTypeService;
use App\Http\Services\ContentServiceHelper\VideoTypeService;
use App\Http\Services\ContentServiceHelper\YoutubeTypeService;
use App\Http\Services\ContentServiceHelper\ExternalLinkTypeService;
use App\Http\Services\ContentServiceHelper\AdvancedTextTypeService;
use App\Http\Services\ContentServiceHelper\NormalTextTypeService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;

use File;

class ContentService
{
    /**
     * @var IMAGE_PATH
     */
    const IMAGE_PATH = 'content/image_preview';
    /**
     * @var ContentRepository
     */
    private $contentRepository;
    /**
     * @var AudioTypeService
     */
    private $audioTypeService;
    /**
     * @var ImageTypeService
     */
    private $imageTypeService;
    /**
     * @var VideoTypeService
     */
    private $videoTypeService;

    /**
     * __construct
     *
     * @param  ContentRepository $contentRepository
     * @param  AudioTypeService $audioTypeService
     * @param  ImageTypeService $imageTypeService
     * @param  VideoTypeService $videoTypeService
     * @param  YoutubeTypeService $YoutubeTypeService
     * @param  UploaderService $uploaderService
     * @return void
     */
    public function __construct(
        ContentRepository $contentRepository,
        AudioTypeService $audioTypeService,
        ImageTypeService $imageTypeService,
        VideoTypeService $videoTypeService,
        YoutubeTypeService $YoutubeTypeService,
        UploaderService $uploaderService
    ) {
        $this->contentRepository      = $contentRepository;
        $this->audioTypeService       = $audioTypeService;
        $this->imageTypeService       = $imageTypeService;
        $this->videoTypeService       = $videoTypeService;
        $this->YoutubeTypeService     = $YoutubeTypeService;
        $this->uploaderService        = $uploaderService;
    }
    /**
     * handle function that make update for content
     * @param array $request
     * @param int|null $contentId
     * @return Content
     */
    public function handle($request, $contentId = null)
    {
        $content = $this->contentRepository;

        if ($contentId) {
            $content = $this->contentRepository->find($contentId);
        }

        $contentTypeClass = sprintf("App\\Http\\Services\\ContentServiceHelper\\" . ContentTypes::getLabel($request['content_type_id']) . 'TypeService');

        $contentTypeStrategy  = new ContentTypeContext(new $contentTypeClass);

        if (isset($request['path'])) {
            // dd($request['content_type_id']);
            $request = array_merge($request, [
                'path' => $contentTypeStrategy->handleType($request['path'])
            ]);
        }

        if (isset($request['image_preview'])) {
            $request = array_merge($request, [
                'image_preview' => $this->handleFile($request['image_preview'])
            ]);
        }
        if (isset($request['image_preview'])) {
            if ($request['content_type_id'] == ContentTypes::VIDEO) {
                $request = array_merge($request, [
                    'image_preview' => $this->handleVideoImagePreview($request)
                ]);
            }
            if ($request['content_type_id'] == ContentTypes::YOUTUBVIDEO) {
                $request = array_merge($request, [
                    'image_preview' => $this->handleYoutubeImagePreview($request)
                ]);
            }
        }
        $content = $this->transTitle($content, $request);
        $path = '';
        if ($request['content_type_id'] == ContentTypes::ADVANCED_TEXT || request()->get('content_type_id') == ContentTypes::NORMAL_TEXT) {
            $content = $this->transPath($content, $request);
            $path = 'path';
        }

        $content->fill(Arr::except($request, ['title', $path]));
        $content->save();

        return $content;
    }


    /**
     * Method transTitle
     *
     * @param content $category [explicite description]
     *
     * @return content
     */
    public function transTitle($content, $request)
    {
        foreach ($request['title'] as $key => $value) {
            $content->setTranslation('title', $key, $value);
        }
        return $content;
    }


    /**
     * Method transPath
     *
     * @param $content $content [explicite description]
     *
     * @return content
     */
    public function transPath($content, $request)
    {
        foreach ($request['path'] as $key => $value) {
            $content->setTranslation('path', $key, $value);
        }
        return $content;
    }

    /**
     * handle image file that return file path
     * @param File $file
     * @return string
     */
    public function handleFile(UploadedFile $file)
    {
        return $this->uploaderService->upload($file, self::IMAGE_PATH);
    }
    /**
     * handle Video Image Preview that return file path
     * @param array $request
     * @return string
     */
    public function handleVideoImagePreview($request)
    {
        $ourPath = $this->uploaderService->creatOurFolderPath(self::IMAGE_PATH);
        $image_path =  'uploads/' . self::IMAGE_PATH . '/' . $ourPath['date_path'] . time() . '.png';
        $command = 'ffmpeg -ss 00:00:02 -i ' . base_path($request['path']) . ' -vframes 1 -q:v 2 ' . base_path($image_path) . '';
        $command = str_replace('\\', '/', $command);
        exec($command);
        return $image_path;
    }
    /**
     * handle Youtube Image Preview that return file path
     * @param array $request
     * @return string
     */
    public function handleYoutubeImagePreview($request)
    {
        $ourPath = $this->uploaderService->creatOurFolderPath(self::IMAGE_PATH);
        $image_path =  'uploads/' . self::IMAGE_PATH . '/' . $ourPath['date_path'] . $request['title'] . '.png';
        $link = explode('embed/', $request['path']);
        if (isset($link[1])) {
            $youtube_id = explode('?', $link[1]);
            file_put_contents(base_path($image_path), file_get_contents('http://img.youtube.com/vi/' . $youtube_id[0] . '/maxresdefault.jpg'));
        } else {
            $link = explode('?v=', $request['path']);
            file_put_contents(base_path($image_path), file_get_contents('http://img.youtube.com/vi/' . $link[1] . '/maxresdefault.jpg'));
        }
        return $image_path;
    }
}
