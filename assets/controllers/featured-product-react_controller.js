import { Controller } from 'stimulus';
import ReactDOM from 'react-dom';
import React from 'react';
import FeatureProduct from '../components/FeatureProduct';

export default class extends Controller {
    static values = {
        product: Object
    }

    connect() {
        ReactDOM.render(
            <FeatureProduct product={this.productValue} />,
            this.element
        )
    }
}
