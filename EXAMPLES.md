Define your template and render the partials like always.
Add the `data-template-source` to the main component which is responsible for api request and therefore for dynamic element creation.
Use the iizuna `ApiViewHelper` for rendering the correct api url.
```html
<!-- EXT:example/Resources/Private/Templates/Product/List.html -->
```

Also define the partial like always
```html
<!-- EXT:example/Resources/Private/Partials/Controller/ListItem.html -->
```

You can also use viewhelpers like always!
```html
<!-- EXT:example/Resources/Private/Partials/Controller/ListItemButton.html -->
```

But before you can usew the api, you have to register the partial in you `ext_localconf.php`. 
This is necessary for security reasons. Everyone could access every partial if we wouldn't do so.
```php
//ext_localconf.php
```

The rendered page could look like this if the user accesses the page and can only see two products
```html
<!-- https://www.example.com/products (containing the product plugin of the example extension) -->
<section data-product data-page="0" data-template-source="https://www.example.com/iizuna/example/Controller/ListItem">
  <div data-product-list>
    <article data-product-list-item>
      <h2>Red Sneaker</h2>
      <p>A Sneaker for your Feet!!!</p>
      <button>Buy!</button>
    </article>
    <article data-product-list-item>
      <h2>Blue Socks</h2>
      <p>Socks you can combine with our red sneakers!</p>
      <button>Buy!</button>
    </article>
  </div>
  <button data-next-product-page="https://www.example.com/products?tx_example_product[action]=page&tx_example_product[controller]=Product&tx_example_product[page]=${page}">Load more</button>
</section>
```

The result of the api which url was rendered into the `data-template-source` attribute of the `data-product-list` Element
then returns a template which can be used by iizuna.
```html
<!-- https://www.example.com/iizuna/example/Controller/ListItem -->
<article data-product-list-item="${product.uid}">
  <h2>${product.title}</h2>
  <p>${product.description}</p>
  <button>Buy!</button>
</article>
```

The basics for "configuring" the typo3 extension is done. lets get into a simple iizuna app:

First create the main product app which is responsible for creating new child components based by the repsonse of a api 
```typescript
//./product.component.ts
```

The child component which get created either by the server as server-side-rendered html or by the parent component.
If the user clicks on the component, then he is redirected to `/product/PRODUCT-UID`
```typescript
//./product-list-item.component.ts

```

Lastly register the components for bootstrapping.

```typescript
//./main.ts

```