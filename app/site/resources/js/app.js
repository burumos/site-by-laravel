import React from 'react';
import ReactDOM from 'react-dom';
import {
  BrowserRouter as Router,
  Switch,
  Route,
} from 'react-router-dom';
import '../sass/app.scss';

import Top from './top';

const App = () => {

  return (
    <Router>
      <Switch>
        <Route path="/">
          <Top></Top>
        </Route>
      </Switch>
    </Router>
  );
}

const root = document.getElementById('root');
ReactDOM.render(
  <App />,
  root
);
