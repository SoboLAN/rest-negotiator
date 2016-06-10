# Purpose

This library is useful when you want to **version your Symfony-based REST API** by relying on the **Accept** (for output) and **Content-Type** (for input) HTTP headers.

The library does all this by building on top of the powerful **Symfony Serializer** component.

### Same data in different format 

For example: let's say in your application you ask for information about a user and you also specify the required **Accept** header:

```
GET http://myapi.myapp.com/user/123
Accept: application/myapp;version=1;format=json
```

You get something like this:

```
{
  "user": {
    "id": 123,
    "name": "sobolan",
    "email": "someemail@example.com",
    "age": 99
  }
}
```

However, let's say your mobile app only knows how to read/interpret XML content. Therefore, all you have to do is change the value of the **format** parameter:

```
GET http://myapi.myapp.com/user/123
Accept: application/myapp;version=1;format=xml
```

Now you get something like this:

```
<response>
    <user>
        <id>123</id>
        <name>sobolan</name>
        <email>someemail@example.com</email>
        <age>99</age>
    </user>
</response>
```

Same data, different format ;) . And, as you will see, **it will require ZERO extra lines of code** in your application.

### Supporting different response structures at the same time

Let's say at some point you don't want this route to return the user-ID anymore, since it's a value that is internal to your system.

You could just remove it. But all your iOS/Android/desktop clients rely on it. If you remove it, these applications will break.

Instead, you could just use this library and have it support responses both with the user-ID and without. This is what the **version** parameter is for:


```
GET http://myapi.myapp.com/user/123
Accept: application/myapp;version=1;format=json
```

Response:

```
{
  "user": {
    "id": 123,
    "name": "sobolan",
    "email": "someemail@example.com",
    "age": 99
  }
}
```

If you use a different version, let's say "2", you can have the API do this:

```
GET http://myapi.myapp.com/user/123
Accept: application/myapp;version=2;format=json
```

Response:

```
{
  "user": {
    "name": "sobolan",
    "email": "someemail@example.com",
    "age": 99
  }
}
```

Notice how the "id" field is missing. This is extremely easy to do with this library.

After you do this, all you have to do is **gradually** (and on your own time) **migrate** your clients to using version "2" of your API.

When you're done, just bump the default version of the API so that "id" doesn't show up by default and you're done !

**No more stressful syncing of deployments** and **no more nightmares** of clients that might or might not break ;)

# How to use it

A complete documentation about how to use the library can be found here: [/doc/howtouse.md](/doc/howtouse.md).

Please note that it may be trickier or more difficult to implement in the beginning. But it will feel more natural once you get used to it and its features.

# Complete Example

A complete example that uses the library can be found here: [sobolan/rest-negotiator-example](https://github.com/SoboLAN/rest-negotiator-example/).

# License

This library is published under the MIT license.