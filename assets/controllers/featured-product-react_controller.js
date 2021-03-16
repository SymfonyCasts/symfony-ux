import { Controller } from 'stimulus';
import React from 'react';
import FeatureProduct from '../components/FeatureProduct';

export default class extends Controller {
    static values = {
        product: Object
    }

    connect() {
        import('react-dom').then((ReactDOM) => {
            ReactDOM.default.render(
                <FeatureProduct product={this.productValue} />,
                this.element
            )
        })
    }
}
