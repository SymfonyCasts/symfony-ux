import React from 'react';

export default class MadeWithLove extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            hearts: 1,
        };
    }

    render() {
        return (
            <div>
                <span>Built with </span>
                <span
                    onClick={() => this.setState({
                        hearts: this.state.hearts + 1,
                    })}
                >
                    {'❤️'.repeat(this.state.hearts)}
                </span>
                <span> by your friends at </span>
                <a href="https://symfonycasts.com" target="_blank">
                    SymfonyCasts
                </a>
            </div>
        )
    }
}
