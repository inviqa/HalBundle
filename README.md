HalBundle
=========

* Support for managing converter services to create Hal resources from other objects.
* Adds (hacky) support for using ESI tags in JSON responses

Resource Conversion
-------------------

This bundle provides the service `inviqa.hal.converters`. Passing an object to its
`convert` method will locate a suitable converter service and use that to transform
the object to a Hal resource and then wrap it in a `Symfony\Component\HttpFoundation\Response`
object. An exception will be thrown if there is no suitable converter.

Current only JSON responses are supported, supporting the conversion to XML responses could
be added if needed.

There are no resource converted by default as they are specific to the use case.

Resource converters need to implement the `Inviqa\HalBundleInterface` interface:

 ```
 namespace Inviqa\HalBundle;

 interface Converter
 {
     public function convert($toConvert);

     public function supports($toConvert);
 }
 ```

`supports` is used to determine whether it can convert a particular object, type
checking is a typical way of deciding this. `convert` should return a `Nocarrier\Hal`
or an `Inviqa\HalBundle\EsiHal` object.

These can then be registered by tagging the service definition with `inviqa.hal.converter`.

There is also a `request.view` listener registered that will try and convert non `Response`
return values from controllers in a similar way to the `@Template` annotation. It's all a bit
magically since there is not even an annotation involved so probably best to avoid relying on this
or find a way of making it more explicit first.

ESI Support
-----------

If you want to send JSON Response but use ESI then you can use `Inviqa\HalBundle\EsiHal`
instead of `Nocarrier\Hal`. This will prevent esi tags being escaped in the output. To
add an embedded resource that is included by ESI and not directly rendered use

```
$hal->addResourceEsi('collection-name', EsiHal::fromJson($resource));
```

This will also accept an ESI tag as a string for the second argument.

To fetch the resource to embed from a controller action and render it within the
converter you can use the `inviqa.hal.esi_fragment_handler` service:

```
$rendered = $this->fragmentHandler->render("/embbeded-resource/{embeddableResource$id}", 'esi');
$hal->addResourceEsi('collection-name', EsiHal::fromJson($rendered));
```
Where `$this->fragmentHandler` is the `inviqa.hal.esi_fragment_handler` service.