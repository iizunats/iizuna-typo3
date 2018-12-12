import {
	AbstractComponent,
	Component,
	ComponentFactory,
	ElementAttribute,
	EventListener,
	HtmlElementUtility,
	Template
} from "iizuna";
import * as $ from "jquery";
import {ProductListItemComponent} from "./product-list-item.component"
import {Product} from "./product";

@Component({
	selector: 'product',
	childrenSelectors: [
		'product-list',
		'next-product-page'
	]
})
export class ProductComponent extends AbstractComponent {

	/**
	 * @description
	 * This page value is overwritten by the value if the corresponding element attribute by the server
	 * @type {number}
	 */
	@ElementAttribute()
	page = 0;

	/**
	 * @description
	 * If the user click on the "next-product-page" button, then load the next products from the server based by the current page
	 * @param {HTMLElement} element
	 */
	@EventListener('click', 'next-product-page')
	loadMoreProducts(element: HTMLElement) {
		//Get the url of the api that should be used to paginate
		const apiUrl = HtmlElementUtility.getSelectorValue('next-product-page', element);
		//Create a Template from the api url (because we want to replace the page without knowing how the property is named on the server side)
		const apiTemplate = new Template(apiUrl);
		//Now replace the ${page} part of the url with the next page
		const renderedUrl = apiTemplate.render({page: ++this.page});
		//Make a GET Request to the TYPO3 Extension, expecting an array of Products of the next page
		$.get(renderedUrl).then((response: Product[]) => {
			//Empty the current list of Products
			this.clearProductList();
			//Create new Product Components for each product that the server returned
			for (let i = 0; i < response.length; i++) {
				this.createChildComponent(response[i])
			}
		});
	}

	/**
	 * @description
	 * Reset the list for the next page
	 */
	private clearProductList() {
		const listElement = this.getProductListElement();
		listElement.innerHTML = '';
	}

	/**
	 * @description
	 * Simply returns the first "product-list" element inside the current component
	 * @return {Element}
	 */
	private getProductListElement() {
		//This only works, because the child is registered at the top of this class inside of the Decorator
		return this.children['product-list'][0] as HTMLElement;
	}

	/**
	 * @description
	 * Creates a new product list element with the html of this.template (comes from iizuna-typo3 api) @todo add link to file
	 * @param product
	 */
	private createChildComponent(product: Product) {
		const $newListElement = $(this.template.render(product))[0];//We don't want to work with jQuery's Elements, just give us the first element
		this.getProductListElement().appendChild($newListElement);
		ComponentFactory.createComponentWithElement($newListElement, ProductListItemComponent as any);
	}
}