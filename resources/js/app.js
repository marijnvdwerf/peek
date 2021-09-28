import _ from 'lodash';

function component() {
    const element = document.createElement('h1');

    // Lodash, now imported by this script
    element.innerHTML = _.join(['Hej', 'världen'], ' ');

    return element;
}

document.body.appendChild(component());
