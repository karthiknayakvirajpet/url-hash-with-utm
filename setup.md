## About Application

Laravel version: 10.1
Php version required: 8.1 or above


## DB details:

Sample DB dump is added in the project: url_hash_with_utm.sql


## API details:

1. 
Endpoint: /api/hash-url
Purpose: To generate URL Hash
Method: POST
Request Body: JSON
{
    "url": "https://test.com",
    "utm": {
        "source": "news_letter",
        "medium": "email",
        "campaign": "sale",
        "term": "book",
        "content": "books"
    }
}

Response structure:
{
    "status": 200,
    "message": "Success.",
    "data": {
        "url_hash": "0c3ade3d7658c0343aea9d782b2d738e24ea11e4aa1c0866d4ce89ed778d6714",
        "token": "eyJpdiI6IjI1Q1ZOVnh1ekkvRzFkSUYySVZIYlE9PSIsInZhbHVlIjoibTNtNlVLSFZWdkZiY1p5NnNqVTJmV3E4QUlocjdYMG5pTGlybUJHcDkxL0lRN3FIQlIxOEdzM21HUUFYWVlWT29KOWpiZnJGNkNrM0hueTlTanV4TmRTSGZVMTVzcFZMSG1nbnRtUjJaYzg9IiwibWFjIjoiYTEzMGUyOTI1YjBmMzk3YTBjOGMwNmIyNzYyZTFlOTMzNjQ3OTBjMzc3Y2ZmYTBlMjBkY2YyOWVhNWFmYjM3MCIsInRhZyI6IiJ9"
    },
    "error": []
}


2.
Endpoint: /api/{url-hash}
Purpose: To count URL clicks
Method: GET
Request Body: JSON
{
    "token": "eyJpdiI6InZQWDJ4RUtUOE5GWWdmRVJLaUtnWGc9PSIsInZhbHVlIjoieGRWMUJoemd6ZXhPNUQrcWlaTlRmNEx0TVVjNURqOW5UeU5ZbVlSamlhNDBoTjV3OE9nRW13azVqWlNZZ21velk5OHNMdFdsOEUyWVZ5ZmxNVHlvMkZCZnJJVFVnNTB3cGdGK0s5N3JhZms9IiwibWFjIjoiNWQ5M2EzNWEwODljMGVhM2RiZGE0MDA3NTA0OGZlMzhmMzVhMDk2YmFlYmNkNzk2ZmE4ZDBkOTQyOWIxY2JiOSIsInRhZyI6IiJ9"
}

Response structure:
A.
{
    "status": 200,
    "message": "Success.",
    "data": [],
    "error": []
}

B.
{
    "status": 400,
    "message": "Failed.",
    "data": [],
    "error": {
        "message": "The URL has been used already."
    }
}