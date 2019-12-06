import React from 'react';
import ReactDOM from 'react-dom';
import { Provider } from 'react-redux';
import { createStore, applyMiddleware } from 'redux';
import thunk from 'redux-thunk';
import reducer from './reducer'
import App from './app';

const store = createStore(reducer, applyMiddleware(thunk));

const root = document.getElementById('nico')
if (root) {
  window.addEventListener('load', () => {
    ReactDOM.render(
      <Provider store={store}>
        <App />
      </Provider>,
      root
    );
  })
}

