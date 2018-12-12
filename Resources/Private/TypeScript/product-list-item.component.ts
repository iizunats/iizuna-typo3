import {AbstractComponent, Component, EventListener} from "iizuna";

@Component({
	selector: 'product-list-item'
})
export class ProductListItemComponent extends AbstractComponent {

	/**
	 * @description
	 * Add a simple click listener to the element and redirect to the page to demonstrate the component
	 */
	@EventListener('click')
	show() {
		window.location.href = '/product/' + this.identifier;
	}
}