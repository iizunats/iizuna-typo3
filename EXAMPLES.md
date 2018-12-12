Define your template and render the partials like always.
Add the `data-template-source` to the main component which is responsible for api request and therefore for dynamic element creation.
Use the iizuna `ApiViewHelper` for rendering the correct api url.
```html
<!-- EXT:example/Resources/Private/Templates/Controller/Action.html -->
{namespace ii=iizunats\iizuna\ViewHelpers}
<section data-product data-page="{page}" data-template-source="{ii:uri.api(partial:'Controller/ListItem')}">
  <div data-product-list>
    <f:if condition="{products}">
      <f:for each="{products}" as="product">
        <f:render partial="Controller/ListItem" arguments="{product:product}" />
      </f:for>
    </f:if>
  </div>
  <f:alias map="{l: '{', r: '}'}">
    <!-- ADD EXPLANATION FOR ALIAS!!!! -->
    <button data-next-product-page="{f:link.action(action:'page',arguments:{page:'${l}page{r}'})}">Load more</button>
  </f:alias>
</section>
```

Also define the partial like always
```html
<!-- EXT:example/Resources/Private/Partials/Controller/ListItem.html -->
<article data-product-list-item="{product.uid}">
  <h2>{product.title}</h2>
  <p>{product.description}</p>
  <f:render partial="Controller/ListItemButton" />
</article>
```

You can also use viewhelpers like always!
```html
<!-- EXT:example/Resources/Private/Partials/Controller/ListItemButton.html -->
<button>Buy!</button>
```

But before you can usew the api, you have to register the partial in you `ext_localconf.php`. 
This is necessary for security reasons. Everyone could access every partial if we wouldn't do so.
```php
//ext_localconf.php
$partialCache = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(iizunats\iizuna\Utility\PartialRegistrationUtility::class);
$partialCache->register('example', 'Controller/ListItem');//First argument is the extension name, the second one is the local partial name with path
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
import {
	AbstractComponent,
	Component,
	ComponentFactory,
	DocumentHelper,
	ElementAttribute,
	EventListener,
	HtmlElementUtility,
	Template
} from "iizuna";
import * as $ from "jquery";
import {ProductListItemComponent} from "./product-list-item.component"

@Component({
	selector: 'product',
	childrenSelectors: [
		'product-list',
		'next-product-page'
	]
})
export class ProductComponent extends AbstractComponent {

	@ElementAttribute()
	page = 0;

	@EventListener('click', 'next-product-page')
	loadMoreProducts(element: HTMLElement) {
		const apiUrl = HtmlElementUtility.getSelectorValue('next-product-page', element);
		const apiTemplate = new Template(apiUrl);
		$.get(apiTemplate.render({page: ++this.page})).then((response) => {
			var listElement = HtmlElementUtility.querySelectByAttribute('product-list', this.element);
			listElement.innerHTML = '';//Reset the list for the next page
			for (let i = 0; i < response.length; i++) {
				const renderedTemplate = this.template.render(response[i]);
				const newProductListElement = DocumentHelper.createDOMFromString(renderedTemplate);
				listElement.appendChild(newProductListElement);
				ComponentFactory.createComponentWithElement(listElement, ProductListItemComponent);
			}
		});
	}
}
```

The child component which get created either by the server as server-side-rendered html or by the parent component.
If the user clicks on the component, then he is redirected to `/product/PRODUCT-UID`
```typescript
//./product-list-item.component.ts
import {AbstractComponent, Component, EventListener} from "iizuna";

@Component({
	selector: 'product-list-item'
})
export class ProductListItemComponent extends AbstractComponent {

	@EventListener('click')
	show() {
		window.location.href = '/product/' + this.identifier;
	}
}
```

Lastly register the components for bootstrapping.

```typescript
//./main.ts
import {ComponentFactory} from "iizuna";
import {ProductComponent} from "./product.component";
import {ProductListItemComponent} from "./product-list-item.component";

ComponentFactory.registerComponents([
	ProductComponent,
	ProductListItemComponent
]); 
```