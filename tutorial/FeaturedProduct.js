import React from 'react';

export default function(props) {
    return (
        <div>
            <div className="component-light product-show p-3 mb-5">
                <h5 className="text-center">Featured Product!</h5>
                <a href={'/product/'+props.product.id}>
                    <img
                        alt={ props.product.name }
                        src={props.product.imageUrl}
                        className="d-block"
                    />
                </a>
                <div className="pt-3">
                    <h6>
                        {props.product.name}
                    </h6>

                    <a href={'/product/'+props.product.id} className="btn btn-sm btn-primary">More info</a>
                </div>
            </div>
        </div>
    )
}
