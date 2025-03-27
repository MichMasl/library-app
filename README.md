# library-app

### POST /signup
#### Register new user
body:
- login: string - required
- password: string - required
- password_confirmation: string - required
```json
{
  "login": "test",
  "password": "test",
  "password_confirmation": "test"
}
```
Response: 200 OK
```json
{
  "status": "ok",
  "api_key": "xxxXXXxxxXXXxxxXXX"
}
```
---
### POST /signin
#### Authorize user
body:
- login: string - required
- password: string - required
```json
{
  "login": "test",
  "password": "test"
}
```
Response: 200 OK
```json
{
  "status": "ok",
  "api_key": "xxxXXXxxxXXXxxxXXX"
}
```
---
## All subsequent requests require "X-API-KEY" header. Value you can get from POST /signin request
### GET /users
#### Get list of all registered users
Response: 200 OK
```json
[
  {
    "user_id": 1,
    "login": "test1"
  },
  {
    "user_id": 2,
    "login": "test2"
  }
]
```
---
### POST /grand-access
#### Grand permission to another user to browse your library
Body:
- userId: int - required
```json
{
  "userId": 1
}
```
Response: 200 OK
```json
{
    "status": "ok",
    "message": "access granted"
}
```
---
### POST /book
#### Add book to your library
Body: json or form-data

json:
- name: string - required
- text: string - required

form-data:
- name - required
- file.txt - required
```json
{
  "name": "New book",
  "text": "Text of the book"
}
```
Response: 200 OK
```json
{
    "status": "ok",
    "book_id": 1
}
```
---
### GET /books
#### Get your library
Parameters:
- id - user id, optional
#### If "id" parameter is set then method will return library of specified user. Only if specified user grand you permission to browse his library
Response: 200 OK
```json
[
  {
    "book_id": 1,
    "name": "Book1"
  },
  {
    "book_id": 2,
    "name": "Book2"
  }
]
```
---
### GET /book
#### Get specified book from your library
Parameters:
- id - required

Response: 200 OK
```json
{
    "name": "Book1",
    "text": "Text of the book"
}
```
---
### PUT /book
#### Change specified book data
Body:
- bookId: int - required
- bookName: string - required
- text: string - required
```json
{
    "bookId": 1,
    "bookName": "Another name of the book",
    "text": "Another text of the book"
}
```
Response: 200 ok
```json
{
    "status": "ok",
    "message": "book successfully updated"
}
```
### DELETE /book
#### Delete specified book from your library
Parameters:
- id - required

Response: 200 ok
```json
{
    "status": "ok",
    "message": "book successfully deleted"
}
```
### GET /search-book
#### Search book at third party service
Parameters:
- bookName - required

Response: 200 OK
```json
[
  {
    "id": "xxXXxxXX",
    "title": "Book1",
    "description": "Book1 description"
  },
  {
    "id": "XXxxXXxx",
    "title": "Book2",
    "description": "Book2 description"
  }
]
```
### POST /save-searched-book
#### Save book found at third party service in your library
Body:
- uuid: string - required
```json
{
  "uuid": "xxxXXXxxx"
}
```
Response: 200 OK
```json
{
  "status": "ok",
  "book_id": 1
}
```