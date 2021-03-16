import { Controller } from 'stimulus';
import ReactDOM from 'react-dom';
import React from 'react';
import FeatureProduct from '../components/FeatureProduct';

export default class extends Controller {
    connect() {
        ReactDOM.render(
            <FeatureProduct />,
            this.element
        )
    }
}
