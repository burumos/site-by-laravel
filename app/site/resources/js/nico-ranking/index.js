import React from 'react';
import ReactDOM from 'react-dom';
import App from './app';

const root = document.getElementById('nico-ranking');
if (root) {
  window.addEventListener('load', () => {
    ReactDOM.render(
        <App />,
      root
    );
  })
}

