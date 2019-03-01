# Media

## Usage

To get started, add `\Optimus\Media\MediaServiceProvider` to your Laravel application's `config/app.php` providers array. 
You will then be able to start calling the below API routes from your application.

## API Routes

The API follows standard RESTful conventions, with responses being returned in JSON. 
Appropriate HTTP status codes are provided, and these should be used to check the outcome of an operation.

### GET `/admin/media-folders`

List all available folders that media items can be added to.

**Parameters**

None

**Response**

```json
[
    {
        "id": 356,
        "folder_id": 12, 
        "name": "My Image", 
        "file_name": "my_image.jpg",
        "disk": "local",
        "mime_type": "image/jpeg", 
        "size": 102400,
        "created_at": "2017-12-24 09:36:23",
        "updated_at": "2017-12-25 10:15:12"
    },
    {
        "id": 513,
        "folder_id": 4, 
        "name": "Landscape", 
        "file_name": "landscape.png",
        "disk": "local",
        "mime_type": "image/png", 
        "size": 219462,
        "created_at": "2019-02-19 09:36:23",
        "updated_at": "2019-02-19 09:36:23"
    }
]
```

### GET `/admin/media`

List available media items.

**Parameters**

None

**Response**

Todo

### POST `/admin/media`

Create and store a new media item.

**Parameters**

| Parameter | Required? | Type  | Description    |
|-----------|-----------|-------|----------------|
| file      |    ✓      | file  | The file that is being uploaded |
| folder_id | ✗         | int   | The ID of the folder in which to store the media. If not provided, the media will be stored in the root folder. |




Todo

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
